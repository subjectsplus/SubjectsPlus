<?php  
/**
 *   @file import_libguides_links.php
 *   @brief This send the libguides importer an id, imports those links into the database, and gives a JSON response indicating sucess or failure, along with more detailed results.
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

$libguides_importer = new LGImport('libguides.xml',$log,$db,$cm);


// Set the guide id 
$libguides_importer->setGuideID($_GET['libguide']);


// Load all the links from the XML
$libguides_links = $libguides_importer->loadLibGuidesLinksXML();

echo $libguides_links;











