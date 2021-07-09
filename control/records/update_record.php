<?php
include_once(__DIR__ . "/../includes/autoloader.php");
include_once(__DIR__ . "/../includes/config.php");

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

var_dump($title_json);
$title = TitleFactory::create($title_json);

$title_insert_id = $title_db->updateTitle($title);

$locations = $title_json['locations'];

foreach ($locations as $location) {
    $location_instance = LocationFactory::create($location);
    $location_db->updateLocation($location_instance);
}

