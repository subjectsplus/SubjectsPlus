<?php
require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Stats\GuideStats;

header('Content-Type: application/json');


if ( isset($_GET['short_form'])) {
	
	$db = new Querier;
	$stats = new GuideStats($db);
	$stats->loadStats($_GET['short_form']);
	
	echo $stats->toJSON();
	
}