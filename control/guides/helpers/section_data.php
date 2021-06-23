<?php

/**
 *   @file section_data.php
 *   @brief Create new section HTML
 *   @description
 *
 *   @author agdarby, dgonzalez
 *   @date updated April 2014
 */

use SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\SectionData;

$subsubcat = "";
$subcat = "guides";
$page_title = "Section Functions";
$header = "noshow";

include("../../includes/header.php");

$sectionData = new SectionData($_POST);
$sectionData->processSection();