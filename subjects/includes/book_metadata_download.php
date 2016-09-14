<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 9/2/2016
 * Time: 11:33 AM
 */

$book_metadata_download = function ($metadata, $isbn) {
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/sp/assets/cache/".$isbn.".bookmetadata", $metadata);
};

$book_metadata_download(htmlspecialchars($_GET['metadata']), htmlspecialchars($_GET['isbn']));