<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/24/16
 * Time: 12:19 PM
 */

header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once(__DIR__ . "/../../includes/autoloader.php");
require_once(__DIR__ . "/../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\PlusletData;

$db = new Querier();
$pluslet_id = $_GET['pluslet_id'];

$objPluslet = new PlusletData($db);
$pluslet = $objPluslet->fetchPlusletById($pluslet_id);

echo json_encode($pluslet);