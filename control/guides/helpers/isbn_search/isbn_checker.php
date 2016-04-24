<?php
require_once("../../../includes/autoloader.php");
use SubjectsPlus\Control\Helpers\ISBNSearcher as Searcher;

$isbn_to_check = $_POST['isbn_number'];

$searcher = Searcher\SearcherFactory::build("GoogleBooks");

echo $searcher->existBook($isbn_to_check);

?>















