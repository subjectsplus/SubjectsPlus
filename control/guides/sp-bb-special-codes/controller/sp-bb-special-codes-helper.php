<?php
header('Content-Type: application/json');
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once( "../../../includes/autoloader.php" );
require_once("../../../includes/config.php");
require_once("../../../includes/functions.php");
require_once(__DIR__ . '/Integration.php');

use SubjectsPlus\Control\Querier;

if (!isset($_REQUEST['spBBCodesAction']) && !isset($_REQUEST['data'])) {
    die('Wrong request!');
}
$action = scrubData($_REQUEST['spBBCodesAction']);
$data = $_REQUEST['data'];

$db = new Querier;
$integration = new Integration($db);

switch ($action) {
    case 'insert-special-course-code':
        $integration->insertCustomCourseCode($data);
        echo $integration->lastExecutionResultToJson();
        break;
    case 'edit-special-course-code':
        $integration->editCustomCourseCode($data);
        echo $integration->lastExecutionResultToJson();
        break;
    case 'remove-special-course-code':
        $integration->removeCustomCourseCode($data);
        echo $integration->lastExecutionResultToJson();
        break;
    default:
        die('Wrong request!');
}
