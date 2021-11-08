<?php  
/**
 *   @file import_libguides.php
 *   @brief This sends a guide id and a staff id to the importer, imports the guide with that id and returns a JSON reponse indicating sucess or failure along with other information about the import status.
 *   @author little9 (Jamie Little)
 *   @date June 2014
 */
header("Content-Type: application/json; charset=UTF-8");



include_once(__DIR__ . "/../../includes/autoloader.php");
include_once(__DIR__ . "/../../includes/config.php");
include_once(__DIR__ . "/../../includes/functions.php");


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LGImport;
use SubjectsPlus\Control\Logger;
use RichterLibrary\Helpers\CatalogMigrator;

$db = new Querier;
$log = new Logger;
$cm = new CatalogMigrator;

$lg_importer = new LGImport('libguides.xml',$log,$db,$cm);

// Set the guide id
$lg_importer->setGuideID($_GET['libguide']);
$lg_importer->setStaffID($_GET['staff_id']);

// Import the guides with the XML you just loaded
echo $lg_importer->importLibGuides();





