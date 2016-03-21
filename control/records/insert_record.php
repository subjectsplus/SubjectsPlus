<?php
include("../includes/autoloader.php");
include("../includes/config.php");
include("../includes/functions.php");
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\AzRecord\TitleDb;
use SubjectsPlus\Control\AzRecord\TitleFactory;
use SubjectsPlus\Control\AzRecord\LocationDb;
use SubjectsPlus\Control\AzRecord\LocationTitleDb;
use SubjectsPlus\Control\AzRecord\LocationFactory;

$db = new Querier();

$title_db = new TitleDb($db);
$location_db = new LocationDb($db);

$title_json = json_decode(file_get_contents('php://input'), true);

$title = TitleFactory::create($title_json);

$title_insert_id = $title_db->insertTitle($title);

if (isset($title_insert_id)) {
   echo json_encode(array("response"=> getControlURL() . '/records/record.php?record_id=' . $title_insert_id));
} else {
   echo json_encode(array("response" => "Error inserting the record"));
}


$locations = $title_json['locations'];

foreach ($locations as $location) {
    $location_instance = LocationFactory::create($location);
    $location_insert_id =  $location_db->insertLocation($location_instance);
    $location_title_db = new LocationTitleDb($location_insert_id, $title_insert_id, $db);
    $location_title_db->insertLocationTitle();
}
