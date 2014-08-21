<?php  
header("Content-Type: text/plain");
error_reporting(1);
ini_set('display_errors', 1);
include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;

/*
$libguides_importer = new LibGuidesImport;

$libguides_importer->setGuideID($_POST['libguide']);


$libguides_xml = $libguides_importer->load_libguides_xml('libguides.xml');
$libguides_importer->import_libguides($libguides_xml);

$libguides_xml = $libguides_importer->load_libguides_links_xml('libguides.xml');

*/


$libguides_importer = new LibGuidesImport;

$libguides_importer->setGuideID($_POST['libguide']);



//echo "Guide Imported: " . $libguides_importer->guide_imported()[0][0]; 



$libguides_xml = $libguides_importer->load_libguides_xml('libguides.xml');
$libguides_importer->import_libguides($libguides_xml);

$libguides_xml = $libguides_importer->load_libguides_links_xml('libguides.xml');

