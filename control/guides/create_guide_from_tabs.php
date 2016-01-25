<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 1/14/16
 * Time: 2:10 PM
 */

global $AssetPath;

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\CopyGuide;

include ("../includes/autoloader.php");
include ("../includes/config.php");

if (isset($_REQUEST['subject_id'])) {
    session_start();
    $db = new Querier;

    $subject_id = (int) $_REQUEST['subject_id'];
    $guide_base = new CopyGuide($_SESSION['staff_id'], $subject_id, $db);

    if( isset($_GET['tabs']) && $_GET['tabs'] != '' ) {
        $ids =  "{$_GET['tabs']}";
        $tab_ids = $guide_base->convertTabsToTabIds($ids);
    } else {
        $tab_ids = array();
    }

    $new_guide = $guide_base->copyGuideTransaction($_SESSION['staff_id'], $subject_id, $tab_ids);
    echo $new_guide;

    exit;
} else {
    include("../includes/header.php");
}