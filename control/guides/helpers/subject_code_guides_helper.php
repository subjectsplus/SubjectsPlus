<?php

header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once(__DIR__ . "/../../includes/autoloader.php");
require_once(__DIR__ . "/../../includes/config.php");
require_once(__DIR__ . "/../../includes/functions.php");

use SubjectsPlus\Control\Guide\SubjectBBCourse;
use SubjectsPlus\Control\Querier;

$db = new Querier;
$objGuides = new SubjectBBCourse($db);

if(isset($_REQUEST['action'])) {
    $action = scrubData($_REQUEST['action'], 'text');

   if( isset($_REQUEST['subject_code_id']) ) {
        $subject_code_id   = scrubData($_REQUEST['subject_code_id']);
    }

    if( isset($_REQUEST['guide_id']) ) {
        $guide_id   = scrubData($_REQUEST['guide_id'], 'integer');
    }

    switch ($action) {
        case "saveChanges":
            $objGuides->saveChanges($subject_code_id, $guide_id);
            break;

        case "fetch_subject_code_guides":
            $objGuides->fetchSubjectCodeGuides($subject_code_id);
            break;
    }
} else {
    $objGuides->response = 'Error, action must be set.';
}

echo $objGuides->toJSON();