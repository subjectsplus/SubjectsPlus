<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$validateImageExists = function($isbn, $syndeticsClientCode) {

    $xmlUrl = 'https://syndetics.com/index.aspx?isbn=' . $isbn . '/xml.xml&client=' . $syndeticsClientCode . '&type=rn12';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $xmlUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    $xml = null;
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($result);

    curl_close($curl);
    $false = 'false';

    if (isset($xml)){
        if (isset($xml->LC)){
            echo $xml->LC;
        }elseif (isset($xml->MC)){
            echo $xml->MC;
        }elseif (isset($xml->SC)){
            echo $xml->SC;
        }else{
            echo $false;
        }
    }else{
        echo $false;
    }

    libxml_use_internal_errors(false);
};

$validateImageExists(htmlspecialchars($_GET['isbn']), htmlspecialchars($_GET['syndeticsClientCode']));