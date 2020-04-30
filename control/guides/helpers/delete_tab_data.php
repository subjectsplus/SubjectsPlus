<?php
require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");
require_once("../../includes/functions.php");
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\GuideData;

header('Content-Type: application/json');


$db = new Querier;

$tabs = new GuideData($db);
$subject_id = scrubData( $_GET['subject_id'] );
$tab_id     = scrubData( $_GET['tab_id'] );
$tabs->deleteTabAndSectionAndPluslets( $subject_id, $tab_id);


echo $tabs->toJSON();