<?php

$page_title = "Search Results";
$subcat = "home";

include("includes/header.php");
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Search;
use SubjectsPlus\Control\Dropdown;

// Categories for searching and sortby
$search_categories = array("all" => "All", "records" => "Records",
"guides" => "Guides", "faq" => "FAQs", "talkback" => "Talkbacks",
"pluslets" => "Pluslets", "staff" => "Staff");

$sortby_categories = array("relevance" => "Relevance", 
"alphabetical_ascending" => "Alphabetical (A-Z)", 
"alphabetical_descending" => "Alphabetical (Z-A)");

// Defaults for category and sortby
$default_category = "all";
$default_sortby = "relevance";

if (isset($_GET["searchterm"]) && strlen(trim($_GET["searchterm"])) > 0) {

	// scrub incoming
	$_GET["searchterm"] = scrubData($_GET["searchterm"]);

	if (!isset($_GET["category"])) {
		$_GET["category"] = $default_category;
	} else {
		$_GET["category"] = scrubData($_GET["category"]);
	}

	if (!isset($_GET["sortby"])) {
		$_GET["sortby"] = $default_sortby;
	} else {
		$_GET["sortby"] = scrubData($_GET["sortby"]);
	}

	$search = new Search;
	$search->setSearch($_GET['searchterm']);

	$results = NULL;

	switch($_GET["category"]) {
		case "records":
			$results = $search->getRecordSearch($_GET["sortby"]);
			$subcat = "records";
			break;
		
		case "guides":
			$results = $search->getSubjectGuideSearch($_GET["sortby"]);
			$subcat = "guides";
			break;

		case "talkback":
			$results = $search->getTalkbackSearch($_GET["sortby"]);
			$subcat = "talkback";
			break;
		
		case "faq":
			$results = $search->getFAQSearch($_GET["sortby"]);
			$subcat = "faq";
			break;
		
		case "pluslets":
			$results = $search->getPlusletSearch($_GET["sortby"]);
			$subcat = "pluslet";
			break;
		
		case "staff":
			$results = $search->getStaffSearch($_GET["sortby"]);
			$subcat = "admin";
			break;

		case "all": // Deliberate fall-through
		default:
			$results = $search->getResults($_GET["sortby"]);
			break;
	}
	
	// Loop through each content type returned in array
	foreach ($results as $result) {
		
		// Matching text does not exist or is empty
		if (!isset($result['matching_text']) || trim($result['matching_text']) == "") {
			// Use additional text instead
			if (isset($result['additional_text']) && trim($result['additional_text']) != ""
				&& $result['content_type'] != "Pluslet") {
				
				// Length of additional_text to display
				$text_length = 25;
				
				// Display additional_text as matching_text up to the length of text_length
				$result['matching_text'] = $result['additional_text'];
				if (strlen($result['additional_text']) <= $text_length) {
					$result['matching_text'] = $result['additional_text'];
				} else {
					$result['matching_text'] = trim(substr($result['additional_text'], 0, $text_length))  . "...";
				}
			} else {
				// neither the matching text nor the additional text are available, 
				// pluslets have an incompatible additional_text value (HTML),
				// skip the result
				continue;
			}
		}

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
	$subcat = "home";
	$subtitle = _("No search term entered");
	$search_result =  _("<p>Please search for something with the box above.</p>");
}
?>

<div id="main-content">
	<div class="pure-g">
		<div class="pure-u-1-2">

		<?php
		// Create an additional Search bar pluslet with sort by methods
		$search_subtitle = ("Search");
		
		if (!isset($_GET["searchterm"])) {
			$_GET["searchterm"] = "";
		}

		if (!isset($_GET["category"])) {
			$_GET["category"] = "all";
		}

		$pluslet_html = ""; // accumulation of search box html, category html, and sortby html

		// Search box
		$input_box = NULL;
		
		$input_box = new CompleteMe("sp_search_additional", $CpanelPath . "search.php", "", "Search", $subcat, "45%", "control", $_GET["searchterm"]);
		
		$input_box_html = "<div style=\"display: inline-block\">" . $input_box->displayBox(false) . "</div>&nbsp;&nbsp;";

		// sortby dropdown
		$sortby_dropdown = new Dropdown("sortby_dropdown", $sortby_categories, $_GET["sortby"], "", "", true);
		$sortby_dropdown_js = "
		<script type=\"text/javascript\">
		jQuery(document).ready(function() {
    
			$(\"#sortby_dropdown select\").bind(\"change\", function() {
				var url = \"search.php?searchterm={$_GET["searchterm"]}&category={$_GET["category"]}&sortby=\" + $(this).val();
				window.location = url;
			});
		});
		</script>";
		$sortby_html = "<div id=\"sortby_dropdown\" style=\"display: inline-block; margin-top: 0.5%; float: right;\">" . $sortby_dropdown->display() . $sortby_dropdown_js . "</div>";


		// Create radio buttons for the search categories
		$searchcategories_html = "<br /><div align=\"center\" style=\"margin-top: 2%;\">";
		foreach ($search_categories as $key => $value) {
			$html = "<span class=\"";

			if (isset($_GET["category"]) && $_GET["category"] == $key) {
				$html .= "ctag-on"; // css class for category chosen
			} else {
				$html .= "ctag-off"; // css class for category not chosen
			}

			$html .= "\"><a href=\"search.php?searchterm={$_GET["searchterm"]}&category={$key}
					\">{$value}</a></span>";

			$searchcategories_html .= $html;
		}

		$searchcategories_html .= "</div>";

		// Create the pluslet
		$pluslet_html = $input_box_html . $sortby_html . $searchcategories_html;

		makePluslet($search_subtitle, $pluslet_html, "no_overflow");
		
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

include("includes/footer.php");

?>
