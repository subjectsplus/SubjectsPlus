<?php


//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');
include_once('../../../../control/includes/autoloader.php');

use \SubjectsPlus\Control\Querier;

//only do something if the search_terms is activated
if (isset($_POST["search_terms"]))
{
	//initiate Querier
	$db = new Querier();

	$content = '<strong>Results</strong><br />';

	if (get_magic_quotes_gpc()) {
		$searcher = $_POST["search_terms"];
	} else {
		$searcher = addslashes($_POST["search_terms"]);
	}

	// Connect to database
	try {
			} catch (Exception $e) {
		echo $e;
	}

	//create query to search terms
	$q = "SELECT title_id, title FROM title WHERE title LIKE '%" . $searcher . "%' ORDER BY title";

	//query results
	$r = $db->query($q);

	//total returned rows
	$total_items = count($r);

	//return message if no results
	if ($total_items == 0) {
		$content .= "<br /><div valign=\"top\" style=\"float: left; min-width: 230px;\"><p>" . _("There were no results matching your query.") . "</p></div>";
	} else
	{
		//while rows exist
		foreach ($r as $myrow)
		{
			$token = "";

			$token = "{{dab},{" . $myrow[0] . "}, {" . $myrow[1] . "}";

			$content .= "<br /><div style=\"clear: both; padding: 3px 5px; font-size: 12px;\"> <input id=\"chosen_token\" name=\"but\" type=\"radio\" value=\"$token\"> $myrow[1]</div>\n";
		}
	}

	print $content;
}
?>