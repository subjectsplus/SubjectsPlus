<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-01-25
 * Time: 14:58
 */


include("../control/includes/autoloader.php");
$page_title = 'Guide Unavailable';

include("includes/header_um-new.php");

$body = 'This guide is currently unavailable. It may be under maintenance, or just resting.<br />';
$body .= '<a href="index.php">Find another guide.</a>';

makePluslet('Guide Not Public', $body, "no_overflow");

include("includes/footer_um-new.php");