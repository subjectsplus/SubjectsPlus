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

        $this->_subject_id = $subject_id;

        $this->_pluslet_id = $pluslet_id;

        $this->_array_keys = array('Name', 'Photo', 'Title', 'Email', 'Phone', 'Facebook', 'Twitter', 'Pinterest', 'Instagram');
    }

    protected function onViewOutput()
    {
        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT *
                FROM staff s, staff_subject ss
                WHERE s.staff_id = ss.staff_id
                AND ss.subject_id = " . $this->_subject_id . "
                ORDER BY lname, fname";

        $this->_staffArray = $querier->query($qs);

        $data = json_decode($this->_extra, true);
        //var_dump($data);
        $show_keys = $this->_array_keys;

        $data = json_decode($this->_extra, true);
        $settings = array();

        foreach($this->_staffArray as $staff):


            $showStatusSettings = $this->setShowStatus($staff['staff_id'], $show_keys, $data);
            //var_dump($showStatusSettings);
            $showStatusData = $this->getShowStatus($showStatusSettings);
            //var_dump($showStatusData);
            $social = $this->getStaffSocialMedia($staff['staff_id']);
/*
            $social['facebook'] = "";
            $social['twitter'] = "californiavol";
            $social['pinterest'] = "californiavol";
            $social['instagram'] = "";
            $showStatusData['showName'] = "Yes";
            $showStatusData['showPhoto'] = "Yes";
            $showStatusData['showTitle'] = "Yes";
            $showStatusData['showEmail'] = "Yes";
            $showStatusData['showPhone'] = "Yes";
            $showStatusData['showFacebook'] = "Yes";
            $showStatusData['showTwitter'] = "Yes";
            $showStatusData['showPinterest'] = "Yes";
            $showStatusData['showInstagram'] = "Yes";
*/
            $settings = array(
                'staff_id'      => $staff['staff_id'],
                'fname'         => $staff['fname'],
                'lname'         => $staff['lname'],
                'title'         => $staff['title'],
                'email'         => $staff['email'],
                'tel'           => $staff['tel'],
                'facebook'      => $social['facebook'],
                'twitter'       => $social['twitter'],
                'pinterest'     => $social['pinterest'],
                'instagram'     => $social['instagram'],
                'showName'      => $showStatusData['showName'],
                'showPhoto'     => $showStatusData['showPhoto'],
                'showTitle'     => $showStatusData['showTitle'],
                'showEmail'     => $showStatusData['showEmail'],
                'showPhone'     => $showStatusData['showPhone'],
                'showFacebook'  => isset($showStatusData['showFacebook'])  ? $showStatusData['showFacebook'] : "No",
                'showTwitter'   => isset($showStatusData['showTwitter'])   ? $showStatusData['showTwitter'] : "No",
                'showPinterest' => isset($showStatusData['showPinterest']) ? $showStatusData['showPinterest'] : "No",
                'showInstagram' => isset($showStatusData['showInstagram']) ? $showStatusData['showInstagram'] : "No",
            );

            $this->setSubjectSpecialist($settings);

            $this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialist.php' );

        endforeach;
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


        if($this->_pluslet_id != '') {

            $qry = "SELECT body FROM pluslet WHERE pluslet_id = {$this->_pluslet_id}";
            $qry_result = $querier->query($qry);
            $this->_body_content = $qry_result;

        } else {

            $this->_body_content = array();
        }


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

        $this_instance = "editor1";
        $this->_editor = $oCKeditor->editor($this_instance, $this->_body, $config);


        //this should rarely happen, only on pull from libguides xml does it occur
        if(empty($this->_staffArray)) {

            $this->_body = "There is no staff member associated with this guide. Please select a user for this guide.";

        } else {

            // make an editable body and title type
            if ($this->_extra != "") {
                $this->_extra = json_decode($this->_extra, true);
            }

            $this->_body_content = $this->_body;

            $data = json_decode($this->_extra, true);
            //var_dump($data);
            $show_keys = $this->_array_keys;

            $data = json_decode($this->_extra, true);
            $settings = array();

            foreach($this->_staffArray as $staff):

                /*
                $social['facebook'] = "";
                $social['twitter'] = "californiavol";
                $social['pinterest'] = "californiavol";
                $social['instagram'] = "";
                */
                $showStatusData['showName'] = "Yes";
                $showStatusData['showPhoto'] = "Yes";
                $showStatusData['showTitle'] = "Yes";
                $showStatusData['showEmail'] = "Yes";
                $showStatusData['showPhone'] = "Yes";
                $showStatusData['showFacebook'] = "No";
                $showStatusData['showTwitter'] = "Yes";
                $showStatusData['showPinterest'] = "Yes";
                $showStatusData['showInstagram'] = "No";

                $social = $this->getStaffSocialMedia($staff['staff_id']);

/*
                $showStatusSettings = $this->setShowStatus($staff['staff_id'], $show_keys, $data);
                //var_dump($showStatusSettings);
                $showStatusData = $this->getShowStatus($showStatusSettings);
                //var_dump($showStatusData);
                $social = $this->getStaffSocialMedia($staff['staff_id']);
*/

                $settings = array(
                    'staff_id'      => $staff['staff_id'],
                    'fname'         => $staff['fname'],
                    'lname'         => $staff['lname'],
                    'title'         => $staff['title'],
                    'email'         => $staff['email'],
                    'tel'           => $staff['tel'],
                    'facebook'      => $social['facebook'],
                    'twitter'       => $social['twitter'],
                    'pinterest'     => $social['pinterest'],
                    'instagram'     => $social['instagram'],
                    'showName'      => $showStatusData['showName'],
                    'showPhoto'     => $showStatusData['showPhoto'],
                    'showTitle'     => $showStatusData['showTitle'],
                    'showEmail'     => $showStatusData['showEmail'],
                    'showPhone'     => $showStatusData['showPhone'],
                    'showFacebook'  => $showStatusData['showFacebook'],
                    'showTwitter'   => $showStatusData['showTwitter'],
                    'showPinterest' => $showStatusData['showPinterest'],
                    'showInstagram' => $showStatusData['showInstagram'],
                );

                $this->setSubjectSpecialist($settings);

                $this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialistEdit.php' );
            endforeach;

        }

    }


    public function setSubjectSpecialist(array $settings) {
        $this->staff_id      = $settings['staff_id'];
        $this->fullname      = $settings['fname'].' '.$settings['lname'];
        $this->title         = $settings['title'];
        $this->email         = $settings['email'];
        $this->tel           = $settings['tel'];
        $this->facebook      = $settings['facebook'];
        $this->twitter       = $settings['twitter'];
        $this->pinterest     = $settings['pinterest'];
        $this->instagram     = $settings['instagram'];
        $this->showName      = $settings['showName'];
        $this->showPhoto     = $settings['showPhoto'];
        $this->showTitle     = $settings['showTitle'];
        $this->showEmail     = $settings['showEmail'];
        $this->showPhone     = $settings['showPhone'];
        $this->showFacebook  = $settings['showFacebook'];
        $this->showTwitter   = $settings['showTwitter'];
        $this->showPinterest = $settings['showPinterest'];
        $this->showInstagram = $settings['showInstagram'];
    }

    public function getShowStatus($settings) {

        $status = array(
            'showName'      => $settings['showName'],
            'showPhoto'     => $settings['showPhoto'],
            'showTitle'     => $settings['showTitle'],
            'showEmail'     => $settings['showEmail'],
            'showPhone'     => $settings['showPhone'],
            'showFacebook'  => $settings['showFacebook'],
            'showTwitter'   => $settings['showTwitter'],
            'showPinterest' => $settings['showPinterest'],
            'showInstagram' => $settings['showInstagram']
        );

        return $status;
    }


    protected function setShowStatus($staff_id, array $show_keys, array $data) {

        $item = array();
        for($i = 0; $i < count($show_keys); ++$i) {
            $key = 'show'.$show_keys[$i].$staff_id;

            $value = array();
            if( (isset($key)) && ($key != null) ) {
                $value = $data[$key];
            }


            $keyNoUserId = 'show'.$show_keys[$i];

            if(array_key_exists($key, $data)) {
                if(isset($value[0])) {
                    $item[$keyNoUserId] = $value[0];
                } else {
                    $item[$keyNoUserId] = "No";

                }


            }
        }

        $data = array(
            'showName'      => $item['showName'],
            'showPhoto'     => $item['showPhoto'],
            'showTitle'     => $item['showTitle'],
            'showEmail'     => $item['showEmail'],
            'showPhone'     => $item['showPhone'],
            'showFacebook'  => $item['showFacebook'],
            'showTwitter'   => $item['showTwitter'],
            'showPinterest' => $item['showPinterest'],
            'showInstagram' => $item['showInstagram']
        );

        return $data;

    }

    protected function getStaffSocialMedia($staff_id) {
        $staff = $this->getStaffMember($staff_id);
        $data = json_decode(html_entity_decode( $staff[0]['social_media'] ), true);
        return $data;
    }

    /*
    protected function onViewOutput() {

        //$this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialistViewOutput.php' );




        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT *
                FROM staff s, staff_subject ss
                WHERE s.staff_id = ss.staff_id
                AND ss.subject_id = " . $this->_subject_id . "
                ORDER BY lname, fname";

        $this->_staffArray = $querier->query($qs);

        $array_keys = $this->_array_keys;

        $body_content = "";

        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);

            foreach($this->_staffArray as $staffMember):

                $this->staffName = $staffMember['fname']." ".$staffMember['lname'];
                $staffId = $staffMember['staff_id'];
                $this->staffId = $staffId;

                $staffData = $this->getStaffMember($staffId);

                if ($staffData[0]['social_media'] == '') {

                    $pos = array_search('Instagram', $array_keys);
                    unset($array_keys[$pos]);

                    $pos = array_search('Facebook', $array_keys);
                    unset($array_keys[$pos]);

                    $pos = array_search('Twitter', $array_keys);
                    unset($array_keys[$pos]);

                    $pos = array_search('Pinterest', $array_keys);
                    unset($array_keys[$pos]);

                } else {
                    $staffSocialMedia = json_decode(html_entity_decode( $staffData[0]['social_media'] ), true);

                    if($staffSocialMedia['instagram'] == '') {
                        $pos = array_search('Instagram', $array_keys);
                        unset($array_keys[$pos]);
                    }

                    if($staffSocialMedia['facebook'] == '') {
                        $pos = array_search('Facebook', $array_keys);
                        unset($array_keys[$pos]);
                    }

                    if($staffSocialMedia['twitter'] == '') {
                        $pos = array_search('Twitter', $array_keys);
                        unset($array_keys[$pos]);
                    }

                    if($staffSocialMedia['pinterest'] == '') {
                        $pos = array_search('Pinterest', $array_keys);
                        unset($array_keys[$pos]);
                    }

                }

                $truncated_email = explode("@", $staffData[0]['email']);

                if(isset($_GET['subject'])) {
                    $staff_picture = "../assets/users/_" . $truncated_email[0] . "/headshot.jpg";

                } elseif(isset($_GET['subject_id'])) {
                    $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

                } else {
                    $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";
                }


                if ($staffData[0]['social_media'] != "") {
                    $data = html_entity_decode($staffData[0]['social_media']);
                    $staffSocialMedia = json_decode($data, true);
                }


                $body_content .= "<div class=\"subjectSpecialistPluslet\">";

                $body_content .= "<ul class='staff-details'>";

                foreach($array_keys as $item):

                    if(array_key_exists("show{$item}{$staffId}", $this->_extra)) {

                        $key = 'show'.$item.$staffId;
                        $value = $this->_extra[$key];

                        if( !empty($value[0]) ) {
                            $key_trimmed = rtrim($key, '0123456789');

                            if($key_trimmed == 'showName' && $value[0] == "Yes") {

                                $body_content .= "<h4>{$staffData[0]['fname']} {$staffData[0]['lname']}</h4>";
                            }

                            if($key_trimmed == 'showPhoto' && $value[0] == "Yes") {

                                $body_content .= "<div class='staff-image'><img id='staffPhoto{$this->staffId}' class=\"staff-photo\" src='{$staff_picture}' /></div>";
                            }

                            if($key_trimmed == 'showTitle' && $value[0] == "Yes") {

                                $body_content .= "<li class='staff-content'>".$staffData[0]['title']."</li>";
                            }

                            if($key_trimmed == 'showEmail' && $value[0] == "Yes") {
                                $body_content .= "<li class='staff-content'><a href='mailto:".$staffData[0]['email']."'>".$staffData[0]['email']."</a></li>";
                            }

                            if($key_trimmed == 'showPhone' && $value[0] == "Yes") {
                                $body_content .= "<li class='staff-content'>".$this->tel_prefix.' - '.$staffData[0]['tel']."</li>";
                            }

                            if($key_trimmed == 'showFacebook' && $value[0] == "Yes") {
                                $body_content .= "<span class='staff-social'><a href='http://facebook.com/{$staffSocialMedia['facebook']}'><i class='fa fa-facebook-square'></i></a></span>";
                            }

                            if($key_trimmed == 'showTwitter' && $value[0] == "Yes") {
                                $body_content .= "<span class='staff-social'><a href='http://twitter.com/{$staffSocialMedia['twitter']}'><i class='fa fa-twitter-square'></i></a></span>";
                            }

                            if($key_trimmed == 'showPinterest' && $value[0] == "Yes") {
                                $body_content .= "<span class='staff-social'><a href='http://pinterest.com/{$staffSocialMedia['pinterest']}'><i class='fa fa-pinterest-square'></i></a></span>";
                            }

                            if($key_trimmed == 'showInstagram' && $value[0] == "Yes") {
                                $body_content .= "<span class='staff-social'><a href='http://instagram.com/{$staffSocialMedia['instagram']}'><i class='fa fa-instagram'></i></a></span>";
                            }
                        }
                    }

                endforeach;
                $body_content .= "</ul>";
                $body_content .= '</div>';

            endforeach;

            if($this->_pluslet_id != '') {

                $qry = "SELECT body FROM pluslet WHERE pluslet_id = {$this->_pluslet_id}";
                $qry_result = $querier->query($qry);
                $body_content .= "<div class='pluslet_body_content'>". $qry_result[0]['body']."</div>";

            } else {

                $body_content .= "";
            }

            $body_content .= "<script>$('.pluslet_body_content').appendTo($('.subjectSpecialistPluslet:last-child'));</script>";

            $body_content .= "<script>$('ul.staff-details:empty').parent('div').hide();</script>";

            $this->_body_content = $body_content;


            $this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialistViewOutput.php' );

        }

    }
    */





    /*
    protected function onEditOutput() {
        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT *
                FROM staff s, staff_subject ss
                WHERE s.staff_id = ss.staff_id
                AND ss.subject_id = " . $this->_subject_id . "
                ORDER BY lname, fname";

        $this->_staffArray = $querier->query($qs);


        if($this->_pluslet_id != '') {

            $qry = "SELECT body FROM pluslet WHERE pluslet_id = {$this->_pluslet_id}";
            $qry_result = $querier->query($qry);
            $this->_body_content = $qry_result;

        } else {

            $this->_body_content = array();
        }


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

        $this_instance = "editor1";
        $this->_editor = $oCKeditor->editor($this_instance, $this->_body, $config);


        //this should rarely happen, only on pull from libguides xml does it occur
        if(empty($this->_staffArray)) {

            $this->_body = "There is no staff member associated with this guide. Please select a user for this guide.";

        } else {

            // make an editable body and title type
            if ($this->_extra != "") {
                $this->_extra = json_decode($this->_extra, true);
            }

            $this->_body_content = $this->_body;

            $this->_body .= $this->loadHtml(__DIR__ . '/views/SubjectSpecialistEditOutput.php' );
        }

    }
    */

    static function getMenuIcon() {
        $icon="<i class=\"fa fa-user\" title=\"" . _("Subject Specialist") . "\" ></i><span class=\"icon-text\">" . _("Subject Specialist") ."</span>";
        return $icon;
    }


    static function getMenuName() {
        return _('Subject Specialist');
    }

    protected function getStaffMember($staffId) {


        $querier = new Querier();
        $qs = "SELECT lname, fname, email, tel, title, extra, social_media
                FROM staff
                WHERE staff_id = {$staffId}";

        $staffMember = $querier->query($qs);

        return $staffMember;


    }


}