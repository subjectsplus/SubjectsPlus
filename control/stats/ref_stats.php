<?php

/**
 *   @file ref_stats.php
 *   @brief Transactions statistics 
 *
 *   @author adarby
 *   @date mar 2014
 */

use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\RefStat;
$subcat = "stats";
$page_title = "Stats in SP";

include("../includes/config.php");
include("../includes/header.php");


try {
  $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
  echo $e;
}

if (isset($_POST["submit_record"])) {

  var_dump($_POST);

  exit;
  // Submit form

  $record = new RefStat("", "post");

  //////////////////////////////////

  if ($_POST["faq_id"] == "") {
    $record->insertRecord();
    $ok_record_id = $record->getRecordId();
  } 

  // Show feedback
  $feedback = $record->getMessage();
  // See all the queries?
  //$record->deBug();

echo "<div class=\"feedback\">$feedback</div><br /><br />";  

}

$record = new RefStat();
$record->outputForm();

include("../includes/footer.php");
?>
