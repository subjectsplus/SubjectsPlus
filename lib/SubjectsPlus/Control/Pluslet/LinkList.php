<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/18/16
 * Time: 3:04 PM
 */

namespace SubjectsPlus\Control;
require_once 'Pluslet.php';

class Pluslet_LinkList extends Pluslet
{


    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "LinkList";
        $this->_pluslet_id = $pluslet_id;
        $this->_subject_id = $subject_id;
        $this->_isclone = $isclone;
    }


    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-list-alt\" title=\"" . _("Link List") . "\" ></i><span class=\"icon-text\">" . _("Link List") . "</span>";
        return $icon;
    }

    static function getMenuName()
    {
        return _('Link List');
    }


    protected function onViewOutput() {


    }


    protected function onEditOutput() {

        
        $this->_body = "<p class=\"faq-alert\">" . _("Click 'Edit' to edit your Link List box.") . "</p>";

        $this->_body .= "<a class='cboxElement linklist_edit_colorbox_btn' href='#linklist_edit_colorbox_".$this->_pluslet_id."'>Edit</a>";

        $this->_body .= $this->loadHtml(__DIR__ . '/views/LinkListEdit.php');


    }



}