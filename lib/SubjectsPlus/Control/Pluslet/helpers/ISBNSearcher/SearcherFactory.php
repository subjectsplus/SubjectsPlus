<?
namespace SubjectsPlus\Control\Helpers;

class SearcherFactory {
	public static function build($searcher_type)
    {
        $searcher = ucwords($searcher_type)."Searcher";
        if(class_exists($searcher)) {
          return new $searcher();
    } else {
      throw new Exception("Invalid search book engine given.");
    }
  }
}