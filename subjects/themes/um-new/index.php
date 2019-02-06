<?php
/**
 * @file index.php
 * @brief Display the subject guides splash page
 */

use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\GuideList;

$page_title  = "Research Guides";
$description = "The best stuff for your research.  No kidding.";
$keywords    = "research, databases, subjects, search, find";

$db         = new Querier;
$connection = $db->getConnection();

// let's use our Pretty URLs if mod_rewrite = TRUE or 1
if ( $mod_rewrite == 1 ) {
	$guide_path = "";
} else {
	$guide_path = "guide.php?subject=";
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

$q2 = "select subject, subject_id, shortform from subject where active = '1' AND type != 'Placeholder' order by subject_id DESC limit 0,5";

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


// Add header now, because we need a value ($v2styles) from it
include( "includes/header_um-new.php" );


// put together our main result display
//**************************************

$pills              = ""; //init
$layout             = ""; //init
$collection_results = ""; //init

// Is this a search?
if ( isset( $_POST["searchterm"] ) && $_POST["searchterm"] != "" ) {
	$searchterm   = scrubData( $_POST["searchterm"] );
	$search_param = "%" . $searchterm . "%";

	$pills = "<div class=\"feature-light p-3 mb-3\">" . _( "Need to start over? " ) . "<a href=\"index.php\" class=\"no-decoration\">See All Research Guides</a></div>";

	$q_search = "select * from subject 
    WHERE active = '1' 
    AND type != 'Placeholder' 
    AND subject LIKE '$search_param'
    ORDER BY subject";


	$statement = $connection->prepare( $q_search );
	$statement->execute();
	$r_search = $statement->fetchAll();

	$col_1 = "<ul class=\"guide-listing list-unstyled\">";

	foreach ( $r_search as $key => $value ) {

		$guide_location = $guide_path . $value['shortform'];
		$list_bonus     = "";

		if ( $value[6] != "" ) {
			$list_bonus .= $value[6] . "<br /><br />";
		} // add description
		if ( $value[7] != "" ) {
			$list_bonus .= "<strong>Keywords:</strong> " . $value[7];
		} // add keywords

		$col_1 .= "<li><i class=\"fa fa-info-circle\"></i> <a href=\"$guide_location\" class=\"no-decoration default\">" . htmlspecialchars_decode( $value[1] ) . "</a>
            <div class=\"guide_list_bonus\">$list_bonus</div>
            </li>";
	}

	$col_1 .= "</ul>";  // close 'er up

	$layout .= "<div class=\"guide_list\"><div class=\"guide_list_header\"><h4>" . _( "Search Results for " ) . "<em>" . $searchterm . "</em></h4></div>" . $col_1 . "</div>";
} else {

	// ANCHOR buttons for guide types
	//**************************************
	$guide_type_btns = "<ul class=\"nav nav-pills justify-content-around justify-content-md-start\" id=\"pills-tab-guides\" role=\"tablist\">";

	$guide_type_btns .= "<li class=\"nav-item\"><a class=\"nav-link no-decoration default active\" id=\"show-Collection\" name=\"showCollection\" data-toggle=\"pill\" href=\"#section-Collection\" role=\"tab\" aria-controls=\"section-Collection\" aria-selected=\"true\">Guide Collections</a></li>";

	// We don't want our placeholder
	if ( in_array( 'Placeholder', $guide_types ) ) {
		unset( $guide_types[ array_search( 'Placeholder', $guide_types ) ] );
	}

	//Output guide buttons/pills
	foreach ( $guide_types as $key ) {
		$guide_type_btns .= "<li class=\"nav-item\"><a class=\"nav-link no-decoration default\" id=\"show-" . ucfirst( $key ) . "\" name=\"show$key\" data-toggle=\"pill\" href=\"#section-" . ucfirst( $key ) . "\" role=\"tab\" aria-controls=\"section-" . ucfirst( $key ) . "\" aria-selected=\"true\">";

		$guide_type_btns .= ucfirst( $key ) . " Guides</a></li>\n";
	}

	$guide_type_btns .= "</ul>";

	$pills = "<div class=\"pills-container\">" . $guide_type_btns . "</div>";

	// let's grab our collections
	$collection_results = listCollections( "", "2col", "true" );

	// We don't want our placeholder
	if ( in_array( 'Placeholder', $guide_types ) ) {
		unset( $guide_types[ array_search( 'Placeholder', $guide_types ) ] );
	}

	// loop through our source types
	foreach ( $guide_types as $key => $value ) {

		$guide_list = new GuideList( $db, $value, 1 );

		$all_guides = $guide_list->toArray(); // get our full listing of guides as an array

		$total_rows = count( $all_guides ); // total number of guides

		$switch_row = round( $total_rows / 2 );

		if ( $total_rows > 0 ) {

			$col_1 = "<div class=\"col-sm-6 col-lg-12 col-xl2-6\"><ul class=\"guide-listing list-unstyled\">";
			$col_2 = "<div class=\"col-sm-6 col-lg-12 col-xl2-6\"><ul class=\"guide-listing list-unstyled\">";

			$row_count = 1;

			foreach ( $all_guides as $myrow ) {

				$icon        = "fa-info-circle";
				$title_hover = "See guide description (when available)";

				$guide_location = $guide_path . $myrow['shortform'];
				$list_bonus     = "";

				if ( $myrow[6] != "" ) {
					$list_bonus .= $myrow[6] . "<br /><br />";
				} // add description
				if ( $myrow[7] != "" ) {
					$list_bonus .= "<strong>Keywords:</strong> " . $myrow[7];
				} // add keywords

				$our_item = "<li title=\"{$title_hover}\"><i class=\"fa {$icon}\"></i> <a href=\"$guide_location\" class=\"no-decoration default\">" . htmlspecialchars_decode( $myrow[1] ) . "</a>
            <div class=\"guide_list_bonus\">$list_bonus</div>
            </li>";

				if ( $row_count <= $switch_row ) {
					// first col
					$col_1 .= $our_item;

				} else {
					// even
					$col_2 .= $our_item;
				}

				$row_count ++;

			} //end foreach

			$col_1 .= "</ul></div>";
			$col_2 .= "</ul></div>";

			$layout .= "<div class=\"tab-pane guide_list\" id=\"section-$value\" role=\"tabpanel\" aria-labelledby=\"show-$value\"><div class=\"guide-list-expand\">Expand/hide all</div><div class=\"guide_list_header\"><a name=\"section-$value\"></a><h3>$value</h3></div><div class=\"row\">" . $col_1 . $col_2 . "</div></div>";

		} //end if

	}//end foreach

} // end search term check


//EXPERTS
//**************************************
// get all of our librarian experts into an array
$qexperts = "SELECT DISTINCT (s.staff_id), CONCAT(s.fname, ' ', s.lname) AS fullname, s.email, s.tel, s.title, sub.subject  FROM staff s, staff_subject ss, subject sub
          WHERE s.staff_id = ss.staff_id
          AND ss.subject_id = sub.subject_id
          AND s.active = 1
          AND sub.active = 1
          AND ptags LIKE '%librarian%'
          AND sub.type = 'Subject'
          GROUP BY s.staff_id
          ORDER BY RAND()
          LIMIT 0,3";

$statement = $connection->prepare( $qexperts );
$statement->execute();
$expertArray = $statement->fetchAll();

// init list item
$expert_item = "";

// additional text
$bonus_text = _( "Need help? Ask an expert!" );

$button_text = _( "See all experts" );

foreach ( $expertArray as $key => $value ) {

	$exp_image = getHeadshotFull( $value['email'] );

	$librarian_email = $value['email'];
	$name_id         = explode( "@", $librarian_email );

	$exp_profile = "<li class=\"d-sm-flex flex-sm-row flex-sm-nowrap justify-content-sm-start\"><div class=\"staff-img\"><div class=\"img-accent\"><a href=\"" . PATH_TO_SP . "subjects/staff_details.php?name=" . $name_id[0] . "\" class=\"no-decoration default\">" . $exp_image . "</a></div></div><div class=\"staff-details\"><p><strong><a href=\"" . PATH_TO_SP . "subjects/staff_details.php?name=" . $name_id[0] . "\" class=\"no-decoration default\">" . $value['fullname'] . "</a></strong><br /><em>" . $value['title'] . "</em></p><p class=\"mt-3\"><strong>Subjects:</strong> " . $value['subject'] . " <a href=\"" . PATH_TO_SP . "subjects/staff_details.php?name=" . $name_id[0] . "\" class=\"no-decoration default\">and more</a></p></div></li>";

	$expert_item .= $exp_profile;
}

$guide_experts = "$expert_item";


// Blackboard Integration

if ( isset ( $_GET["no_bb_guide"] ) ) {

	$bb_guide_not_found = intval( scrubData( $_GET["no_bb_guide"] ) );

	if ( $bb_guide_not_found == 1 ) {
		print "
  <div class=\"alert alert-danger\" role=\"alert\">
    <button class=\"notification-close-button btn\">x</button>
    <p>" . _( "Find the Research Guide that best meets your needs below." ) . "</p>
  </div>";
	}
}

if ( isset ( $_GET["no_lti_enabled"] ) ) {

	$no_lti_enabled = intval( scrubData( $_GET["no_lti_enabled"] ) );

	if ( $no_lti_enabled == 1 ) {
		print "
<div class=\"alert alert-danger\" role=\"alert\">
    <button class=\"notification-close-button btn\">x</button>
    <p>" . _( "Find the Research Guide that best meets your needs below." ) . "</p>
  </div>";
	}
}

if ( isset ( $_GET["invalid_lti_call"] ) ) {

	$invalid_lti_call = intval( scrubData( $_GET["invalid_lti_call"] ) );

	if ( $invalid_lti_call == 1 ) {
		print "
<div class=\"alert alert-danger\" role=\"alert\">
    <button class=\"notification-close-button btn\">x</button>
    <p>" . _( "Please access the LTI from the appropriate LMS." ) . "</p>
  </div>";
	}
}

// Legend
$legend = "Research Guides point you to materials and resources appropriate for particular disciplines, courses, and special topics.  These guides are curated by UM librarians and experts-in-training, including Library Research Scholars and Peer Research Consultants. In a world of abundant information resources, librarians create research guides to save you time and energy. If you would like to request a guide for a course or topic, please contact your librarian.";

// Now we are finally read to display the page
?>

<input id="jekyll-category" value="sp-guide" type="hidden">
<div class="feature section-minimal">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
        <hr align="center" class="hr-panel">
        <p class="mb-0 mt-md-2 mb-md-2 d-none d-md-inline-block text-left" style="line-height:1.2;"><?php print $legend; ?></p>

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon"></div>
        </div>
    </div>
</div>


<!-- Search Component -->
<section class="search-area d-none d-lg-block">
    <div class="full-search">
        <div class="container text-center">
            <div class="search-group">
                <div class="uml-site-search-container"></div>
            </div>
        </div>
    </div>
</section>



<section class="section section-half-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php print $pills; ?>

                <div class="tab-content" id="pills-tabContent-guides">
                    <?php
                    print $collection_results;
                    print $layout; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <h4 class="mb-2">Find an Expert</h4>
                <p><?php print $bonus_text; ?></p>
                <ul class="people-vertical list-unstyled">
                    <?php print $guide_experts; ?>
                </ul>

                <div class="text-center mt-3 mb-3 mb-lg-5"><a
                            href="<?php print PATH_TO_SP; ?>subjects/staff.php?letter=Subject Librarians"
                            class="btn btn-default" role="button"><?php print $button_text; ?></a></div>

                <div class="feature popular-list p-3 mt-3">
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
        // Toggle details for all guides in a category list
        $('.guide-list-expand').click(function () {
            $(this).parent().find('.guide_list_bonus').toggle();
        });

        // Toggle details for each guide list item in collection list
        $(".fa-plus-circle").click(function () {
            $(this).toggleClass('fa-plus-circle fa-minus-circle');
            $(this).parent().find('.guide_list_bonus').toggle();
        });

        // Toggle details for each guide list item
        $('.fa-info-circle').click(function () {
            $(this).parent().find('.guide_list_bonus').toggle();
        });

        //add class to ui-autocomplete dropdown
        $('.ui-autocomplete-input').addClass("index-search-dd");

        //LTI notification close button
        $( ".notification-close-button" ).click(function() {
            $(this).parent().hide();
        });
    });
</script>

<?php
// Load footer file
include( "includes/footer_um-new.php" );
?>