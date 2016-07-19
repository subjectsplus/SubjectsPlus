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
libxml_disable_entity_loader(false);

include_once ('../control/includes/autoloader.php');
include_once('../control/includes/config.php');

if(isset($language)) {
    $lang = $language;
} else {
    $lang = "English";
}
$url = new BaseUrl($BaseURL);

$setup = array('repositoryName'   => $resource_name,
                'baseUrl'         => $url->getUrl().'oai/oai.php',
                'adminEmail'      => $administrator_email,
                'publisher'       => $institution_name,
                'identifierUrl'   =>  $url->getUrl().'subjects/guide.php?id=',
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