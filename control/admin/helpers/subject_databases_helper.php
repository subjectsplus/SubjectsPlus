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

    if( isset($_REQUEST['subject_database_id']) ) {
        $subject_database_id         = scrubData(htmlspecialchars_decode($_REQUEST['subject_database_id']), 'integer');
    }

    if( isset($_REQUEST['subject_id']) ) {
        $subject_id   = scrubData(htmlspecialchars_decode($_REQUEST['subject_id']), 'integer');
    }

    if( isset($_REQUEST['subject_id']) ) {
        $subject_id   = scrubData(htmlspecialchars_decode($_REQUEST['subject_id']), 'integer');
    }

    if( isset($_REQUEST['title_id']) ) {
        $title_id     = scrubData(htmlspecialchars_decode($_REQUEST['title_id']), 'integer');
    }

    if(isset($_REQUEST['sort'])) {
        $sort = scrubData(htmlspecialchars_decode($_REQUEST['sort']), 'integer');
    }

    if(isset($_REQUEST['description_override'])) {
        $description_override = scrubData(htmlspecialchars_decode($_REQUEST['description_override']));
    }

    switch ($action) {
        case "update":
            $objDatabases->saveChanges($subject_database_id, $subject_id, $title_id, $sort, $description_override);
            break;

        case "delete":
            $objDatabases->deleteDatabaseFromGuide($subject_database_id, $description_override);
            break;

        case "fetchdatabases":
            $objDatabases->fetchSubjectDatabases($subject_id);
            break;
    }
} else {
    $objDatabases->response = 'Error, action must be set.';
}

echo $objDatabases->toJSON();