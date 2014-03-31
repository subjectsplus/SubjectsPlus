<?php

/**
 *   @file find_pluslets.php
 *   @brief Allows for discovery of pluslets from other guides.  Called by guide.js
 *
 *   @author adarby
 *   @date Sep 18, 2009

 not being used?
 */

// print_r($_POST);

$subsubcat = "";
$subcat = "guides";
$page_title = "Find Pluslets include";
$header = "noshow";


include("../../includes/header.php");

// Connect to database
try {$dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}

if ($_POST["browse_subject_id"]) {

	$q = "SELECT distinct p.pluslet_id, LEFT(p.title, 25) FROM pluslet p, subject s, pluslet_subject ps
	WHERE p.pluslet_id = ps.pluslet_id
	AND s.subject_id = ps.subject_id
	AND s.subject_id = '" . $_POST["browse_subject_id"] . "'
	AND p.type != 'Special'";

} elseif ($_POST["search_terms"]) {

	$q = "SELECT distinct p.pluslet_id, LEFT(p.title, 25) FROM pluslet p
	WHERE p.title LIKE '%" . addslashes($_POST["search_terms"]) .  "%' ORDER BY p.title";

} else {
return;
}

//print $q;

$r = $db->query($q);

$total_items = count($r);

if ($total_items == 0 && (!isset($_POST["base"])) ) {
$content = "<div valign=\"top\" style=\"float: left; min-width: 230px;\">" . _("There were no results matching your query.") . "</div>";

} else {

while($myrow =  mysql_fetch_array($r)) {
print "<div class=\"draggable clone\" style=\"z-index: 10\" id=\"pluslet-id-" . $myrow[0] . "\"  >";
	if ($myrow["clone"] != 0) { print "c:"; }
print $myrow[1] . "</div>";

}

//print "</div>";

}
print $content;

?>
