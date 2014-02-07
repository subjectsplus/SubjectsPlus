<?php

/**
*   @file
*   @brief create json listing of subject guides
*
*   @author adarby
*   @date
*   @todo
*/

$header = "noshow";

include("../control/includes/header.php");

try {$dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}
$orig = $_GET["collection"];
$param = $_REQUEST["term"];

$_GET["collection"] = "faq";

switch ($_GET["collection"]) {
	case "guides":
	$q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE '%$param%'";

	break;
	case "records":
	$q = "SELECT title_id, title FROM title WHERE title LIKE '%$param%'";

	break;
	case "faq":
	$q = "SELECT faq_id, LEFT(question, 55) FROM faq WHERE question LIKE '%$param%'";
	break;
	case "talkback":
	$q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE '%$param%'";
	break;
	case "admin":
	$q = "SELECT staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE (fname LIKE '%$param%') OR (lname LIKE '%$param%')";
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