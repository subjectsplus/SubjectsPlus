<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/30/16
 * Time: 11:01 AM
 */

require_once(__DIR__ . "/../../includes/autoloader.php");
require_once(__DIR__ . "/../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\PlusletData;

header('Content-Type: application/json');

$tab_id = $_GET['tab_id']; // "4531189";

$db = new Querier;
$objPlusletData = new PlusletData($db);
$objPlusletData->getClonedPlusletsBySubjectIdTabId($tab_id);

echo $objPlusletData->toJSON();