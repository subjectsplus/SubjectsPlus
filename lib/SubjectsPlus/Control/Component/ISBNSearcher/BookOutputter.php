<?
namespace SubjectsPlus\Control\Component\ISBNSearcher;
/**
 * @todo Look at how can this class be substituted by implementing OutputInterface.
 */
class BookOutputter{
	  protected $book;

	  function __construct(Book $book) {
	  	$this->book = $book;
	  }

	 public function JSON() {

	  }

	 public function HTML() {
        $output = '<div class="book_isbn_container">
                       <label>'. $this->book->title .'</label><br>
                       <text>'. $this->book->subtitle.'</text><br>
                       <label>'. implode(" - ", $this->book->authors).'</label><br>
                       <img src='. $this->book->cover_image.'></img>
                  </div>';
        return $output;          
	  }
}