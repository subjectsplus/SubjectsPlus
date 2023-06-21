<?php
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\AzRecord\RecordInsertFromCSV;

$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Insert Records from CSV";


include("../includes/header.php");

$db = new Querier();
$recordInsertObj = new RecordInsertFromCSV($db);
$csv_filepath = "ebooks.csv";

$insertRecords = $recordInsertObj->insertFromCSV($csv_filepath);


print "
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";

makePluslet(_("Record Insert From CSV"), $insertRecords, "no_overflow");

print "</div>";

include("../includes/footer.php");