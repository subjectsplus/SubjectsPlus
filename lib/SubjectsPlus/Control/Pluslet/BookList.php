<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:19 PM
 */
/**
 * Class BookList
 */

namespace SubjectsPlus\Control\Pluslet;
require_once("Pluslet.php");


class BookList extends \SubjectsPlus\Control\Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0, $clone_id='') {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "BookList";
        $this->_pluslet_bonus_classes = "type-BookList";

    }

    protected function onEditOutput()
    {
        if($this->_extra == "")
        {
            $this->_extra = array();

        }else
        {
            $this->_extra = json_decode( $this->_extra, true );
        }
        
        $this->_body = $this->loadHtml(__DIR__ . '/views/BookListEditOutput.php');
    }

    protected function onViewOutput()
    {
        $this->_extra = json_decode( $this->_extra, true );
        $this->_body = $this->loadHtml(__DIR__ . '/views/BookListViewOutput.php');

    }

    static function getMenuName()
    {
        return _('Book List');
    }

    static function getMenuIcon()
    {
        $icon="<span class=\"icon-text guidesearch-text\">" . _("Book List") . "</span>";
        return $icon;
    }


}