<?php

/**
 *   @file save_guide.php
 *   @brief Save the layout of the guide columns.
 *   @description Basically, just updates the extra column for the guide
 *
 *   @author agdarby, dgonzalez
 *   @date updated dec 2012
 */

use SubjectsPlus\Control\Guide;

$subsubcat = "";
$subcat = "guides";
$page_title = "Save Layout include";
$header = "noshow";

include("../../includes/header.php");

$oSaveLayout = new Guide($_POST["subject_id"]);


?>