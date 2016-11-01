<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 11/1/2016
 * Time: 9:14 AM
 */
include("../../control/includes/functions.php");
include("../../control/includes/config.php");

$isbn_in_primo = function ($isbn) {

    global $booklist_primo_institution_code;
    global $booklist_primo_api_key;
    global $booklist_primo_domain;
    global $booklist_primo_vid;
    $result_url = '';

    $url = "https://api-na.hosted.exlibrisgroup.com/primo/v1/pnxs?q=any,contains," . $isbn . '&lang=eng&view=brief&vid=' . $booklist_primo_institution_code . '&scope=default_scope&apikey=' . $booklist_primo_api_key;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($raw,true);

    if (isset($response['info'])) {
        $total = $response['info']['total'];
        if ($total != 0) {
            $pnxId = $response['docs'][0]['pnxId'];
            $result_url = 'https://' . $booklist_primo_domain . '/primo_library/libweb/action/display.do?doc=' . $pnxId . '&displayMode=full&vid=' . $booklist_primo_vid . '&institution=' . $booklist_primo_institution_code;
        }

    }

    echo $result_url;
};

$isbn_in_primo(scrubData($_GET['isbn']));