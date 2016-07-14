<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/14/16
 * Time: 11:25 AM
 */

use SubjectsPlus\Control\OAI\Repo;

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once ('../control/includes/autoloader.php');
include_once('../control/includes/config.php');

$setup = array('repositoryName' => $resource_name, 'baseUrl' =>$BaseURL.'oai/oai.php', 'adminEmail' => $administrator_email);
$repo = new Repo(new XSLTProcessor(),$setup);

if ($_GET['verb'] == 'Identify') {
    $dom = new DOMDocument();
    $dom->load('./xsl/Identify.xsl');
    echo $repo->identify($dom);
}

if (empty($_GET['verb'])) {
    $dom = new DOMDocument();
    $dom->load('./xsl/badVerb.xsl');
    echo $repo->badVerb($dom);
}

if ($_GET['verb'] == 'ListMetadataFormats') {
    $dom = new DOMDocument();
    $dom->load('./xsl/ListMetadataFormats.xsl');
    echo $repo->listMetadataFormats($dom);
}
