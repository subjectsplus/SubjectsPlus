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
    $dom = new DOMDocument();
    $dom->load('./xsl/Identify.xsl');
    echo $repo->identify($dom,"?".$_SERVER['QUERY_STRING']);
}

if (empty($_GET['verb'])) {
    $dom = new DOMDocument();
    $dom->load('./xsl/badVerb.xsl');
    echo $repo->badVerb($dom,"?".$_SERVER['QUERY_STRING']);
}

if ($_GET['verb'] == 'ListSets') {
    $dom = new DOMDocument();
    $dom->load('./xsl/ListSets.xsl');
    echo $repo->listSets($dom,"?".$_SERVER['QUERY_STRING']);
}

if ($_GET['verb'] == 'ListMetadataFormats') {
    $dom = new DOMDocument();
    $dom->load('./xsl/ListMetadataFormats.xsl');
    echo $repo->listMetadataFormats("?".$dom,$_SERVER['QUERY_STRING']);
}
