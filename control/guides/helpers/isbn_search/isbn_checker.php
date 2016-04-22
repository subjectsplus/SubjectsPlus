<?php
$isbn_to_check = $_POST['isbn_number'];
//@TODO call here the ISearcher used class to get the data from the existing API, 
// don't call it directly from here.
$page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".$isbn_to_check);

echo $page;

?>















