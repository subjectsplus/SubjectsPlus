<?php
/**
 *   @file api/index.php
 *   @brief Return data from subjectsplus
 *	 @package SubjectsPlus API
 *
 *   @author dgonzalez
 *   @date November 2012
 */

include_once("../control/includes/autoloader.php");
include_once("../control/includes/config.php");
include_once("../control/includes/functions.php");

use SubjectsPlus\API\WebServiceHandler;
//if the not properly configured, redirect to control folder for installation/update
if( !isset( $BaseURL ) )
{
	header( "Location: ../control/" );
	exit;
}

$lobjWebServiceHandler = new WebServiceHandler($uname, $pword, $dbName_SPlus, $hname);

$lobjWebServiceHandler->doService();

$lobjWebServiceHandler->displayOutput();
?>