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

use SubjectsPlus\Control\Guide\SaveGuide;

$subsubcat = "";
$subcat = "guides";
$page_title = "Save Guides include";
$header = "noshow";

include("../../includes/header.php");

$saveGuide = new SaveGuide($_POST);
$saveGuide->save();
