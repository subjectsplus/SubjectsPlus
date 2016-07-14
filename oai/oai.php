<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/14/16
 * Time: 11:25 AM
 */

use SubjectsPlus\Control\OAI\Repo;

header("Content-type: text/xml");

include_once ('../control/includes/autoloader.php');
include_once('../control/includes/config.php');

$setup = array('repositoryName' => $resource_name, 'baseUrl' =>$BaseURL.'oai/oai.php', 'adminEmail' => $administrator_email);
$repo = new Repo(new XSLTProcessor(),$setup);

if ($_GET['verb'] == 'Identify') {
    $request = array('verb'=>'Identify', 'queryString'=>'?'.$_SERVER['QUERY_STRING']);
    echo $repo->processVerb($request);
}

if (empty($_GET['verb'])) {
    $request = array('verb'=>'badVerb', 'queryString'=>'?'.$_SERVER['QUERY_STRING']);
    echo $repo->processVerb($request);
}

if ($_GET['verb'] == 'ListSets') {
    $request = array('verb'=>'ListSets', 'queryString'=>'?'.$_SERVER['QUERY_STRING']);
    echo $repo->processVerb($request);
}

if ($_GET['verb'] == 'ListMetadataFormats') {
    $request = array('verb'=>'ListMetadataFormats', 'queryString'=>'?'.$_SERVER['QUERY_STRING']);
    echo $repo->processVerb($request);
}
