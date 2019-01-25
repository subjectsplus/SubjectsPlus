<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-01-25
 * Time: 14:58
 */



include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

var_dump($subjects_theme);

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if (isset($subjects_theme)  && $subjects_theme != "") { include("themes/$subjects_theme/guide_unavailable.php"); exit;}