<?
namespace SubjectsPlus\Control\Helpers\ISBNSearcher;
//require_once("GoogleBooksSearcher.php");
class SearcherFactory {
	function __construct() {

	}
	static function build($searcher_type)
    {
        $searcher = "SubjectsPlus\\Control\\Helpers\\ISBNSearcher\\".$searcher_type."Searcher";
        //$searcher_class = new $searcher();
        if(class_exists($searcher)) {
          return new $searcher();
    } else {
      throw new \Exception("Invalid search book engine given.");
    }
  }
}