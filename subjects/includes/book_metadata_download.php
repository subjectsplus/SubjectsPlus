<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$book_metadata_download = function ($title, $isbn, $author, $date) {

    $data = array("isbn" => array('title' => "$title" ,'isbn' => "$isbn",'author' => "$author",'date' => "$date"));

    $prefix = explode('subjects', dirname(__FILE__));

    $newJsonString = json_encode($data);
    file_put_contents($prefix[0] . "assets/cache/" . $isbn . ".bookmetadata", $newJsonString, FILE_APPEND | LOCK_EX);
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

$book_metadata_download(htmlspecialchars($_GET['title']), htmlspecialchars($_GET['isbn']), htmlspecialchars($_GET['author']), htmlspecialchars($_GET['date']));