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
require_once(__DIR__ . "/../../includes/autoloader.php");
require_once(__DIR__ . "/../../includes/config.php");
require_once(__DIR__ . "/../../includes/functions.php");

use SubjectsPlus\Control\Guide\GuideCollection;
use SubjectsPlus\Control\Querier;

$db = new Querier;
$objCollections = new GuideCollection($db);

if(isset($_REQUEST['action'])) {
    $action = scrubData($_REQUEST['action'], 'text');

    if(isset($_REQUEST['collection_id'])) {
        $collection_id = scrubData($_REQUEST['collection_id'], 'integer');
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
        $guide_id = scrubData($_REQUEST['subject_id'], 'integer');
    }

    switch ($action) {
        case "fetchall":
            $objCollections->fetchCollections();
            break;

        case "fetchone":
            $objCollections->fetchCollectionById($collection_id);
            break;

        case "create":
            $objCollections->createCollection($title, $description, $shortform);
            break;

        case "update":
            $objCollections->updateCollection($collection_id, $title, $description, $shortform);
            break;

        case "delete":
            $objCollections->deleteCollectionGuides($collection_id);
            break;

        case "fetchguides":
            $objCollections->fetchCollectionGuides($collection_id);
            break;
        case "sortguides":

            $i = 0;

            foreach ($_REQUEST['item'] as $value) {
                $objCollections->updateGuideSortOrderInCollection($i, $value);
                $i++;
            }
            break;

        case "addguide":
            $objCollections->addGuideToCollection($collection_id, $guide_id);
            break;

        case "removeguide":
            $objCollections->deleteGuideFromCollection($guide_id, $collection_id);
            break;

        case "validateshortform":
            $objCollections->validateShortform($shortform);
            break;

    }

} else {
    $objCollections->response = 'Error, action must be set.';
}

echo $objCollections->toJSON();