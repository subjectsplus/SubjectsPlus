<?php 
/**
 *   @file save_guide.php
 *   @brief Save the contents of the guide.
 *   @description Called by guide.js, which passes in an array of all the pluslets for a guide
 *   and their position within the page (i.e., left, center or right column + row number).  The existing
 *   entries in the intervening pluslet_section table are emptied out, and new ones are added.
 *
 *   @author jlittle
 *   @date updated jul 2013
 */
$subsubcat = "";
$subcat = "guides";
$page_title = "Save Guides include";
$header = "noshow";

include(dirname(__FILE__)."/../../includes/header.php");

use SubjectsPlus\Control\Guide\SaveGuide;
use SubjectsPlus\Control\Querier;

$db = new Querier;
$saveGuide = new SaveGuide($_POST, $db);
$saveGuide->save();