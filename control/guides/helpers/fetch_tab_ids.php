<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 9/1/16
 * Time: 10:27 AM
 */

require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\TabData;

header('Content-Type: application/json');


$db = new Querier;

$tabs = new TabData($db);
$tabs->fetchTabIdsBySubjectId($_GET['subject_id']);


echo $tabs->toJSON();