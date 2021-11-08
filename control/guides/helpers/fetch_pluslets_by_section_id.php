<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 1/6/16
 * Time: 8:47 AM
 */

require_once(__DIR__ . "/../../includes/autoloader.php");
require_once(__DIR__ . "/../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\PlusletData;
header('Content-Type: application/json');

$db = new Querier;

$objPlusletData = new PlusletData($db);
$objPlusletData->fetchPlusletsBySectionId($_GET['section_id']);

echo $objPlusletData->toJSON();

// 2142263024

// 2142263021