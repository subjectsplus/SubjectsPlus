<?php
die("Disabled!");
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Pluslet_TOC;

//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');
include_once('../../../../control/includes/autoloader.php');

$_COOKIE["our_guide"] = scrubData($_COOKIE["our_guide"]);
$_COOKIE["our_guide_id"] = scrubData($_COOKIE["our_guide_id"]);
$_POST["pluslets"] = scrubData($_POST["pluslets"]);

global $AssetPath;

$querier = new Querier();

if( isset($_COOKIE["our_guide"]) && isset($_COOKIE["our_guide_id"]) )
{
	$lobjTocPluslet = new Pluslet_TOC('', '', $_COOKIE["our_guide_id"]);

	if( isset( $_POST['pluslets'] ) )
		$lobjTocPluslet->setTickedItems( explode(',', $_POST['pluslets']) );
}else
{
	die("No Subject Selected!");
}

$lobjTocPluslet->output( 'edit' );
?>
