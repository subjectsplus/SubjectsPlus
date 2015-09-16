<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 9/15/15
 * Time: 5:15 PM
 */

require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\UserGuide;

header('Content-Type: application/json');


$db = new Querier;
$guides = new UserGuide($db);
$guides->loadUserGuides($_GET['staff_id']);

echo $guides->toJSON();
