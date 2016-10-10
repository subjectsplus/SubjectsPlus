<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$book_metadata_read = function ($url) {
    $metadata = file_get_contents($url);
    echo htmlspecialchars_decode($metadata);
};

$book_metadata_read(htmlspecialchars(htmlspecialchars($_GET['url'])));