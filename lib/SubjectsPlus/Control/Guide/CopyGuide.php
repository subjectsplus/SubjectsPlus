<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 1/14/16
 * Time: 2:33 PM
 */


namespace SubjectsPlus\Control\Guide;


use SubjectsPlus\Control\Interfaces\OutputInterface;
use SubjectsPlus\Control\Querier;


class CopyGuide implements OutputInterface {

    private $_subject_id;
    private $_subject;
    private $_staff;
    private $_shortform;
    private $_description;
    private $_keywords;
    private $_redirect_url;
    private $_active;
    private $_type;
    private $_extra;
    private $_message;
    private $_all_tabs;
    private $_departments;
    private $_parents;
    private $_header;
    private $_sections = array ();
    private $_pluslets = array ();
    private $_tabs = array ();

    private $_tab_data = array();
    private $_tab_id;
    private $_tab_ids = array();


    public function __construct($staff_id, $subject_id, Querier $db) {
        $this->_subject_id = $subject_id;
        $this->_staff_id = $staff_id;
        $this->db = $db;

    }


    /*
     * @TODO error handling
     */
    public function copyGuideTransaction($staff_id = null, $subject_id = null, $tab_ids = array()) {



        if(empty($staff_id)) {
            //must have a staff id
        }

        if(empty($subject_id)) {
            // must have a subject id
        }


        try {

            $connection = $this->db->getConnection ();
            $connection->beginTransaction();


            //get existing subject data
            $subject_data = $this->fetchSubjectData($subject_id);

            //insert subject data into subject table and return new subject_id
            $new_subject_id = $this->insertNewSubject($subject_data);

            //insert new_subject_id and staff_id into staff_subject table
            $this->insertStaffSubject($staff_id, $new_subject_id);

            //get tab ids - fetch all if ids not specified in arguments
            if($tab_ids == null) {
                $tab_ids = $this->fetchAllExistingTabIdsBySubjectId($subject_id);
            }

            //get existing tab data
            $tab_data = array();
            foreach( $tab_ids as $id ):
                array_push( $tab_data, $this->fetchTabDataById($id['tab_id']) );
            endforeach;

            //insert new tabs and return both new and old tab ids in array
            $new_and_existing_tab_ids = $this->insertTabData($new_subject_id, $tab_data);

            //get existing section data by tab and create new sections
            //return section data with new section id and tab id along with existing section data
            $section_data = array();
            foreach($new_and_existing_tab_ids as $key => $value) {
                $data['section_data'] = $this->fetchSectionDataByTabId($value['existing_tab_id']);
                $data['section_data']['new_tab_id'] = $value['new_tab_id'];

                $new_section_id = $this->insertSectionData($data);

                $data['section_data']['new_section_id'] = $new_section_id;
                array_push($section_data, $data);
            }


            foreach($section_data as $section) {


                $new_section_tab_array = array();
                $new_value = array();
                foreach($section as $sec) {

                    foreach($sec as $key => $value) {


                        if( isset( $sec['new_tab_id'], $sec['new_section_id'][$key] ) ) {

                            $value['new_tab_id'] = $sec['new_tab_id'];
                            $value['new_section_id'] = $sec['new_section_id'][$key];
                        }

                        array_push($new_section_tab_array, $value);
                    }

                    foreach($new_section_tab_array as $arr) {

                        if( isset( $arr['new_tab_id'], $arr['new_section_id'] ) ) {
                           //get existing pluslet data
                           $existing_pluslet = $this->fetchExistingPlusletDataBySubjectIdTabIdSectionId($subject_id, $arr['tab_id'], $arr['section_id']);

                            if( isset($existing_pluslet) ) {

                                foreach($existing_pluslet as $pluslet) {
                                    //insert new pluslet
                                    $new_pluslet_id = $this->insertPlusletData($pluslet);

                                    //insert new pluslet and section ids
                                    $this->insertPlusletSectionIds($arr['new_section_id'], $new_pluslet_id, $pluslet ['pcolumn'], $pluslet ['prow']);
                                }

                            }

                        }

                    }

                }

            }



            $connection->commit();



            return $new_subject_id;

        } catch (\Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $connection->rollback();
        }


    }




    ////////////////////////////////////////////////////////////////////////////////
    // Subject Functions
    ////////////////////////////////////////////////////////////////////////////////



    public function fetchSubjectData($subject_id) {
        $connection = $this->db->getConnection ();

        $statement = $connection->prepare ( "SELECT * FROM subject WHERE subject_id = :subject_id" );
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute ();
        $results = $statement->fetchAll ();
        return $results;
    }


