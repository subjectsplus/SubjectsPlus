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
        // Look for tokens, tokenize
        //parent::tokenizeText();

        $db = new Querier;
        $connection = $db->getConnection();
        $statement = $connection->prepare("SELECT * FROM pluslet WHERE pluslet_id = :pluslet_id");
        $statement->bindParam(":pluslet_id", $this->_pluslet_id);
        $statement->execute();
        $result = $statement->fetchAll();

        if(!empty($result)) {
            $this->_body = $result[0]['body'];
            parent::tokenizeText();

            $this->_list = $this->_body;

            $this->_body = "<div id='LinkList-body'>";
            $this->_body .= $this->_list;
            $this->_body .= "</div>";
            //var_dump($this->_tokens);
        }



    }


    protected function onEditOutput() {

        $db = new Querier;
        $connection = $db->getConnection();
        $statement = $connection->prepare("SELECT * FROM pluslet WHERE pluslet_id = :pluslet_id");
        $statement->bindParam(":pluslet_id", $this->_pluslet_id);
        $statement->execute();
        $result = $statement->fetchAll();

        //var_dump($result[0]['body']);

        if(!empty($result)) {

            $this->_linkList = $result[0]['body'];

            $this->_body = "<div id='LinkList-body'>";
            $this->_body .= $this->_linkList;
            $this->_body .= "</div>";
        }

        $this->_body .= "<p class=\"faq-alert\">" . _("Click 'Edit' to edit your Link List box.") . "</p>";

        $this->_body .= "<a class='cboxElement linklist_edit_colorbox_btn' id='linklist_edit_colorbox_btn' href='#linklist_edit_colorbox_".$this->_pluslet_id."'>Edit</a>";

        $this->_body .= $this->loadHtml(__DIR__ . '/views/LinkListEdit.php');
    }



}