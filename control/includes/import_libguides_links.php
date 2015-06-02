<?php  
header("Content-Type: application/json");

include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;


$libguides_importer = new LibGuidesImport;


// Set the guide id 
$libguides_importer->setGuideID($_GET['libguide']);


// Load all the links from the XML
$libguides_links = $libguides_importer->load_libguides_links_xml('libguides.xml');

echo $libguides_links;











