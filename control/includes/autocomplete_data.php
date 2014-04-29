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

//$_GET["collection"] = "records";

$param = $_GET["term"];

switch ($_GET["collection"]) {
  case "home":
  $q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE " . $db->quote("%" . $param . "%") ;
  break;
  case "guides":
  $q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE " . $db->quote("%" . $param . "%") ;

  break;
  case "records":
  $q = "SELECT title_id, title FROM title WHERE title LIKE " . $db->quote("%" . $param . "%") ;
  
  break;		
  case "faq":
  $q = "SELECT faq_id, LEFT(question, 55) FROM faq WHERE question LIKE " . $db->quote("%" . $param . "%") ;
  break;
  case "talkback":
  $q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE " . $db->quote("%" . $param . "%") ;
  break;	
  case "admin":
  $q = "SELECT staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE (fname LIKE " . $db->quote("%" . $param . "%") . ") OR (lname LIKE " . $db->quote("%" . $param . "%") . ")";
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
