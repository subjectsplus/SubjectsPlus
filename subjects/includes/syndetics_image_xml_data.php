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

    $result = array();
    array_push($result, $isbn);

    if (isset($xml)){
        if (isset($xml->LC)){
            array_push($result, (string)$xml->LC);
            echo json_encode($result);
        }elseif (isset($xml->MC)){
            array_push($result, (string)$xml->MC);
            echo json_encode($result);
        }elseif (isset($xml->SC)){
            array_push($result, (string)$xml->SC);
            echo json_encode($result);
        }else{
            array_push($result, $false);
            echo json_encode($result);
        }
    }else{
        array_push($result, $false);
        echo json_encode($result);
    }

    libxml_use_internal_errors(false);
};

$validateImageExists(htmlspecialchars($_GET['isbn']), htmlspecialchars($_GET['syndeticsClientCode']));