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


        if( isset($_REQUEST['our_guide_id']) ) {

            $subject_id = $_REQUEST['our_guide_id'];

        } elseif( isset($_REQUEST['this_subject_id']) ) {

            $subject_id = $_REQUEST['this_subject_id'];

        } else {

            $subject_id = $_GET['subject_id'];
        }

        $this->_subject_id = $subject_id;

        $this->_array_keys = array('Photo', 'Title', 'Email', 'Phone', 'Facebook', 'Twitter', 'Pinterest', 'Instagram');

        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT *
                FROM staff s, staff_subject ss
                WHERE s.staff_id = ss.staff_id
                AND ss.subject_id = " . $this->_subject_id . "
                ORDER BY lname, fname";

        $this->_staffArray = $querier->query($qs);
    }

    protected function onViewOutput() {

        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);


            foreach($this->_staffArray as $staffMember):

                $this->staffName = $staffMember['fname']." ".$staffMember['lname'];
                $staffId = $staffMember['staff_id'];
                $this->staffId = $staffId;

                $staffData = $this->getStaffMember($staffId);
                //var_dump($staffData);

                $truncated_email = explode("@", $staffData[0]['email']);

                $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

                if ($staffData[0]['extra'] != "") {
                    $data = html_entity_decode($staffData[0]['extra']);
                    $staffSocialMedia = json_decode($data, true);
                }

                $this->_body .= "<div>";

                $this->_body .= "<h4>{$staffData[0]['fname']} {$staffData[0]['lname']}</h4>";

                $this->_body .= "<ul class='staff-details'>";
                foreach($this->_array_keys as $item):

                    if(array_key_exists("show{$item}{$staffId}", $this->_extra)) {

                        $key = 'show'.$item.$staffId;
                        $value = $this->_extra[$key];

                        if( !empty($value[0]) ) {

                            $key_trimmed = rtrim($key, ' 0123456789');

                            if($key_trimmed == 'showPhoto' && $value[0] == "Yes") {
                                $this->_body .= "<div style='float:left;'><img id='staffPhoto' class=\"staff-photo\" src='{$staff_picture}' /></div>";
                            }

                            if($key_trimmed == 'showTitle' && $value[0] == "Yes") {
                                $this->_body .= "<li>".$staffData[0]['title']."</li>";
                            }

                            if($key_trimmed == 'showEmail' && $value[0] == "Yes") {
                                $this->_body .= "<li><a href='mailto:".$staffData[0]['email']."'>".$staffData[0]['email']."</a></li>";
                            }

                            if($key_trimmed == 'showPhone' && $value[0] == "Yes") {
                                $this->_body .= "<li>".$this->tel_prefix.' - '.$staffData[0]['tel']."</li>";
                            }

                            $this->_body .= "<li style='float:left; padding-right:8px;'>";

                            if($key_trimmed == 'showFacebook' && $value[0] == "Yes") {
                                $this->_body .= "<span><a href='http://facebook.com/{$staffSocialMedia['facebook']}'><i class='fa fa-facebook-square fa-2x'></i></a></span>";
                            }

                            if($key_trimmed == 'showTwitter' && $value[0] == "Yes") {
                                $this->_body .= "<span><a href='http://twitter.com/{$staffSocialMedia['twitter']}'><i class='fa fa-twitter-square fa-2x'></i></a></span>";
                            }

                            if($key_trimmed == 'showPinterest' && $value[0] == "Yes") {
                                $this->_body .= "<span><a href='http://pinterest.com/{$staffSocialMedia['pinterest']}'><i class='fa fa-pinterest-square fa-2x'></i></a></span>";
                            }

                            if($key_trimmed == 'showInstagram' && $value[0] == "Yes") {
                                $this->_body .= "<span><a href='http://instagram.com/{$staffSocialMedia['instagram']}'><i class='fa fa-instagram fa-2x'></i></a></span>";
                            }
                            $this->_body .= "</li>";

                        }
                    }

                endforeach;
                $this->_body .= "</ul>";
                $this->_body .= '</div>';

                $this->_body .= "<span class='clear' style='clear:both; padding:8px;'><hr style='clear:both;'></span>";
            endforeach;

        }
    }


    protected function onEditOutput() {
        // make an editable body and title type
        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);
        }

        $this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialistEditOutput.php' );
    }


    static function getMenuIcon() {
        $icon="<i class=\"fa fa-users\" title=\"" . _("Subject Specialists") . "\" ></i><span class=\"icon-text\">" . _("Subject Specialists") ."</span>";
        return $icon;
    }


    static function getMenuName() {
        return _('Subject Specialist');
    }

    protected function getStaffMember($staffId) {


        $querier = new Querier();
        $qs = "SELECT lname, fname, email, tel, title, extra
                FROM staff
                WHERE staff_id = {$staffId}";

        $staffMember = $querier->query($qs);

        return $staffMember;


    }


}