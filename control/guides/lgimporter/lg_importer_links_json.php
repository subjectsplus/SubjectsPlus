<?php  
/**
 *   @file import_libguides_links.php
 *   @brief This send the libguides importer an id, imports those links into the database, and gives a JSON response indicating sucess or failure, along with more detailed results.
 *   @author little9 (Jamie Little)
 *   @date June 2014
 */

header("Content-Type: application/json; charset=UTF-8");

include('../../includes/autoloader.php');
include('../../includes/config.php');
include('../../includes/functions.php');


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LGImport;
use SubjectsPlus\Control\Logger;

$db = new Querier;
$log = new Logger;

$libguides_importer = new LGImport('libguides.xml',$log,$db);


// Set the guide id 
$libguides_importer->setGuideID($_GET['libguide']);


// Load all the links from the XML
$libguides_links = $libguides_importer->loadLibGuidesLinksXML();

echo $libguides_links;











