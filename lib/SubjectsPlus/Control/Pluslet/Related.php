<?php

namespace SubjectsPlus\Control;

require_once ("Pluslet.php");
class Pluslet_Related extends Pluslet {
	public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
		parent::__construct ( $pluslet_id, $flag, $subject_id, $isclone );
		
		$this->_type = "Related";
		
		$this->db = new Querier ();
	}
	protected function onEditOutput() {
		$output = $this->outputRelatedGuides ();
		$this->_body = "<div>$output</div>";
	}
	public function outputRelatedGuides() {
		$output = "";
		
		$output .= "<ul>";
		
		$children = $this->db->query ( 'SELECT subject_child  FROM subject NATURAL JOIN subject_subject WHERE subject_id = 653877' );
		
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
	static function getMenuName() {
		return _ ( 'Related' );
	}
}