<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

include_once("../../control/includes/config.php");

$validateSyndeticsClientCode = function() {

    global $syndetics_client_code;

    $url = "https://syndetics.com/index.aspx?isbn=9780605039070/xml.xml&client=". $syndetics_client_code . "&type=rn12";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);

    $xml = null;
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($result);
    curl_close($curl);

    if ($xml){
        echo 'true';
    }else{
        echo 'false';
    }
    libxml_use_internal_errors(false);
};

$validateSyndeticsClientCode();