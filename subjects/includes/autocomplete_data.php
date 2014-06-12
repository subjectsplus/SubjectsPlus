<?php
header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Autocomplete;
include('../../control/includes/config.php');
include("../../control/includes/autoloader.php");

$auto_complete = new Autocomplete();
$auto_complete->setSearchPage("public");

if (isset($_GET["term"])) {
  $auto_complete->setParam($_GET["term"]);
}

if  (isset($_GET["collection"])) {
  $auto_complete->setCollection($_GET["collection"]);
}
if (isset($_GET["subject_id"])) {
  $auto_complete->setSubjectId($_GET["subject_id"]);
}


echo $auto_complete->search();

