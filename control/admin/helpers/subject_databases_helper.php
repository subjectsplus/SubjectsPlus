<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 6/29/16
 * Time: 9:52 AM
 */

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

    if(isset($_REQUEST['collection_id'])) {
        $subject_id = scrubData($_REQUEST['subject_id'], 'integer');
    }

    if( isset($_REQUEST['title']) ) {
        $title         = scrubData($_REQUEST['title']);
    }

    if( isset($_REQUEST['description']) ) {
        $description   = scrubData($_REQUEST['description']);
    }

    if( isset($_REQUEST['shortform']) ) {
        $shortform     = scrubData($_REQUEST['shortform']);
    }

    if(isset($_REQUEST['subject_id'])) {
        $subject_id = scrubData($_REQUEST['subject_id'], 'integer');
    }

    switch ($action) {
        case "fetchall":
            $objDatabases->fetchCollections();
            break;

        case "fetchone":
            $objDatabases->fetchCollectionById($subject_id);
            break;

        case "create":
            $objDatabases->createCollection($title, $description, $shortform);
            break;

        case "update":
            $objDatabases->updateCollection($subject_id, $title, $description, $shortform);
            break;

        case "delete":
            $objDatabases->deleteCollectionGuides($subject_id);
            break;

        case "fetchdatabases":
            $objDatabases->fetchSubjectDatabases($subject_id);
            break;
        case "sortdatabases":

            $i = 0;

            foreach ($_REQUEST['item'] as $value) {
                $objDatabases->updateGuideSortOrderInCollection($i, $value);
                $i++;
            }
            break;

        case "addguide":
            $objDatabases->addGuideToCollection($subject_id, $guide_id);
            break;

        case "removeguide":
            $objDatabases->deleteGuideFromCollection($guide_id, $subject_id);
            break;

        case "validateshortform":
            $objDatabases->validateShortform($shortform);
            break;

    }

} else {
    $objDatabases->response = 'Error, action must be set.';
}

echo $objDatabases->toJSON();