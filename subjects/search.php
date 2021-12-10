<?php
/**
 *   @file search.php
 *   @brief Search results page
 *
 *   @author Jamie Little (little9)
 *   @date May 2014
 */

use SubjectsPlus\Control\Search;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Dropdown;

$use_jquery = array("ui"); 
$page_title = _("Search Results");

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

$this_fname = "search.php";
$that_fname = theme_file($this_fname, $subjects_theme);
if ( $this_fname != $that_fname ) { include($that_fname); exit; }

include(theme_file("includes/header.php", $subjects_theme));

// Categories for searching and sortby
$search_categories = array("all" => "All", "guides" => "Guides", "records" => "Databases",
	"faq" => "FAQs", "staff" => "Staff");

$sortby_categories = array("relevance" => "Relevance", 
"alphabetical_ascending" => "Alphabetical (A-Z)", 
"alphabetical_descending" => "Alphabetical (Z-A)");

// Defaults for category, sortby, and collections
$default_category = "all";
$default_sortby = "relevance";
$collections = "home";

if (isset($_GET["searchterm"]) && strlen(trim($_GET["searchterm"])) > 0) {
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
			$collections = "records";
			$results = $search->getRecordSearch($_GET["sortby"]);
			break;
		
		case "guides":
			$collections = "guides";
			$results = $search->getSubjectGuideSearch($_GET["sortby"]);
			break;
		
		case "faq":
			$collections = "faq";
			$results = $search->getFAQSearch($_GET["sortby"]);
			break;
		
		case "staff":
			$collections = "admin";
			$results = $search->getStaffSearch($_GET["sortby"]);
			break;

		case "all": // Deliberate fall-through
		default:
			$collections = "home";
			$results = $search->getResults($_GET["sortby"]);
			break;
	}
	
	// Our search box
	$input_box = new CompleteMe("sp_search", $PublicPath . "search.php", "search.php", "", $collections, "60", "public", $_GET["searchterm"], $_GET["sortby"]);

	if (count($results) > 0) {
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
				$databases_results[] = "<a href='record.php?id=" . $result['id'] . "'/>" . $result['matching_text'] .  "</a>";	    
				break;

			case 'FAQ':
				$faq_results[] = "<a href='faq.php?faq_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
				break;
	
			case 'Subject Guide':
				$guides_results[] = "<a href='guide.php?id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
				break;

			case 'Staff':
				$email = $result['matching_text'];
				$name_id = explode("@", $email);
				$staff_results[] = "<a href='staff_details.php?name=" . $name_id[0] . "'/>" . $result['matching_text'] ."</a>";	    
				break;
			}   
		}

		// Now build our display
		$search_types = array("guides", "databases", "faq", "staff");

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
	} else {
		// No results found
		$search_result = ("<p>No results found.</p>");
	}

	$subtitle = _("Search Results for ") . $_GET['searchterm'];

} else {
	// Our search box
	$input_box = new CompleteMe("sp_search", $PublicPath . "search.php", "search.php", "", "home", "60", "public", "");
	
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
    <?php
		if (!isset($_GET["searchterm"])) {
			$_GET["searchterm"] = "";
		}

		if (!isset($_GET["category"])) {
			$_GET["category"] = $default_category;
		}

		if (!isset($_GET["sortby"])) {
			$_GET["sortby"] = $default_sortby;
		}

		// input box html
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
		$searchcategories_html = "<br /><div align=\"center\" style=\"margin-top: 2%;\">" . ("<i>Filter:</i>") . "&nbsp&nbsp&nbsp&nbsp";
		foreach ($search_categories as $key => $value) {
			$html = "<a style=\"";

			if (isset($_GET["category"]) && $_GET["category"] == $key) {
				// css style for if category is on
				$html .= "font-weight: bold; text-decoration-line: none; color: #000000;";
			} else {
				// css style for if category is off
				$html .= "color: #000000;"; // css class for category not chosen
			}

			$html .= "\" href=\"search.php?searchterm={$_GET["searchterm"]}&category={$key}&sortby={$_GET["sortby"]}
					\">{$value}</a>";

			$searchcategories_html .= $html . "&nbsp&nbsp&nbsp&nbsp";
		}

		$searchcategories_html .= "</div>";
		
		// display the new components
		echo $input_box_html . $sortby_html . $searchcategories_html;
	?>
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

include(theme_file("includes/footer.php", $subjects_theme));

?>
