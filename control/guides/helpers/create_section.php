<?php

/**
 *   @file create_section.php
 *   @brief Create new section HTML
 *   @description
 *
 *   @author agdarby, dgonzalez
 *   @date updated April 2014
 */

use SubjectsPlus\Control\Guide;

$subsubcat = "";
$subcat = "guides";
$page_title = "Create Sections include";
$header = "noshow";

include("../../includes/header.php");

$lobjGuide = new Guide();

print $lobjGuide->dropBoxes(0, 'left', "");
print $lobjGuide->dropBoxes(1, 'center', "");
print $lobjGuide->dropBoxes(2, 'sidebar', "");
print '<div id="clearblock" style="clear:both;"></div> <!-- this just seems to allow the space to grow to fit dropbox areas -->';
?>