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
      //  $this->_pluslet_bonus_classes = "type-googlesearch";
    }
    protected function onEditOutput()
    {
        $output = $this->loadHtml(__DIR__ . '/views/ISBNSearch.html');

        $this->_body = $output;
    }

    protected function onViewOutput()
    {
        $output = $this->loadHtml(__DIR__ . '/views/ISBNSearch.html');

        $this->_body = $output;

    }
    static function getMenuName()
    {
        return _('ISBN Search');
    }

    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-users\" title=\"" . _("ISBN Search") . "\" ></i><span class=\"icon-text\">"  . _("ISBNSearch") . "</span>";
        return $icon;
    }

}