<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/18/16
 * Time: 3:04 PM
 */

namespace SubjectsPlus\Control;
require_once 'Pluslet.php';
use DOMDocument;
use DOMXPath;

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
    protected function onEditOutput() {

        $this->_body .= $this->loadHtml(__DIR__ . '/views/LinkListEdit.php');


    }


}