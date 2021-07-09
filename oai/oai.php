<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/14/16
 * Time: 11:25 AM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);


use SubjectsPlus\Control\BaseUrl;
use SubjectsPlus\Control\OAI\Repo;
use SubjectsPlus\Control\OAI\Request;

header("Content-type: text/xml");


include_once(__DIR__ . '/../control/includes/autoloader.php');
include_once(__DIR__ . '/../control/includes/config.php');

if(isset($language)) {
    $lang = $language;
} else {
    $lang = "English";
}


$baseUrl = function($BaseUrl) {
    // Function to add http prefix if missing
    if (substr($BaseUrl,0,4) == 4) {
       return $BaseUrl;
    } else {
       return "http:" . $BaseUrl;
    }
};


$setup = array('repositoryName'   => $resource_name,
                'baseUrl'         => $baseUrl($BaseURL) . 'oai/oai.php',
                'adminEmail'      => $administrator_email,
                'publisher'       => $institution_name,
                'identifierUrl'   =>  $baseUrl($BaseURL) . 'subjects/guide.php?id=',
                'language'        => $lang);

$repo = new Repo(new XSLTProcessor(),$setup);

if ($_GET['verb'] == 'Identify') {
    $request = new Request('Identify',$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}

if (empty($_GET['verb'])) {
    $request = new Request('badVerb',$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}

if ($_GET['verb'] == 'ListSets') {
    $request = new Request('ListSets', $_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);
}

if ($_GET['verb'] == 'ListMetadataFormats') {
    $request = new Request('ListMetadataFormats',$_SERVER['QUERY_STRING']);
    echo $repo->processRequest($request);

}

if ($_GET['verb'] == 'GetRecord') {
    $request = new Request('GetRecord',$_SERVER['QUERY_STRING']);
    $request->identifier = $_GET['Identifier'];
    echo $repo->getRecord($request);
}

if ($_GET['verb'] == 'ListRecords') {
    $request = new Request('ListRecords',$_SERVER['QUERY_STRING']);
    echo $repo->listRecords();
}

if ($_GET['verb'] == 'records') {
    echo $repo->allRecordsXml();

}