<?php
/**
 *   @file Related.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date July 2015
 */
namespace SubjectsPlus\Control;

require_once ("Pluslet.php");
class Pluslet_Related extends Pluslet {
    public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
        parent::__construct ( $pluslet_id, $flag, $subject_id, $isclone );

        $this->_subject_id = $subject_id;
        $this->_type = "Related";
        $this->_pluslet_bonus_classes = "type-relguide";
        $this->_isclone = $isclone;

        if(isset($pluslet_id)) {
            $this->_pluslet_id = $pluslet_id;
        }

        $this->db = new Querier ();
    }
    protected function onEditOutput() {

        $this->_body = "<div class=\"faq-alert\">" . _("This box automatically links to any child guides that you have assigned to this guide.") . "</div>";
    }
    public function outputRelatedGuides() {

        $output = "";

        //is this a child guide? if so, get parent and siblings
        $parent_id = $this->isChildGuide($this->_subject_id);


        if($this->_isclone == 1) {
            $subject_id = $this->getSubjectIdByMasterId($this->_pluslet_id);
            $subject_id = $subject_id['subject_id'];

        } elseif ( (isset($parent_id)) && ($parent_id != NULL) ) {

            $subject_id = $parent_id['subject_parent'];

        } else {
            $subject_id = $this->_subject_id;
        }

        //get master subject and shortform
        $master_subject = $this->getMasterSubject($subject_id);

        if(isset($master_subject)) {
            $output .= "<h3><a href=\"../../subjects/guide.php?subject={$master_subject['shortform']}\"> {$master_subject['subject']}</a></h3>";
        }


        $output .= "<ul>";

        $children = $this->db->query ( 'SELECT * FROM subject INNER JOIN subject_subject ON subject.subject_id = subject_subject.subject_child WHERE active = 1 AND subject_parent = ' . $subject_id .' ORDER BY subject.subject ASC' );

        foreach ( $children as $child ) {

            $child_info = $this->db->query ( "SELECT * FROM subject WHERE subject_id = {$child['subject_child']} " );

            $output .= "<li><a href=\"../../subjects/guide.php?subject={$child_info[0]['shortform']}\">{$child_info[0]['subject']}</a></li>";
        }

        $output .= "</ul>";
        return $output;
    }

    protected function onViewOutput() {
        $output = $this->outputRelatedGuides ();

        $this->_body .= "<div>{$output}</div>";
    }

    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-files-o\" title=\""  . _("Related Guides") . "\" ></i><span class=\"icon-text\">" . _("Related Guides") . "</span>";
        return $icon;
    }

    static function getMenuName() {
        return _ ( 'Related' );
    }

    public function getSubjectIdByMasterId($master_id) {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT subject_id FROM tab
											LEFT JOIN section ON tab.tab_id = section.tab_id
											LEFT JOIN pluslet_section ON section.section_id = pluslet_section.section_id
											WHERE pluslet_section.pluslet_id  = :master_id");

        $statement->bindParam ( ":master_id", $master_id );
        $statement->execute();
        $subject_id = $statement->fetch();
        return $subject_id;
    }

    public function getMasterSubject($master_id) {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT subject, shortform FROM subject
											WHERE subject_id  = :master_id");

        $statement->bindParam ( ":master_id", $master_id );
        $statement->execute();
        $subject = $statement->fetch();
        return $subject;
    }

    public function isChildGuide($subject_id) {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT subject_parent FROM subject_subject
											WHERE subject_child  = :subject_id");

        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $parent_id = $statement->fetch();
        return $parent_id;
    }


}