<?php
require_once("../../../includes/autoloader.php");
use SubjectsPlus\Control\Component\ISBNSearcher as Searcher;

$isbn_to_check = $_POST['isbn_number'];
$isbn_to_check = str_replace("-", "" , $isbn_to_check);

$searcher = Searcher\SearcherFactory::build("GoogleBooks");

echo $searcher->existBook($isbn_to_check);

?>















