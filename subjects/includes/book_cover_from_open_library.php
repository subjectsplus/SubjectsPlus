<?php
include("../../control/includes/functions.php");

$book_cover_from_open_library = function ($isbn) {

    $url = "https://openlibrary.org/api/books?bibkeys=ISBN:" . $isbn . "&format=json";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, htmlspecialchars_decode($url));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($raw,true);
    $result = "";

    if (!empty($response)) {
        foreach ($response as $data) {
            if (array_key_exists('thumbnail_url', $data)) {
                $cover_url = str_replace("-S.jpg", "-M.jpg", $data['thumbnail_url']);
                $result = $cover_url;
            }
        }
    }

    if (empty($result)){
        $prefix = explode('subjects', dirname(__FILE__));
        $url = $prefix[0]."assets/images/blank-cover.png";
        $result = $url;
    }

    echo $result;
};

$book_cover_from_open_library(scrubData($_GET['isbn']));