<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 11/10/15
 * Time: 1:25 PM
 */

require_once(__DIR__ . "/../../includes/autoloader.php");
require_once(__DIR__ . "/../../includes/config.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\TabData;


$subsubcat = "";
$subcat = "guides";
$page_title = "Save Tab Order";
$header = "noshow";

include_once(__DIR__ . "/../../includes/header.php");

$db = new Querier;

$tabs = new TabData($db);
$tabs->saveTabOrder($_POST);
