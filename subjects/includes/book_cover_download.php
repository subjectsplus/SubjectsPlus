<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$download_cover = function ($url, $isbn) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw=curl_exec($curl);
    curl_close($curl);
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/sp/assets/cache/".$isbn.".jpg", $raw);
};

$download_cover(htmlspecialchars($_GET['url']), htmlspecialchars($_GET['isbn']));