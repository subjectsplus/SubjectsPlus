<?
namespace SubjectsPlus\Control\Component\ISBNSearcher;

class Book{
	public $title;         //string
	public $subtitle;      //string
	public $authors;       //array
	public $cover_image;   //string

	function __construct($title, $subtitle, $authors, $cover_image) {
        $this->title        = $title;
        $this->subtitle     = $subtitle;
        $this->authors      = $authors;
        $this->cover_image = $cover_image;
	}
}