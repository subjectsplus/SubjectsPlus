<?php

header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");
require_once("../../includes/functions.php");

use SubjectsPlus\Control\Guide\SubjectDatabase;
use SubjectsPlus\Control\Querier;

$db = new Querier;
$objDatabases = new SubjectDatabase($db);

if(isset($_REQUEST['action'])) {
    $action = scrubData($_REQUEST['action'], 'text');

   if( isset($_REQUEST['subject_id']) ) {
        $subject_id   = scrubData($_REQUEST['subject_id'], 'integer');
    }

    if( isset($_REQUEST['title_id']) ) {
        $title_id     = scrubData($_REQUEST['title_id'], 'integer');
    }

    if(isset($_REQUEST['dbbysub_active'])) {
        $dbbysub_active = scrubData($_REQUEST['dbbysub_active'], 'integer');
    }

    if(isset($_REQUEST['description_override'])) {
        $description_override = scrubData($_REQUEST['description_override']);
    }

    if(isset($_REQUEST['rank_id'])) {
        $rank_id = scrubData($_REQUEST['rank_id']);
    }

    if( isset($_REQUEST['dbbysub_active']) ) {
        $dbbysub_active   = scrubData($_REQUEST['dbbysub_active'], 'integer');
    }

    switch ($action) {
        case "update":
            $objDatabases->saveChanges($title_id, $subject_id, $description_override);
            break;

        case "delete":
            $objDatabases->hideDatabaseFromGuide($rank_id);
            break;

        case "fetchdatabases":
            $objDatabases->fetchSubjectDatabases($subject_id);
            break;

        case "getDescriptionOverride":
            $objDatabases->getDescriptionOverride($subject_id, $title_id);
            break;

        case "saveDescriptionOverride":
            $objDatabases->saveDescriptionOverride($subject_id, $title_id, $description_override);
            break;
    }
} else {
    $objDatabases->response = 'Error, action must be set.';
}

echo $objDatabases->toJSON();