<?php  
header("Content-Type: application/json");
error_reporting(1);
ini_set('display_errors', 1);
include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;


$libguides_importer = new LibGuidesImport;


// Set the guide id 
$libguides_importer->setGuideID($_GET['libguide']);


// Load all the links from the XML
//$libguides_xml = $libguides_importer->load_libguides_links_xml('libguides.xml');

// Load the XML
$libguides_xml = $libguides_importer->load_libguides_xml('libguides.xml');


// Import the guides with the XML you just loaded
echo $libguides_importer->import_libguides($libguides_xml);















