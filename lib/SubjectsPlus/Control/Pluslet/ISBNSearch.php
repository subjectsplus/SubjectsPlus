<?php
/**
 * Created by PhpStorm.
 * User: ericbris
 * Date: 4/17/16
 * Time: 11:56 PM
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");
class Pluslet_ISBNSearch extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "ISBNSearch";
    }
    protected function onEditOutput()
    {
        $this->json_extra = json_decode($this->_extra);
        $this->data = $data['totalItems'];
        $output = $this->loadHtml(__DIR__ . '/views/ISBNSearchEdit.html');
      
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
          
          /**
           * @todo Define "GoogleBoks" in a config file.
           * @var ISearcher [get the instance of the new created ISearcher]
           */
            $searcher = $this->getISBNInstance("GoogleBooks");
            //here you receive an instance of ISearcher, that's why you can call "getBooks" method 
            $this->output_books = $searcher->getBooks($json_extra);

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

    /**
     * Creates and instance of ISearcher
     * @param  [type] $searcher [description]
     * @return [type]           [description]
     */
    private function getISBNInstance($searcher){
        return Component\ISBNSearcher\SearcherFactory::build($searcher);
    }

}