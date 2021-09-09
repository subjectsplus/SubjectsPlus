<?php
/**
 *   @file index.php
 *   @brief Display the subject guides splash page
 *
 *   @author adarby
 *   @date nov 2015
 */
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

include_once(__DIR__ . "/../control/includes/config.php");
include_once(__DIR__ . "/../control/includes/functions.php");
include_once(__DIR__ . "/../control/includes/autoloader.php");

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if (isset($subjects_theme)  && $subjects_theme != "") { include_once(__DIR__ . "/..themes/$subjects_theme/index.php"); exit;}