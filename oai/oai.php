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
use SubjectsPlus\Control\OAI\Request;


header("Content-type: text/xml");

include_once ('../control/includes/autoloader.php');
include_once('../control/includes/config.php');

$setup = array('repositoryName'   => $resource_name,
                'baseUrl'         => $BaseURL.'oai/oai.php',
                'adminEmail'      => $administrator_email,
                'institutionName' => $institution_name,
                'identifierUrl'   => $BaseURL.'subjects/guide.php?subject_id=');

$repo = new Repo(new XSLTProcessor(),$setup);

if ($_GET['verb'] == 'Identify') {
    $request = new Request('Identify','?'.$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}

if (empty($_GET['verb'])) {
    $request = new Request('badVerb','?'.$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}

if ($_GET['verb'] == 'ListSets') {
    $request = new Request('ListSets','?'.$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}

if ($_GET['verb'] == 'ListMetadataFormats') {
    $request = new Request('ListMetadataFormats','?'.$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}


if( (isset($_GET['subject_id'])) && ($_GET['verb'] == 'GetRecord') ) {

    $id = $_GET['subject_id'];

    $db = new Querier();

    $record = new Record($db, $setup);
    $record->getRecord($id);
    $record->toArray();

    var_dump($record);
}

