<?php  
header("Content-Type: application/json");

include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;


$libguides_importer = new LibGuidesImport('libguides.xml');


// Set the guide id 
$libguides_importer->setGuideID($_GET['libguide']);


// Load all the links from the XML
$libguides_links = $libguides_importer->loadLibGuidesLinksXML();

echo $libguides_links;











