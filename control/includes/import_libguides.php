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


// Import the guides with the XML you just loaded
echo $libguides_importer->importLibGuides();














