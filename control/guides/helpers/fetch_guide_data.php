<?php
header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\GuideData;

$db = new Querier();
$subject_id = '690289'; // $_GET['subject_id'];
$staff = "charlesbrownroberts@miami.edu";

$objGuide = new GuideData($db);
$guideData = $objGuide->fetchGuideData($subject_id);

echo json_encode($guideData);