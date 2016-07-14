<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/14/16
 * Time: 11:25 AM
 */

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\OAI\Repo;
use SubjectsPlus\Control\OAI\Record;

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once ('../control/includes/autoloader.php');
include_once('../control/includes/config.php');

$setup = array('repositoryName'   => $resource_name,
                'baseUrl'         => $BaseURL.'oai/oai.php',
                'adminEmail'      => $administrator_email,
                'institutionName' => $institution_name,
                'identifierUrl'   => $BaseURL.'subjects/guide.php?subject_id=');

$repo = new Repo(new XSLTProcessor(),$setup);

if ($_GET['verb'] == 'Identify') {
    $dom = new DOMDocument();
    $dom->load('./xsl/Identify.xsl');
    echo $repo->identify($dom);
}


if ($_GET['verb'] == 'ListMetadataFormats') {
    $dom = new DOMDocument();
    $dom->load('./xsl/ListMetadataFormats.xsl');
    echo $repo->listMetadataFormats($dom);
}


if( (isset($_GET['subject_id'])) && ($_GET['verb'] == 'GetRecord') ) {

    $id = $_GET['subject_id'];

    $db = new Querier();

    $record = new Record($db, $setup);
    $record->getRecord($id);
    $record->toArray();

    var_dump($record);
}

