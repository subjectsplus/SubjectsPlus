<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */
include("../../control/includes/functions.php");

$book_metadata_download = function ($title, $isbn, $author, $date) {

    $prefix = explode('subjects', dirname(__FILE__));
    $file_path = $prefix[0] . "assets/cache/" . $isbn . ".bookmetadata";

    if (!file_exists($file_path)) {
        $data = array("isbn" => array('title' => "$title", 'isbn' => "$isbn", 'author' => "$author", 'date' => "$date"));
        $newJsonString = json_encode($data);
        file_put_contents($file_path, $newJsonString, FILE_APPEND | LOCK_EX);
    }
};

$book_metadata_download(scrubData($_GET['title']), scrubData($_GET['isbn']), scrubData($_GET['author']), scrubData($_GET['date']));