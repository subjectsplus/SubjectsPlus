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

namespace SubjectsPlus\Control;
require_once("Pluslet.php");


class Pluslet_BookList extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0, $clone_id='') {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "BookList";
        $this->_pluslet_id = $pluslet_id;
        $this->_subject_id = $subject_id;
        $this->_isclone = $isclone;
        $this->_clone_id = $clone_id;
        $this->_pluslet_bonus_classes = "type-booklist";

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
        $icon="<span class=\"icon-text \">" . _("Book List") . "</span>";
        return $icon;
    }


}