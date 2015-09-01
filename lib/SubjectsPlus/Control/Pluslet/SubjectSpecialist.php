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
        $qs = "SELECT lname, fname, email, tel, title
                FROM staff s, staff_subject ss
                WHERE s.staff_id = ss.staff_id
                AND ss.subject_id = " . $this->_subject_id . "
                ORDER BY lname, fname";

        $this->staffArray = $querier->query($qs);

        foreach ($this->staffArray as $value) {

            // get username from email
            $truncated_email = explode("@", $value[2]);


            if(!empty($this->_extra['showPhoto'][0])) {

                $this->staffPhoto = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

            } else {
                $this->staffPhoto = "/assets/images/headshot.jpg";
            }

            $this->staffName  = $value['fname']." " .$value['lname'];

            if(!empty($this->_extra['showTitle'][0])) {
                $this->staffTitle = $value['title'];
            } else {
                $this->staffTitle = "off";
            }

            if(!empty($this->_extra['showPhone'][0])) {
                $this->staffPhone = $this->tel_prefix.'-'.$value['tel'];
            } else {
                $this->staffPhone = "off";
            }

            if(!empty($this->_extra['showEmail'][0])) {
                $this->staffEmail = $value['email'];
            } else {
                $this->staffEmail = "off";
            }

            if( $this->_extra['facebook'] != "" )
            {
                $this->staffFacebook = $this->_extra['facebook'];
            } else {
                $this->staffFacebook = "";
            }

            if( $this->_extra['twitter'] != "" )
            {
                $this->staffTwitter = $this->_extra['twitter'];
            } else {
                $this->staffTwitter = "";
            }

            if( $this->_extra['pinterest'] != "" )
            {
                $this->staffPinterest = $this->_extra['pinterest'];
            } else {
                $this->staffPinterest = "";
            }

            // Output Picture and Contact Info
            // Create and output object
            $this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialistViewOutput.php' );

        }

    }


    protected function onEditOutput() {
        // make an editable body and title type

        if($this->_extra == "")
        {
            $this->_extra = array();
            $this->_extra['facebook']  = "";
            $this->_extra['twitter']   = "";
            $this->_extra['pinterest'] = "";
        } else {
            $this->_extra = json_decode( $this->_extra, true );
        }

        // Create and output object
        $view = $this->loadHtml(__DIR__ . '/views/SubjectSpecialistEditOutput.php' );
        $this->_body = $view;
    }


    static function getMenuIcon() {
        $icon="<i class=\"fa fa-user\" title=\"" . _("Subject Specialist") . "\" ></i><span class=\"icon-text\">" . _("Subject Specialist") ."</span>";
        return $icon;
    }


    static function getMenuName() {
        return _('Subject Specialist');
    }


}