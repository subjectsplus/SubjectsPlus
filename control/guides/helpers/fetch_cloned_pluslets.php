<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 1/6/16
 * Time: 8:47 AM
 */

require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\PlusletData;
header('Content-Type: application/json');

$db = new Querier;

$objPlusletData = new PlusletData($db);
$objPlusletData->fetchClonedPlusletsById($_GET['master_id']);

echo $objPlusletData->toJSON();