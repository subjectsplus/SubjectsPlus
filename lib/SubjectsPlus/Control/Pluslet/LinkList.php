<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/18/16
 * Time: 3:04 PM
 */

namespace SubjectsPlus\Control\Pluslet;
require_once 'Pluslet.php';
use DOMDocument;
use DOMXPath;

class LinkList extends \SubjectsPlus\Control\Pluslet
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
        $icon="<i class=\"fa fa-list-ul\" title=\"" . _("Link List") . "\" ></i><span class=\"icon-text\">" . _("Link List") . "</span>";
        return $icon;
    }

    static function getMenuName()
    {
        return _('Link List');
    }



    protected function onViewOutput() {

    }

    protected function getLinkListId() {
        $link_list_id = "";

        if (isset($this->_body)) {
            $dom = new DOMDocument();
            $dom->loadHTML($this->_body);
            $xpath = new DOMXPath($dom);
            $query = "//div[@data-link-list-id]";
            $entries = $xpath->query($query);
            $link_list_id = $entries[0]->getAttribute("data-link-list-id");
        }
        return $link_list_id;
    }


    protected function getLinkListText() {
        $link_list_text = "";

        if (isset($this->_body)) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($this->_body);

            $xpath = new DOMXPath($dom);
            $query = '//ul[contains(@class, "link-list-display")]';
            $list = $xpath->query($query);

            if( (is_object($list) == true) && ($list->length > 0) ) {

                $newEl = $dom->createElement('div');
                $newNode = $dom->appendChild($newEl);
                $theList = $list->item(0);
                $newNode->appendChild($theList);
                $dom->removeChild($newNode);
                $link_list_text = $dom->saveHTML();
            }

        }

        return $link_list_text;
    }


    protected function onEditOutput() {

        global $CKPath;
        global $CKBasePath;

        include ($CKPath);
        global $BaseURL;


        $oCKeditor = new CKEditor($CKBasePath);
        $oCKeditor->timestamp = time();
        //$oCKeditor->config['ToolbarStartExpanded'] = true;
        $config['toolbar'] = 'TextFormat';
        $config['height'] = '300';
        $config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

        $this_instance = "link-list-textarea";
        $this->_editor = $oCKeditor->editor($this_instance, $this->_body, $config);

        $this->_linkText = $this->getLinkListText();

        $this->_body .= $this->loadHtml(__DIR__ . '/views/LinkListEdit.php');

    }


}