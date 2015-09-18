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
		
		$this->db = new Querier ();
	}
	protected function onEditOutput() {
		
		$this->_body = "<div class=\"faq-alert\">" . _("This box automatically links to any child guides that you have assigned to this guide.") . "</div>";
	}
	public function outputRelatedGuides() {
		
		
		$output = "";
		
		$output .= "<ul>";
		
		
		$children = $this->db->query ( 'SELECT * FROM subject INNER JOIN subject_subject ON subject.subject_id = subject_subject.subject_child WHERE subject_parent = ' . $this->_subject_id );
		
		foreach ( $children as $child ) {
		
			$child_info = $this->db->query ( "SELECT * FROM subject WHERE subject_id = {$child['subject_child']} " );
			
			$output .= "<li><a href=\"../../subjects/guide.php?subject={$child_info[0]['shortform']}\">{$child_info[0]['subject']}</a></li>";
		}
		
		$output .= "</ul>";
		
		return $output;
	}
	protected function onViewOutput() {
		$output = $this->outputRelatedGuides ();
		$this->_body = "<div>$output</div>";
	}

	static function getMenuIcon()
   	{
   		$icon="<i class=\"fa fa-files-o\" title=\""  . _("Related Guides") . "\" ></i><span class=\"icon-text\">" . _("Related Guides") . "</span>";
        return $icon;
   	}

	static function getMenuName() {
		return _ ( 'Related' );
	}

	
}
