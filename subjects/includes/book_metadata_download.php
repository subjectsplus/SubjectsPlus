<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$book_metadata_download = function ($title, $isbn, $author, $date) {

    $data = array("isbn" => array('title' => "$title" ,'isbn' => "$isbn",'author' => "$author",'date' => "$date"));

    $path = $_SERVER["DOCUMENT_ROOT"]."/sp/assets/cache/".$isbn.".bookmetadata";

    $newJsonString = json_encode($data);
    file_put_contents($path, $newJsonString);
};

$book_metadata_download(htmlspecialchars($_GET['title']), htmlspecialchars($_GET['isbn']), htmlspecialchars($_GET['author']), htmlspecialchars($_GET['date']));