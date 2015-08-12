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
  			 $this->_body = "<p>" . _('This box was cloned from another pluslet.') . "</p>";
  			 $this->_body .=  "<input class=\"clone-input\" required aria-required=\"true\" type=\"text\" name=\"Clone-extra-master\" value=\"{$master->master}\"></input>";
			 $this->_body .= "<p>" . _('It can be found on this guide: ') . "<a href='.//guide.php?subject_id={$parent_guide[0][subject_id]}'>" . $parent_guide[0]['subject'] . "</a></p>";
  			 
  		 } else {
		
  			 $this->_body = "<p>" . _('Your boxhas been cloned. You will need to save the page to view its contents.') . "</p>";
  			 $this->_body .=  '<input class="clone-input" required aria-required="true" type="text" name="Clone-extra-master" value=""></input>';
			 $this->_body .= $parent_guide;
			
  		 }
  		 
		 
	 }

	 protected function onViewOutput()
	 {

		 
		 $master = json_decode($this->_extra);
		 
		 $db = new Querier;
		 $output = $db->query("SELECT body from pluslet WHERE pluslet_id = '{$master->master}'");
		 
		 $this->_body = "<p>{$output[0]['body']}</p>";
		 
  		 
		 
	 }

	 
	 
	 static function getMenuName()
	 {
		 return _('Clone');
	 }


	 public function getParentGuide() {
		 $master = json_decode($this->_extra);

		 
		 $pluslet_id = $this->db->quote($this->_id);
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
