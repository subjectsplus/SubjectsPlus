<?
namespace SubjectsPlus\Control\Component\ISBNSearcher;
use SubjectsPlus\Control\Interfaces as PlusInterface;
class GoogleBooksSearcher implements PlusInterface\ISearcher {

	private static $searcherName = "GoogleBooks";
    private static $api_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:";

    function __construct() {
    }

	public function getBooks($isbns) {
        foreach ($isbns as $key => $value) {
             if($value != "" && $value != "/") {
                 $value = str_replace("-", "", $value); 
                 $page = file_get_contents(self::$api_url.$value);
                 $elements = json_decode($page);
                 $title = $elements->items[0]->volumeInfo->title;
                 $subtitle = isset($elements->items[0]->volumeInfo->subtitle)?$elements->items[0]->volumeInfo->subtitle:"";
                 $authors = $elements->items[0]->volumeInfo->authors;
                 $thumbnail = $elements->items[0]->volumeInfo->imageLinks->smallThumbnail;
                 
                 $output_book = new Book($title, $subtitle, $authors, $thumbnail);
    
                 $book_outputter = new BookOutputter($output_book);
    
                 $output_books[] = $book_outputter->HTML();
             }
        }
            //die(var_dump($output_books));
        return $output_books;    
	}

    public function existBook($isbn){
        $page = file_get_contents(self::$api_url.$isbn);
        $elements = json_decode($page);
        return json_encode($elements);
    }
}