    public function insertNewSubject($data = array()) {
        $connection = $this->db->getConnection ();

        //add timestamps to subject and shortform to create unique title
        $subject   = $data[0]['subject']. " – " .date('Y-m-d H:i:s');
        $shortform = $data[0]['shortform'].date('Y-m-d-H-i-s');

        $statement = $connection->prepare ( "INSERT INTO subject (`subject`, `active`, `shortform`,`header`, `description`, `keywords`, `type`, `extra`  ) VALUES (:subject, :active, :shortform, :header, :description, :keywords, :type, :extra)" );
        $statement->bindParam ( ':subject',     $subject );
        $statement->bindParam ( ':active',      $data[0]['active'] );
        $statement->bindParam ( ':shortform',   $shortform );
        $statement->bindParam ( ':header',      $data[0]['header'] );
        $statement->bindParam ( ':description', $data[0]['description'] );
        $statement->bindParam ( ':keywords',    $data[0]['keywords'] );
        $statement->bindParam ( ':type',        $data[0]['type'] );
        $statement->bindParam ( ':extra',       $data[0]['extra'] );

        $statement->execute ();
        $subject_insert_id = $this->db->last_id ();


        return $subject_insert_id;
    }


    public function setSubjectData($data) {

        foreach ( $data as $datum ) {
            $this->setSubject( $datum ['subject'] . " – " .date('Y-m-d H:i:s') );
            $this->setShortform( $datum ['shortform'] . date('Y-m-dH:i:s') );
            $this->setDescription( $datum ['description'] );
            $this->setKeywords( $datum ['keywords'] );
            $this->setType( $datum ['type'] );
            $this->setExtra( $datum ['extra'] );
            $this->setActive( $datum ['active'] );
            $this->setHeader( $datum ['header'] );
        }
        return;
    }



    ////////////////////////////////////////////////////////////////////////////////
    // Tab Functions
    ////////////////////////////////////////////////////////////////////////////////

