<?php

$book_metadata_from_open_library = function ($isbn) {

    $url = "http://openlibrary.org/api/volumes/brief/isbn/" . $isbn . ".json";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, htmlspecialchars_decode($url));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($raw,true);
    $result = array("isbn" => array());

    if (!empty($response)) {
        foreach ($response as $data) {
            foreach ($data as $info) {
                $title = "";
                $author = array();
                $date = "";

                if (array_key_exists('title', $info['data'])){
                    $title = $info['data']['title'];
                }

                if (array_key_exists('authors', $info['data'])){
                    $author_list = $info['data']['authors'];
                    foreach ($author_list as $author_info){
                        array_push($author, $author_info['name']);
                    }
                }

                if (array_key_exists('publish_date', $info['data'])){
                    $date = $info['data']['publish_date'];
                }


                $result = array("isbn" => array('title' => "$title" ,'isbn' => "$isbn",'author' => array($author),'date' => "$date"));
            }
            break;
        }
    }
    echo json_encode($result);
};

$book_metadata_from_open_library(htmlspecialchars($_GET['isbn']));