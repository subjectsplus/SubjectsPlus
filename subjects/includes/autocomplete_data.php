<?php

/**
*   @file
*   @brief create json listing of subject guides
*
*   @author adarby
*   @date
*   @todo
*/

include("../../control/includes/config.php");
include("../../control/includes/functions.php");
include("../control/includes/autoloader.php");

try {$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}

if (isset($_GET["collection"])) {
  $orig = scrubData($_GET["collection"]);
} else {
  $orig = "";
}

if (isset($_REQUEST["term"])) {
  $param = scrubData($_REQUEST["term"]);
} else {
  $param = "";
}

//$_GET["collection"] = "records";

switch ($_GET["collection"]) {
	case "guides":
	$q = "SELECT shortform, subject FROM subject WHERE subject LIKE '%" . mysql_real_escape_string($param) . "%'";

	break;
	case "records":
	$q = "SELECT title_id, title FROM title WHERE title LIKE '%" . mysql_real_escape_string($param) . "%'";

	break;
	case "faq":
	$q = "SELECT faq_id, LEFT(question, 55) FROM faq WHERE question LIKE '%" . mysql_real_escape_string($param) . "%'";
	break;
  	case "databases":
	$q = "SELECT location, title
    FROM title t, location_title lt, location l
    WHERE t.title_id = lt.title_id
    AND lt.location_id = l.location_id
    AND title LIKE '%" . mysql_real_escape_string($param) . "%'";
	break;
	case "talkback":
	$q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE '%" . mysql_real_escape_string($param) . "%'";
	break;

}
//query the database

$r = MYSQL_QUERY($q);

$arr = array();

$i = 0;
while($myrow =  mysql_fetch_array($r)) {
  $arr[$i]['value'] = $myrow[0];
  $arr[$i]['label'] = $myrow[1];
  $i++;
}

//echo JSON to page

$response = json_encode($arr);
echo $response;


?>