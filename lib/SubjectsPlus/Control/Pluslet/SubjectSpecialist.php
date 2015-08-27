<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/25/15
 * Time: 12:55 PM
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");


class Pluslet_SubjectSpecialist extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "SubjectSpecialist";

        global $tel_prefix;
        $this->tel_prefix = $tel_prefix;
    }



    protected function onViewOutput() {

        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);
        }

        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT lname, fname, email, tel, title from staff s, staff_subject ss WHERE s.staff_id = ss.staff_id and ss.subject_id = " . $this->_subject_id . " ORDER BY lname, fname";


        $this->staffArray = $querier->query($qs);

        // Create and output object
        $this->_body = $this->loadHtml(__DIR__ . '/views/SubjectSpecialistViewOutput.php' );



    }


    protected function onEditOutput()
    {
        // make an editable body and title type
        if($this->_extra == "")
        {
            $this->_extra = array();
            $this->_extra['facebook'] = "";
            $this->_extra['twitter'] = "";
            $this->_extra['pinterest'] = "";
        }else
        {
            $this->_extra = json_decode( $this->_extra, true );
        }

        // Create and output object
        $view = $this->loadHtml(__DIR__ . '/views/SubjectSpecialistEditOutput.php' );
        $this->_body = $view;
    }



    static function getMenuIcon() {
        $icon="<i class=\"fa fa-user\" title=\"" . _("SubjectSpecialist") . "\" ></i><span class=\"icon-text\">" . _("SubjectSpecialist") ."</span>";
        return $icon;
    }


    static function getMenuName()
    {
        return _('SubjectSpecialist');
    }


}