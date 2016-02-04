<?php
/**
 *   @file search.php
 *   @brief Search results page
 *
 *   @author Jamie Little (little9)
 *   @date May 2014
 */

use SubjectsPlus\Control\AllHandler;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Search;
use SubjectsPlus\Control\CompleteMe;


$page_title = "Search Results";

$use_jquery = array("ui"); 

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

// scrub incoming
$_POST["searchterm"] = scrubData($_POST["searchterm"]);

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if (isset($subjects_theme)  && $subjects_theme != "") { include("themes/$subjects_theme/search.php"); exit;}

include("includes/header.php");

// Our search box
$input_box = new CompleteMe("sp_search", $PublicPath . "search.php", "search.php", "", '', "60", "");

if (isset($_POST["searchterm"])) {
	$search = new Search;
	$search->setSearch($_POST['searchterm']);

	$results = $search->getResults();

	// Loop through each content type returned in array
	foreach ($results as $result) {
		
		switch($result['content_type']) {

	 	  case 'Record':
	 	  	$records_results[] = "<a href='databases.php?letter=%" . $result['matching_text'] . "%'/>" . $result['matching_text'] .  "</a>";	    
		    break;

	 	  case 'Talkback':
	 	  	$talkback_results[] = "<a href='talkback.php?talkback_id=" . $result['id'] . "'/>" . $result['matching_text'] .  "</a>";	    
		    break;

		  case 'Subject Guide':
		    $guides_results[] = "<a href='guide.php?subject_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
		    break;

		  case 'FAQ':
		    $faq_results[] = "<a href='faq.php?faq_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
		    break;

		  case 'Pluslet':
		    $pluslets_results[] = "<a href='guide.php?subject_id=" . $result['parent_id'] . "#box-" . $result['tab_index'] . "-" . $result['id'] . "'/>" . $result['matching_text'] . "</a>";	    
		    break;
		  case 'Staff':
		    $staff_results[] = "<a href='staff.php?staff_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";	    
		    break;
		}   
	}

	// Now build our display

	$search_types = array("records", "guides", "plulsets", "faq", "staff", "talkback");

	    $colour1 = "#fff";
	    $colour2 = "#F6E3E7";
	    $colour3 = "highlight";

	$search_result = '';

	    foreach ($search_types as $key) {
	        $row_count = 0;
	        $currentArray = $key . "_results";
	        global $$currentArray;

	        if ($$currentArray) {
	            $intro = ""; // clear out the intro
	            $search_result .= "<h3>" . ucfirst($key) . "</h3>";

	            foreach ($$currentArray as $value) {
	                $row_colour = ($row_count % 2) ? $colour1 : $colour2;
	                $search_result .= "<div style=\"background-color:$row_colour ; padding: 2px;\" class=\"striper\">
	&nbsp;&nbsp;<img src=\"$IconPath/required.png\" alt=\"bullet\" /> " . $value . "</div>";
	                $row_count++;
	            }
	        }
	    }

$subtitle = _("Search Results for ") . $_POST['searchterm'];

} else {
	$subtitle = _("No search term entered");
	$search_result =  _("<p>Please search for something with the box above.</p>");
}
?>

  <div class="pure-g">
    <div class="pure-u-1-2">

  <div class="pluslet no_overflow">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Search Subject Guides, Database Records, Staff List, FAQs, etc."); ?></div>
      <div class="titlebar_options"></div>
    </div>
    <div class="pluslet_body">
    <?php $input_box->displayBox(); ?>
    </div>
    </div>

	<?php makePluslet($subtitle, $search_result, "no_overflow"); ?> 

    </div>
    <div class="pure-u-1-2">

    </div>
  </div>


<?php 

//print "<pre>";
//print_r($results);

include("includes/footer.php");

?>