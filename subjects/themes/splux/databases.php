<?php
/**
 * @file databases.php
 * @brief themed override of db a-z page for U Miami
 *
 */

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\DbHandler;


$db = new Querier;

//$use_jquery = array("sp_legacy");

$page_title  = _( "Database List" );
$description = _( "An alphabetical list of the electronic resources available." );
$keywords    = _( "library, research, electronic journals, databases, electronic resources, full text, online, magazine, articles, paper, assignment" );

// set a default if the letter isn't set
if ( ! isset( $_GET["letter"] ) ) {
	$_GET["letter"] = "A";
	$page_title     .= ":  A";
} else {
	$page_title .= ": " . ucfirst( scrubData( $_GET["letter"] ) );
}

// Deal with databases by subject display
if ( ! isset( $_GET["subject_id"] ) ) {
	$_GET["subject_id"] = "";
	$clean_id           = "";
} else {
	$clean_id = scrubData( $_GET["subject_id"], "integer" );
}

if ( $_GET["letter"] == "bysub" ) {
	$page_title = _( "Database List By Subject" );
	if ( $clean_id == "" ) {
		$_GET["subject_id"] = "";
		$show_subjects      = true;
	} else {
		$show_subjects = false;
		// add subject name to title

		$connection = $db->getConnection();
		$statement  = $connection->prepare( "SELECT subject FROM subject WHERE subject_id = :id AND (type = 'Subject' OR type = 'Placeholder') AND active = '1'" );
		$statement->bindParam( ":id", $clean_id );
		$statement->execute();
		$myrow = $statement->fetchAll();

		$page_title .= ": " . $myrow[0][0];
	}
} else {
	$_GET["subject_id"] = "";
	$show_subjects      = false;
}

// Deal with databases by type display
if ( $_GET["letter"] == "bytype" ) {
	$page_title = _( "Database List By Format" );
	if ( ! isset( $_GET["type"] ) ) {
		$_GET["type"] = "";
		$show_types   = true;
	} else {
		$clean_type  = ucfirst( scrubData( $_GET["type"] ) );
		$pretty_type = ucwords( preg_replace( '/_/', ' ', $clean_type ) );
		$page_title  .= ": " . $pretty_type;
		$show_types  = false;
	}
} else {
	$_GET["type"] = "";
	$show_types   = false;
	$clean_type   = "";
}

// Add a quick search
$description_search = 0; // init to negative
if ( isset( $_POST["searchterm"] ) ) {
	$_GET["letter"]     = "%" . $_POST["searchterm"];
	$page_title         = _( "Database List: Search Results" );
	$description_search = 1; // if you want to search descriptions, too, set to 1; otherwise to 0
}

// A-Z Listing
$alphabet = getLetters( "databases", $_GET["letter"], 1, true );


// Get our newest databases
$connection = $db->getConnection();
$statement  = $connection->prepare( "SELECT * FROM (SELECT DISTINCT title, location, access_restrictions
           FROM title as t
           INNER JOIN location_title as lt
           ON t.title_id = lt.title_id
           INNER JOIN location as l
           ON lt.location_id = l.location_id
           INNER JOIN restrictions as r
           ON l.access_restrictions = r.restrictions_id
           INNER JOIN rank as rk
           ON rk.title_id = t.title_id
           INNER JOIN source as s
           ON rk.source_id = s.source_id
WHERE l.ctags LIKE '%New_Databases%'
           AND eres_display = 'Y'
ORDER BY RAND()
ASC limit 0,5) results ORDER BY title" );
$statement->execute();

$newlist = "";

if ( $rnew = $statement->fetchAll() ) {

	$newlist = "<ul>\n";
	foreach ( $rnew as $myrow ) {

		switch ($myrow[2]) {

			case 1:
				$url = $myrow[1];
				$rest_icons = "unrestricted";
				break;
			case 2:
			case 3:
				$url = $proxyURL . $myrow[1];
				$rest_icons = "restricted";
				break;
			case 4:
				$url = $myrow[1];
				$rest_icons = "restricted";
				break;
		}

		$newlist .= "<li><a href=\"$url\">$myrow[0]</a></li>\n";
	}
	$newlist .= "</ul>\n";

}

// Intro text
$intro = "";

if ( isset( $_POST["searchterm"] ) ) {
	$selected = scrubData( $_POST["searchterm"] );
	$intro    .= "<div class=\"feature-light p-3 mb-3\"><p class=\"mb-0\">Search results for <strong><em>$selected</em></strong></p></div>";
}

$intro .= "<div class=\"expander\"><a id=\"expander\" class=\"no-decoration default\">Expand/hide all descriptions</a></div>";


// Create our table of databases object
$our_items = new DbHandler();

$out = "";

// if we're showing the subject list, do so

