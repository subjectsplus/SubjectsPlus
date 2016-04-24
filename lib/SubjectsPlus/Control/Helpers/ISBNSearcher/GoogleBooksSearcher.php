<?
namespace SubjectsPlus\Control\Helpers\ISBNSearcher;
use SubjectsPlus\Control\Interfaces as PlusInterface;
class GoogleBooksSearcher implements PlusInterface\ISearcher {

	private static $searcherName = "GoogleBooks";
    private static $api_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:";

    function __construct() {
    }

	public function getBooks($isbns) {
        foreach ($isbns as $key => $value) {
             $page = file_get_contents(self::$api_url.$value);
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
        $page = file_get_contents(self::$api_url.$isbn);
        $elements = json_decode($page);
        return json_encode($elements);
    }
}