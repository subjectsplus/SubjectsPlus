<?php
/**
 * @file collection.php
 * @brief Display the subject guides by collection splash page
 */

use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

$use_jquery = array("ui");

$page_title  = _( "Research Guide Collections" );
$description = _( "The best stuff for your research.  No kidding." );
$keywords    = _( "research, databases, subjects, search, find" );

$db         = new Querier;
$connection = $db->getConnection();

// let's use our Pretty URLs if mod_rewrite = TRUE or 1
if ( $mod_rewrite == 1 ) {
	$guide_path = "";
} else {
	$guide_path = "guide.php?subject=";
}

///////////////////////
// Have they done a search?

$search = "";

if ( isset( $_POST["search"] ) ) {
	$search = scrubData( $_POST["search"] );
}

// Get the subjects for jquery autocomplete
$suggestibles = "";  // init

$q = "select subject, shortform from subject where active = '1' AND type != 'Placeholder' order by subject";

$statement = $connection->prepare( $q );
$statement->execute();
$r = $statement->fetchAll();

//initialize $suggestibles
$suggestibles = '';

foreach ( $r as $myrow ) {
	$item_title = trim( $myrow[0][0] );

	if ( ! isset( $link ) ) {
		$link = '';
	}

	$suggestibles .= "{text:\"" . htmlspecialchars( $item_title ) . "\", url:\"$link$myrow[1][0]\"}, ";
}

$suggestibles = trim( $suggestibles, ', ' );


// Get our newest guides
$q2 = "select subject, subject_id, shortform from subject where active = '1' and type != 'Placeholder' order by subject_id DESC limit 0,5";

$statement = $connection->prepare( $q2 );
$statement->execute();
$r2 = $statement->fetchAll();

$newest_guides = "<ul>\n";

foreach ( $r2 as $myrow2 ) {
	$guide_location = $guide_path . $myrow2[2];
	$newest_guides  .= "<li><a href=\"$guide_location\">" . trim( $myrow2[0] ) . "</a></li>\n";
}

$newest_guides .= "</ul>\n";


// Get our newest databases
$qnew = "SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5";

$statement = $connection->prepare( $qnew );
$statement->execute();
$rnew = $statement->fetchAll();

$newlist = "<ul>\n";
foreach ( $rnew as $myrow ) {
	$db_url = "";

	// add proxy string if necessary
	if ( $myrow[2] != 1 ) {
		$db_url = $proxyURL;
	}

	$newlist .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";


// Add header now
include_once(__DIR__ . "/includes/header_splux.php" );

// put together our main result display

$guide_results = "";

if ( isset( $_GET["d"] ) ) {

	$guide_results = listGuideCollections( $_GET["d"] );

} else {
	// Default collection listing
	$intro         = "<p></p>";
	$guide_results = listCollections( $search );
}

// Now we are finally read to display the page
?>

<div class="section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h5 class="mt-3 mt-lg-0 mb-1"><a href="<?php print $PublicPath; ?>index.php" class="no-decoration default">Research Guides</a></h5>
        <h1></h1>
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
                            $input_box = new CompleteMe("quick_search_b", "index.php", $proxyURL, "Find Guides", "guides");
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
        <div class="row">
            <div class="col-lg-8">
                <?php print $guide_results; ?>
            </div>
            <div class="col-lg-4">
                <div class="feature-light popular-list p-3 mt-3">
                    <h4><?php print _( "- New Databases -" ); ?></h4>
                    <?php print $newlist; ?>
                    <a href="databases.php?letter=bytype&type=New_Databases" class="btn btn-default" role="button">See
                        all</a>
                    <h4 class="mt-4"><?php print _( "- New Guides -" ); ?></h4>
                    <?php print $newest_guides; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {
        // Move Collection Title to minimal page header
        var $collection_title = $('#collection_parent_title');
        $('.minimal-header h1').append($collection_title);

        //add class to ui-autocomplete dropdown
        $('.ui-autocomplete-input').addClass("index-search-dd");
        $('.index-search-area .pure-button').addClass("btn-small");

    });
</script>


<?php
// Load footer file
include_once(__DIR__ . "/includes/footer_splux.php" );
?>