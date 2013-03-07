<?php

//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');

//only do something if the search_terms is activated
if (isset($_POST["search_terms"]))
{
	$content = '<strong>Results</strong><br />';

	if (get_magic_quotes_gpc()) {
		$searcher = $_POST["search_terms"];
	} else {
		$searcher = addslashes($_POST["search_terms"]);
	}

	// Connect to database
	try {
		$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
	} catch (Exception $e) {
		echo $e;
	}

	//create query to search terms
	$q = "SELECT title_id, title FROM title WHERE title LIKE '%" . $searcher . "%' ORDER BY title";

	//query results
	$r = mysql_query($q);

	//total returned rows
	$total_items = mysql_num_rows($r);

	//return message if no results
	if ($total_items == 0) {
		$content .= "<br /><div valign=\"top\" style=\"float: left; min-width: 230px;\"><p>" . _("There were no results matching your query.") . "</p></div>";
	} else
	{
		//while rows exist
		while ($myrow = mysql_fetch_array($r))
		{
			$token = "";

			$token = "{{dab},{" . $myrow[0] . "}, {" . $myrow[1] . "}";

			$content .= "<br /><div style=\"clear: both; padding: 3px 5px; font-size: 12px;\"> <input id=\"chosen_token\" name=\"but\" type=\"radio\" value=\"$token\"> $myrow[1]</div>\n";
		}
	}

	print $content;
}
?>