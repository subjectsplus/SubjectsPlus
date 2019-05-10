<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/30/16
 * Time: 11:01 AM
 */

require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\PlusletData;

header('Content-Type: application/json');

$section_id = $_GET['section_id']; // "4531189";

$db = new Querier;
$objPlusletData = new PlusletData($db);
$objPlusletData->fetchClonedParentPlusletsBySectionId($section_id);

echo $objPlusletData->toJSON();