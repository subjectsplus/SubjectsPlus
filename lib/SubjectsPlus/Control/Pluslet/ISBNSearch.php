<?php
/**
 * Created by PhpStorm.
 * User: ericbris
 * Date: 4/17/16
 * Time: 11:56 PM
 */

namespace SubjectsPlus\Control;
use SubjectsPlus\Control\Helpers\Searcher;
require_once("Pluslet.php");

class Pluslet_ISBNSearch extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "ISBNSearch";
      //  $this->_pluslet_bonus_classes = "type-googlesearch";
    }
    protected function onEditOutput()
    {
        $this->json_extra = json_decode($this->_extra);
        $this->data = $data['totalItems'];
        $output = $this->loadHtml(__DIR__ . '/views/ISBNSearch.html');
      
        $this->_body = $output;
    }

    protected function onViewOutput()
    {
        /**
         * @todo by Eric [Create an interface called ISearcher to encapsulate the logic to get the book data
         * from an specific API, in this case google api, but in other case another different API]
         * Validate that the used data exist and if not, jus define a default data.
         * Get a way to get tha data as a batch from the Google or another API.
         * Get the API we want to use from Dependency Injection.
         */
        $this->output_books = array();
        if(isset($this->_extra) && $this->_extra !== "") {
            $json_extra = json_decode($this->_extra);
          
           // $searcher = new SearcherFactory();

            foreach ($json_extra as $key => $value) {
             $page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".$value);
             $elements = json_decode($page);
             $title = $elements->items[0]->volumeInfo->title;
             $subtitle = isset($elements->items[0]->volumeInfo->subtitle)?$elements->items[0]->volumeInfo->subtitle:"";
             $authors = $elements->items[0]->volumeInfo->authors;
             $thumbnail = $elements->items[0]->volumeInfo->imageLinks->smallThumbnail;
             
             $this->output_books[] = array("title"=>$title, "subtitle"=>$subtitle, "authors"=>$authors, "image"=>$thumbnail);
            }
            


             $output = $this->loadHtml(__DIR__ . '/views/ISBNSearchView.html');

            $this->_body = $output;
        }

    }

    static function getMenuName()
    {
        return _('ISBN Search');
    }

    /**
     * @todo by Eric [Define this method only in Pluslet parent class and pass the class to use and the icon name from children]
     */
    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-users\" title=\"" . _("ISBN Search") . "\" ></i><span class=\"icon-text\">"  . _("ISBNSearch") . "</span>";
        return $icon;
    }
    private function checkISBN(){
        return json_encode(array("succes"=>true));
    }

}