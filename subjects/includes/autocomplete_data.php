<?php

/**
 *   @file
 *   @brief create json listing of subject guides
 *
 *   @author adarby
 *   @date
 *   @todo
 */
use SubjectsPlus\Control\Querier;

include("../../control/includes/config.php");
include("../../control/includes/functions.php");
include("../../control/includes/autoloader.php");


$db = new Querier();

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
  case "home":
  $q = "SELECT shortform, subject FROM subject WHERE subject LIKE"  . $db->quote('%' . $param . '%');
  
  break;

  case "guides":
  $q = "SELECT shortform, subject FROM subject WHERE subject LIKE"  . $db->quote('%' . $param . '%' );

  break;

  case "records":
  $q = "SELECT title_id, title FROM title WHERE title LIKE " . $db->quote('%' . $param . '%');

  break;


  case "faq":

  $q = "SELECT faq_id, LEFT(question, 55) FROM faq WHERE question LIKE". $db->quote('%' . $param . '%');
  break;

  case "databases":
  $q = "SELECT location, title, access_restrictions
    FROM title t, location_title lt, location l
    WHERE t.title_id = lt.title_id
    AND lt.location_id = l.location_id
    AND title LIKE " . $db->quote( '%' . $param . '%');
  break;



  case "talkback":
  $q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE " . $db->quote('%' . $param  . '%') ;
  break;

}
//query the database

$r = $db->query($q);

$arr = array();

$i = 0;

foreach($r as $myrow) {

  // deal with records, which might need a proxy
  if ($_GET["collection"] == "databases") {
    if ($myrow[2] == 2) {
      $arr[$i]['value'] = $proxyURL . $myrow[0];
    } else {
      $arr[$i]['value'] = $myrow[0];
    }
  } else {
    $arr[$i]['value'] = $myrow[0];
  }
  
  //$arr[$i]['label'] = $myrow[1];
  $arr[$i]['label'] = html_entity_decode($myrow[1], ENT_QUOTES); 
  $i++;
}
//echo JSON to page

$response = json_encode($arr);
echo $response;
