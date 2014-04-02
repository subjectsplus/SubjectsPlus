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

include("../includes/header.php");

try {$dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}
$orig = scrubData($_GET["collection"]);
$param = scrubData($_REQUEST["term"]);

//$_GET["collection"] = "records";

switch ($_GET["collection"]) {
	case "guides":
	$q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE '%" . mysql_real_escape_string($param) . "%'";

	break;
	case "records":
	$q = "SELECT title_id, title FROM title WHERE title LIKE '%" . mysql_real_escape_string($param) . "%'";
	
	break;		
	case "faq":
	$q = "SELECT faq_id, LEFT(question, 55) FROM faq WHERE question LIKE '%" . mysql_real_escape_string($param) . "%'";
	break;
	case "talkback":
	$q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE '%" . mysql_real_escape_string($param) . "%'";
	break;	
	case "admin":
	$q = "SELECT staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE (fname LIKE '%" . mysql_real_escape_string($param) . "%') OR (lname LIKE '%" . mysql_real_escape_string($param) . "%')";
	break;
	
}
//query the database



$r = $db->query($q);

$arr = array();

$i = 0;
foreach ($r as $myrow){
  $arr[$i]['value'] = $myrow[0];
  $arr[$i]['label'] = $myrow[1];
  $i++;
}

//echo JSON to page

$response = json_encode($arr);
echo $response;


?>