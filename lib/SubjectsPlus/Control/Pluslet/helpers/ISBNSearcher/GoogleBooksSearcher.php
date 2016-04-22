<?
namespace SubjectsPlus\Control\Helpers\Searcher;
use namespace SubjectsPlus\Control\Interfaces;
class GoogleBooksSearcher implements ISearcher {

	private static $searcherName = "GoogleBooks";

    public __construct() {
    
    }

	public function getBooks($isbns) {
        foreach ($isbns as $key => $value) {
             $page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".$value);
             $elements = json_decode($page);
             $title = $elements->items[0]->volumeInfo->title;
             $subtitle = isset($elements->items[0]->volumeInfo->subtitle)?$elements->items[0]->volumeInfo->subtitle:"";
             $authors = $elements->items[0]->volumeInfo->authors;
             $thumbnail = $elements->items[0]->volumeInfo->imageLinks->smallThumbnail;
             
             $output_books[] = array("title"=>$title, "subtitle"=>$subtitle, "authors"=>$authors, "image"=>$thumbnail);
            }
        return $output_books;    
	}

    public function existBook($isbn){
        $page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".$isbn);
        $elements = json_decode($page);
        return $elements->totalItems;
    }
}