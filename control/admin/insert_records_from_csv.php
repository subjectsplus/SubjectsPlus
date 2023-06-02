<?php
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Record;

$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Insert Records from CSV";


include("../includes/header.php");

$csv_filepath = "./test_records_csv.csv";
echo $_SESSION['staff_id'];

$currentTimestamp = date('Y-m-d H:i:s', strtotime('now'));
$content_box = $currentTimestamp;


print "
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";

makePluslet(_("Departments"), $content_box, "no_overflow");

print "</div>";

include("../includes/footer.php");