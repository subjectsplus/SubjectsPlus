<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 11/10/15
 * Time: 12:34 PM
 */

require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\TabData;

header('Content-Type: application/json');


$db = new Querier;

$tabs = new TabData($db);
$tabs->loadTabs("", $_GET['subject_id']);


echo $tabs->toJSON();