<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$book_cover_cache_check = function ($isbn) {
    $file = $_SERVER["DOCUMENT_ROOT"]."/sp/assets/cache/".$isbn.".jpg";

    if (file_exists($file)) {
        echo $file;
    } else {
        echo 'false';
    }
};

$book_cover_cache_check(htmlspecialchars($_GET['isbn']));