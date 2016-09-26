<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$download_cover = function ($url, $isbn) {

    $prefix = explode('subjects', dirname(__FILE__));
    if (empty($url) || strpos($url, 'blank-cover') !== false) {
        $page_url = explode('subjects', curPageURL());
        $url = $page_url[0] . "assets/images/blank-cover.png";
        $cover = file_get_contents($url);
        file_put_contents($prefix[0] . "assets/cache/" . $isbn . ".jpg", $cover, FILE_APPEND | LOCK_EX);

    } else {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, htmlspecialchars_decode($url));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $raw = curl_exec($curl);
        curl_close($curl);
        file_put_contents($prefix[0] . "assets/cache/" . $isbn . ".jpg", $raw, FILE_APPEND | LOCK_EX);
    }

};

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}


$download_cover(htmlspecialchars($_GET['url']), htmlspecialchars($_GET['isbn']));