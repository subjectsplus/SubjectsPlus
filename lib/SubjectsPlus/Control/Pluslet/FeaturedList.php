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


class Pluslet_FeaturedList extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0, $clone_id='') {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "FeaturedList";
        $this->_pluslet_bonus_classes = "type-FeaturedList";
    }

    protected function onEditOutput()
    {
        $this->_body = $this->loadHtml(__DIR__ . '/views/FeaturedListEditOutput.php');
    }

    protected function onViewOutput()
    {
        $this->_body = $this->loadHtml(__DIR__ . '/views/FeaturedListViewOutput.php');
    }

    static function getMenuName()
    {
        return _('Featured List');
    }

    static function getMenuIcon()
    {
        $icon="<span class=\"icon-text guidesearch-text\">" . _("Featured List") . "</span>";
        return $icon;
    }


}