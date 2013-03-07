<?php
/**
 *   @file api/index.php
 *   @brief Return data from subjectsplus
 *	 @package SubjectsPlus API
 *
 *   @author dgonzalez
 *   @date November 2012
 */

include_once("../control/includes/config.php");
include_once("../control/includes/functions.php");

$lobjWebServiceHandler = new sp_WebServiceHandler($uname, $pword, $dbName_SPlus, $hname);

$lobjWebServiceHandler->doService();

$lobjWebServiceHandler->displayOutput();
?>