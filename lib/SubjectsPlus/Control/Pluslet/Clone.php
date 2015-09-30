<?php
/**
 *   @file Clone.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date July 2015
 */
namespace SubjectsPlus\Control;

require_once("Pluslet.php");

 class Pluslet_Clone extends Pluslet {

	 protected $_master;

	 private $_id;
	 
	 public function __construct($pluslet_id, $flag="", $subject_id, $isclone="") {
		 parent::__construct($pluslet_id, $flag, $subject_id, $isclone="");
		 
		 $this->db = new Querier;
		 $this->_id = $pluslet_id;
		 $this->_type = "Clone";

	 }

	 protected function onEditOutput()
	 {
  		 if ($this->_extra != "") {
		  
  		  $parent_guide =  $this->getParentGuide();
			

			 
  			 $master = json_decode($this->_extra);
  			 $this->_body = "<p class=\"clone-alert\">" . _('This box is linked to another box.') . "</p>";
  			 $this->_body .=  "<input class=\"clone-input\" required aria-required=\"true\" type=\"text\" name=\"Clone-extra-master\" value=\"{$master->master}\"></input>";
			 
  			 if (isset($parent_guide[0]['subject_id'])){
  			 	$this->_body .= "<p>" . _('It can be found on this guide: ') . "<a href='./guide.php?subject_id={$parent_guide[0]['subject_id']}' target='_blank'>" . $parent_guide[0]['subject'] . "</a></p>";
  			 		
  			 }else {
  			 	$this->_body .= "<p class=\"clone-warning\">" . _("There was a problem locating its original source. It's possible that the original guide has been deleted.") . "</p>";
  			 
  			 }
  			 	
  			 
  		 } else {
		
  			 $this->_body = "<p class=\"clone-alert\">" . _('Your box has been linked. You will need to save the page to view its contents.') . "</p>";
  			 $this->_body .=  '<input class="clone-input" required aria-required="true" type="text" name="Clone-extra-master" value=""></input>';
			
  		 }
  		 
		 
	 }

	 protected function onViewOutput()
	 {
		 $master = json_decode($this->_extra);
		 
		 if (is_object($master)) {

			 $pluslet_type = $this->getPlusletType($master->master);

			 $cloned_body = $this->getPlusletCloneBody($pluslet_type, $master->master, $this->_subject_id);

			 $this->_body .= $cloned_body;

		 } else {
		 	
		 }
		 
	 }


	 public function getPlusletType($pluslet_id) {

		 $db = new Querier();
		 $pluslet_type = $db->query("SELECT type from pluslet WHERE pluslet_id = '{$pluslet_id}'");

		 $type = $pluslet_type[0]['type'];
		 return $type;
	 }

	 public function getPlusletCloneBody($type, $master, $subject_id) {

		 $cloned_pluslet = "";

		 switch($type) {

			 case "HTML5Video":
				 $cloned_pluslet = new Pluslet_HTML5Video($master, null, $subject_id, 1);
				 break;

			 case "TOC":
				 $cloned_pluslet = new Pluslet_TOC($master, null, $subject_id, 1);
				 break;

			 case "Feed":
				 $cloned_pluslet = new Pluslet_Feed($master, null, $subject_id, 1);
				 break;

			 case "SocialMedia":
				 $cloned_pluslet = new Pluslet_SocialMedia($master, null, $subject_id, 1);
				 break;

			 case "SubjectSpecialist":
				 $cloned_pluslet = new Pluslet_SubjectSpecialist($master, null, $subject_id, 1);
				 break;

			 case "Basic":
				 $cloned_pluslet = new Pluslet_Basic($master, null, $subject_id, 1);
				 break;
		 }

		 $cloned_pluslet->_isclone = 1;
		 $cloned_pluslet->_hide_titlebar = 1;
		 $cloned_pluslet = $cloned_pluslet->output(null, 'admin');
		 return $cloned_pluslet;
	 }
	 
	 
	 static function getMenuName()
	 {
		 return _('Clone');
	 }


	 public function getParentGuide() {
		 $master = json_decode($this->_extra);

		 		 
		 
	$results = $this->db->query("SELECT p.pluslet_id as 'id',su.shortform as 'short_form','Pluslet' as 'content_type', p.title, p.title AS 'label', ps.section_id, t.tab_index AS 'additional_id', t.subject_id, su.subject FROM pluslet AS p
                    INNER JOIN pluslet_section AS ps
                    ON ps.pluslet_id = p.pluslet_id
                    INNER JOIN section AS s
                    ON ps.section_id = s.section_id
                    INNER JOIN tab AS t
                    ON s.tab_id = t.tab_id
                    INNER JOIN subject AS su
                    ON su.subject_id = t.subject_id
                    WHERE p.pluslet_id = {$master->master}" );
		 
			    
		 return $results;

	
	 }


	 

	 
 }