    public function fetchAllExistingTabIdsBySubjectId($subject_id = null) {

        $connection = $this->db->getConnection ();
        $tabs_statement = $connection->prepare ( "SELECT tab_id FROM subject
                            INNER JOIN tab on tab.subject_id = subject.subject_id
                            WHERE subject.subject_id = :subject_id" );
        $tabs_statement->bindParam ( ":subject_id", $subject_id );
        $tabs_statement->execute ();
        $tabs = $tabs_statement->fetchAll ();
        return $tabs;
    }

    public function convertTabsToTabIds($ids = array()) {

        $items = explode(',', $ids);

        $tab_ids = array();
        foreach($items as $tab) {
            $tab_id = array('tab_id' => $tab, '0' => $tab);
            array_push($tab_ids, $tab_id);
        }
        return $tab_ids;
    }


    public function fetchTabDataBySubjectId($subject_id = null) {

        if($subject_id == null) {
            $subject_id = $this->getSubjectId();
        }

        $connection = $this->db->getConnection ();
        $tabs_statement = $connection->prepare ( "SELECT * FROM subject
                            INNER JOIN tab on tab.subject_id = subject.subject_id
                            WHERE subject.subject_id = :subject_id" );
        $tabs_statement->bindParam ( ":subject_id", $subject_id );
        $tabs_statement->execute ();
        $tabs = $tabs_statement->fetchAll ();
        return $tabs;
    }

    protected function insertTabData($new_subject_id = null, $tab_data = array()) {

        if($new_subject_id == null) {
            //throw error
        }

        $tab_ids = array();
        foreach ( $tab_data as $tab ) {
            $connection = $this->db->getConnection ();

            // Insert tabs
            $statement = $connection->prepare ( "INSERT INTO tab (`subject_id`, `label`, `tab_index`, `external_url`, `visibility`) VALUES (:subject_id, :label, :tab_index, :external_url, :visibility )" );
            $statement->bindParam ( ":subject_id",   $new_subject_id );
            $statement->bindParam ( ":label",        $tab[0]['label'] );
            $statement->bindParam ( ":tab_index",    $tab[0]['tab_index'] );
            $statement->bindParam ( ":external_url", $tab[0]['external_url'] );
            $statement->bindParam ( ":visibility",   $tab[0]['visibility'] );
            $statement->execute ();

            $tab_insert_id['new_tab_id'] = $this->db->last_id ();

            $tab_insert_id['existing_tab_id'] = $tab[0]['tab_id'];

            array_push( $tab_ids, $tab_insert_id );
        }
        return $tab_ids;
    }


    public function fetchTabDataById($tab_id = null) {

        $connection = $this->db->getConnection ();
        $tabs_statement = $connection->prepare ( "SELECT * FROM tab WHERE tab_id = :tab_id" );
        $tabs_statement->bindParam ( ":tab_id", $tab_id );
        $tabs_statement->execute ();
        $tab_data = $tabs_statement->fetchAll ();
        return $tab_data;
    }






    ////////////////////////////////////////////////////////////////////////////////
    // Section Functions
    ////////////////////////////////////////////////////////////////////////////////


    public function fetchSectionDataByTabId($tab_id = null) {
        $connection = $this->db->getConnection ();
        // Get Sections
        $sections_statement = $connection->prepare ( "SELECT * FROM section
                                WHERE tab_id = :tab_id" );
        $sections_statement->bindParam ( ":tab_id", $tab_id );
        $sections_statement->execute ();
        $section_data = $sections_statement->fetchAll ();

        return $section_data;
    }


    public function insertSectionData($section_data) {
        $connection = $this->db->getConnection ();

        $section_ids = array();
        foreach($section_data as $data) {

            foreach($data as $key => $value) {

               $new_tab_id = $data['new_tab_id'];

               if(is_array($value))  {
                   $statement = $connection->prepare ( "INSERT INTO section (`tab_id`, `layout`, `section_index`) VALUES (:tab_id, :layout, :section_index)" );
                   $statement->bindParam ( ":tab_id",        $new_tab_id );
                   $statement->bindParam ( ":layout",        $value['layout'] );
                   $statement->bindParam ( ":section_index", $value['section_index'] );
                   $statement->execute ();

                   $section_insert_id = $this->db->last_id ();
                   array_push($section_ids, $section_insert_id);
               }
            }
        }
        return $section_ids;
    }




    ////////////////////////////////////////////////////////////////////////////////
    // Pluslet Functions
    ////////////////////////////////////////////////////////////////////////////////


    public function fetchExistingPlusletDataBySubjectId($subject_id = null) {

        $connection = $this->db->getConnection ();

        $pluslets_statement = $connection->prepare ( "SELECT * FROM subject
                                INNER JOIN tab on tab.subject_id = subject.subject_id
                                INNER JOIN section on tab.tab_id = section.tab_id
                                INNER JOIN pluslet_section on section.section_id = pluslet_section.section_id
                                INNER JOIN pluslet on pluslet_section.pluslet_id = pluslet.pluslet_id
                            WHERE subject.subject_id = :subject_id" );
        $pluslets_statement->bindParam ( ":subject_id", $subject_id );
        $pluslets_statement->execute ();
        $pluslets = $pluslets_statement->fetchAll ();

        return $pluslets;
    }


    public function fetchExistingPlusletDataBySubjectIdTabIdSectionId($subject_id = null, $tab_id = null, $section_id = null) {

        $connection = $this->db->getConnection ();

        $pluslets_statement = $connection->prepare ( "SELECT * FROM subject
                                INNER JOIN tab on tab.subject_id = subject.subject_id
                                INNER JOIN section on tab.tab_id = section.tab_id
                                INNER JOIN pluslet_section on section.section_id = pluslet_section.section_id
                                INNER JOIN pluslet on pluslet_section.pluslet_id = pluslet.pluslet_id
                            WHERE subject.subject_id = :subject_id
                            AND section.section_id = :section_id
                            AND section.tab_id = :tab_id" );
        $pluslets_statement->bindParam ( ":subject_id", $subject_id );
        $pluslets_statement->bindParam ( ":section_id", $section_id );
        $pluslets_statement->bindParam ( ":tab_id", $tab_id );

        $pluslets_statement->execute ();
        $pluslet = $pluslets_statement->fetchAll ();

        return $pluslet;
    }




    public function insertPlusletData($pluslet) {
        $connection = $this->db->getConnection ();

        $pluslet_statement = $connection->prepare ( "
			    		INSERT INTO pluslet (`title`, `body`, `type`, `extra`, `hide_titlebar`,`collapse_body`, `titlebar_styling`, `favorite_box`)
			    		VALUES (:title, :body, :type, :extra, :hide_titlebar, :collapse_body, :titlebar_styling, :favorite_box) " );

        $pluslet_statement->bindParam ( ':title', $pluslet ['title'] );
        $pluslet_statement->bindParam ( ':body', $pluslet ['body'] );
        $pluslet_statement->bindParam ( ':type', $pluslet ['type'] );
        $pluslet_statement->bindParam ( ':extra', $pluslet ['extra'] );
        $pluslet_statement->bindParam ( ':hide_titlebar', $pluslet ['hide_titlebar'] );
        $pluslet_statement->bindParam ( ':collapse_body', $pluslet ['collapse_body'] );
        $pluslet_statement->bindParam ( ':titlebar_styling', $pluslet ['titlebar_styling'] );
        $pluslet_statement->bindParam ( ':favorite_box', $pluslet ['favorite_box'] );
        $pluslet_statement->execute ();

        $pluslet_last_id = $this->db->last_id ();

        return $pluslet_last_id;
    }

    public function insertPlusletSectionIds($new_section_id, $new_pluslet_id, $pluslet_pcolumn, $pluslet_prow) {
        $connection = $this->db->getConnection ();

        $statement = $connection->prepare ( "INSERT INTO pluslet_section (`section_id`, `pluslet_id`, `pcolumn`, `prow` ) VALUES (:section_id, :pluslet_id, :pcolumn, :prow) " );
        $statement->bindParam ( ":section_id", $new_section_id );
        $statement->bindParam ( ":pluslet_id", $new_pluslet_id );
        $statement->bindParam ( ":pcolumn",    $pluslet_pcolumn );
        $statement->bindParam ( ":prow",       $pluslet_prow );
        $statement->execute ();

        $pluslet_section_last_id = $this->db->last_id ();

        return $pluslet_section_last_id;
    }



    ////////////////////////////////////////////////////////////////////////////////
    //
    ////////////////////////////////////////////////////////////////////////////////



    public function insertStaffSubject($staff_id, $subject_id) {
        $connection = $this->db->getConnection ();
        $statement = $connection->prepare("INSERT INTO staff_subject (`staff_id`, `subject_id`) VALUES (:staff_id, :subject_id)");
        $statement->bindParam(":staff_id", $staff_id);
        $statement->bindParam(":subject_id", $subject_id);
        $statement->execute();
    }




    public function setSubjectTabData($tab_data) {
        $this->_tab_data = $tab_data;
    }

    public function getSubjectTabData() {
        return $this->_tab_data;
    }


    public function fetchStaffByEmail($email) {
        $connection = $this->db->getConnection ();

        $statement = $connection->prepare("SELECT staff_id FROM staff WHERE email = :email");
        $statement->bindParam(":email", $email);
        $statement->execute();
        $staff_id = $statement->fetchAll();

        return $staff_id;
    }




    /**
     * @return mixed
     */
    public function getSubjectId()
    {
        return $this->_subject_id;
    }

    /**
     * @param mixed $subject_id
     */
    public function setSubjectId($subject_id)
    {
        $this->_subject_id = $subject_id;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getStaff()
    {
        return $this->_staff;
    }

    /**
     * @param mixed $staff
     */
    public function setStaff($staff)
    {
        $this->_staff = $staff;
    }

    /**
     * @return mixed
     */
    public function getShortform()
    {
        return $this->_shortform;
    }

    /**
     * @param mixed $shortform
     */
    public function setShortform($shortform)
    {
        $this->_shortform = $shortform;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->_keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->_keywords = $keywords;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->_redirect_url;
    }

    /**
     * @param mixed $redirect_url
     */
    public function setRedirectUrl($redirect_url)
    {
        $this->_redirect_url = $redirect_url;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->_active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->_active = $active;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->_extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->_extra = $extra;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * @return mixed
     */
    public function getTabId()
    {
        return $this->_tab_id;
    }

    /**
     * @param mixed $tab_id
     */
    public function setTabId($tab_id)
    {
        $this->_tab_id = $tab_id;
    }

    /**
     * @return array
     */
    public function getTabIds()
    {
        return $this->_tab_ids;
    }

    /**
     * @param array $tab_ids
     */
    public function setTabIds($tab_ids)
    {
        $this->_tab_ids = $tab_ids;
    }

    /**
     * @return array
     */
    public function getTabData()
    {
        return $this->_tab_data;
    }

    /**
     * @param array $tab_data
     */
    public function setTabData($tab_data)
    {
        $this->_tab_data = $tab_data;
    }

    /**
     * @return mixed
     */
    public function getAllTabs()
    {
        return $this->_all_tabs;
    }

    /**
     * @param mixed $all_tabs
     */
    public function setAllTabs($all_tabs)
    {
        $this->_all_tabs = $all_tabs;
    }

    /**
     * @return mixed
     */
    public function getDepartments()
    {
        return $this->_departments;
    }

    /**
     * @param mixed $departments
     */
    public function setDepartments($departments)
    {
        $this->_departments = $departments;
    }

    /**
     * @return mixed
     */
    public function getParents()
    {
        return $this->_parents;
    }

    /**
     * @param mixed $parents
     */
    public function setParents($parents)
    {
        $this->_parents = $parents;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->_header = $header;
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->_sections;
    }

    /**
     * @param array $sections
     */
    public function setSections($sections)
    {
        $this->_sections = $sections;
    }

    /**
     * @return array
     */
    public function getPluslets()
    {
        return $this->_pluslets;
    }

    /**
     * @param array $pluslets
     */
    public function setPluslets($pluslets)
    {
        $this->_pluslets = $pluslets;
    }

    /**
     * @return array
     */
    public function getTabs()
    {
        return $this->_tabs;
    }

    /**
     * @param array $tabs
     */
    public function setTabs($tabs)
    {
        $this->_tabs = $tabs;
    }



    public function toArray() {
        return get_object_vars ( $this );
    }
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }


}