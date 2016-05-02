<?php
require_once("../../../includes/autoloader.php");
require_once("../../../includes/config.php");
use SubjectsPlus\Control\Component\ISBNSearcher as Searcher;

global $isbn_engine;
$isbn_engine_checked = $isbn_engine ?: "GoogleBooks";
$isbn_to_check = $_POST['isbn_number'];
$isbn_to_check = str_replace("-", "" , $isbn_to_check);

$searcher = Searcher\SearcherFactory::build($isbn_engine);

echo $searcher->existBook($isbn_to_check);

?>