if ( $show_subjects == true ) {
	$out .= $our_items->displaySubjects();

} elseif ( $show_types == true ) {

	$out .= $our_items->displayTypes();
} else {
	// if it's the type type, show filter tip
	if ( isset( $clean_type ) && $clean_type != "" ) {
		$out .= "<div class=\"feature-light p-3 mb-3\">Displaying databases filtered by <em><strong>$clean_type</strong></em>. <a href=\"databases.php?letter=bytype\" class=\"no-decoration\">View all types</a>.</div>";
	}

	// otherwise display our results from the database list

	$out .= $our_items->writeTable( $_GET["letter"], $clean_id, $description_search );
}

// Assemble the content for our main pluslet/box
$display = $out;


// Trial Databases //
// xxxUM trial databases--requires DB_Trials in ctags fieldxxx
// In v4.2 uses the "status" field set to "Trial"
$connection = $db->getConnection();
$statement  = $connection->prepare( "select distinct title, location, access_restrictions, title.title_id as this_record
        FROM title, location, location_title
        WHERE record_status = 'Trial'
        AND title.title_id = location_title.title_id
        AND location.location_id = location_title.location_id
        ORDER BY title" );
$statement->execute();

$trial_list = "";

if ( $rtrial = $statement->fetchAll() ) {
	$trial_list = "<ul>\n";
	foreach ( $rtrial as $myrow ) {

		// add proxy string if necessary
		if ( $myrow[2] != 1 ) {
			$db_url = $proxyURL;
		} else {
			$db_url = "";
		}

		$trial_list .= "<li><a href=\"" . $db_url . $myrow[1] . "\">$myrow[0]</a></li>\n";

	}

	$trial_list .= "</ul>\n";
	$trials     = true;

} else {
	$trial_list = "<p><strong>No trials at this time.</strong></p>";
	$trials     = false;
}

// Legend //
$legend = "<i class=\"fas fa-info-circle\"></i> = " . _( "Click for more information" ) . "\n";


// Now we are finally read to display the page
// Load header file
include_once(__DIR__ . "/includes/header_splux.php" );


// Our version 2 vs version 3 styles choice
if ( isset ( $v2styles ) && $v2styles == 1 ) {
	$db_results = $display;

	$layout = makePluslet( "", $db_results, "", "", false );
} else {
	print "version 3 styles not set up yet";
}
?>
<div class="section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
    </div>
</div>

    <div class="section section-half">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-lg-8 offset-sm-1 offset-lg-2">
                    <!-- Search Area -->
                    <div class="default-search">
                        <div class="index-search-area">
                            <?php
                            $input_box = new CompleteMe("quick_search", "databases.php", $proxyURL, "Find Databases", "records");
                            $input_box->displayBox();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<section class="section section-half-top">
    <div class="container">
        <script src="<?php print $AssetPath; ?>um-special/bootstrap-select.js"></script>
        <script>
            $(function () {
                $('#select_format').selectpicker({
                    style: 'btn-select-db',
                    width: '250px',
                    size: '10'
                });
                $('#select_subject').selectpicker({
                    style: 'btn-select-db',
                    width: '250px',
                    size: '10'
                });

                // show all db details
                $('#expander').click(function () {
                    $('.list_bonus').toggle();
                });

                //show individual db details
                $('span[id*=bib-]').click(function () {
                    var bib_id = $(this).attr("id").split("-");
                    $(this).parent().parent().find(".list_bonus").toggle();
                });

                // SP GA Event Tracking
                $('.trackContainer a').click(function () {
                    var label = $(this).text();
                    ga('send', 'event', 'OutboundLink', 'Click', label);
                });
            });
        </script>

        <form><?php print $alphabet; ?></form>
        <div class="row mt-2 mt-lg-5">
            <div class="col-lg-8">
                <?php print $intro;
                print $db_results;
                ?>
            </div>
            <div class="col-lg-4">
                <?php if ( $newlist ) { ?>
                    <div class="feature-light popular-list">
                        <h4>- New Databases -</h4>
                        <?php print $newlist; ?>
                        <a href="databases.php?letter=bytype&type=New_Databases" class="btn btn-default"
                           role="button">See all</a>
                    </div>
                <?php } ?>

                <?php if ( isset( $featured ) ) { ?>
                    <div class="feature-light popular-list">
                        <h4><?php print _( "- Featured Databases -" ); ?></h4>
                        <?php print $featured_list; ?>
                    </div>
                <?php } ?>

                <?php if ( isset( $trials ) ) { ?>
                    <div class="feature-light popular-list">
                        <h4>- Database Trials -</h4>
                        <?php print $trial_list; ?>
                        <p>Trial demonstrations of fee-based subscription services under consideration.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

    <script>
        $(function () {
            //add class to ui-autocomplete dropdown
            $('.index-search-area #quick_search').addClass("index-search-dd");
            $('.index-search-area .pure-button').addClass("btn-small");

        });
    </script>

<?php
// Load footer file
include_once(__DIR__ . "/includes/footer_splux.php" ); ?>