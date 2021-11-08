<?php

$page_title = "Search Results";
$subcat = "home";

include_once(__DIR__ . "/includes/header.php");
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Search;

// scrub incoming
$_GET["searchterm"] = scrubData($_GET["searchterm"]);

if (isset($_GET["searchterm"])) {
	$search = new Search;
	$search->setSearch($_GET['searchterm']);

	$results = $search->getResults();

	// Loop through each content type returned in array
	foreach ($results as $result) {
		
		switch($result['content_type']) {

	 	  case 'Record':
	 	  	$records_results[] = "<a href='records/record.php?record_id=" . $result['id'] . "'/>" . $result['matching_text'] .  "</a>";	    
		    break;

	 	  case 'Talkback':
	 	  	$talkback_results[] = "<a href='talkback/talkback.php?talkback_id=" . $result['id'] . "'/>" . $result['matching_text'] .  "</a>";	    
		    break;

		  case 'Subject Guide':
		    $guides_results[] = "<a href='guides/guide.php?subject_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
		    break;

		  case 'FAQ':
		    $faq_results[] = "<a href='faq/faq.php?faq_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
		    break;

		  case 'Pluslet':
		    $pluslets_results[] = "<a href='guides/guide.php?subject_id=" . $result['parent_id'] . "#box-" . $result['tab_index'] . "-" . $result['id'] . "'/>" . $result['matching_text'] . "</a>";	    
		    break;
		  case 'Staff':
		    $staff_results[] = "<a href='admin/user.php?staff_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";	    
		    break;
		}   
	}

	// Now build our display

	$search_types = array("records", "guides", "pluslets", "faq", "staff", "talkback");

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

$subtitle = _("Search Results for ") . $_GET['searchterm'];

} else {
	$subtitle = _("No search term entered");
	$search_result =  _("<p>Please search for something with the box above.</p>");
}
?>

<div id="main-content">
	<div class="pure-g">
		<div class="pure-u-1-2">

		<?php
		// Additional Search bar pluslet
		$search_subtitle = "Search";
		$input_box = new CompleteMe("sp_search_additional", $CpanelPath . "search.php", "", "", $subcat, "", "private", $_GET["searchterm"]);
		$input_box_html = $input_box->displayBox(false);
		makePluslet($search_subtitle, $input_box_html, "no_overflow");
		
		?> 

		</div>
  </div>
  
	<div class="pure-g">
    	<div class="pure-u-1-2">

		<?php
		// Search Result pluslet
		makePluslet($subtitle, $search_result, "no_overflow");
		?> 

    	</div>
  </div>
</div>

<?php 

//print "<pre>";
//print_r($results);

include_once(__DIR__ . "/includes/footer.php");

?>
