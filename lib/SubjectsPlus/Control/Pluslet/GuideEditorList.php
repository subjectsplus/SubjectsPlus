<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 5/3/16
 * Time: 8:59 AM
 */

namespace SubjectsPlus\Control;
require_once 'Pluslet.php';


class Pluslet_GuideEditorList extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "GuideEditorList";
        $this->_pluslet_id = $pluslet_id;
        $this->_subject_id = $subject_id;
        $this->_isclone = $isclone;


        global $tel_prefix;
        $this->tel_prefix = $tel_prefix;
    }


    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-list-alt\" title=\"" . _("Guide Editor") . "\" ></i><span class=\"icon-text\">" . _("Guide Editor") . "</span>";
        return $icon;
    }

    static function getMenuName()
    {
        return _('Guide Editor');
    }


    protected function onViewOutput() {

    }

    protected function onEditOutput() {

        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT *
                FROM staff s, staff_subject ss
                WHERE s.staff_id = ss.staff_id
                AND ss.subject_id = " . $this->_subject_id . "
                ORDER BY lname, fname";

        $this->_staffArray = $querier->query($qs);

        //var_dump($this->_staffArray);

        $this->_body .= $this->loadHtml(__DIR__ . '/views/GuideEditorListEditOutput.php');
    }
}