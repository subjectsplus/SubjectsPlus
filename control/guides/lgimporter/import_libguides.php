<?php  
/**
 *   @file import_libguides.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date June 2014
 */
header("Content-Type: application/json");


include('../../includes/autoloader.php');
include('../../includes/config.php');
include('../../includes/functions.php');


use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;
use SubjectsPlus\Control\Logger;
$db = new Querier;
$log = new Logger;

$libguides_importer = new LibGuidesImport('libguides.xml',$log,$db);


// Set the guide id 
$libguides_importer->setGuideID($_GET['libguide']);
$libguides_importer->setStaffID($_GET['staff_id']);

// Import the guides with the XML you just loaded
echo $libguides_importer->importLibGuides();














