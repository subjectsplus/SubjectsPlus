<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Clone extends Pluslet {

  private $_master;
	
  public function __construct($pluslet_id, $flag="", $subject_id, $isclone="") {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone="");
  
    $this->db = new Querier;
    
    $this->_type = "Clone";

  }

  protected function onEditOutput()
  {
  	if ($this->_extra != "") {
  		$master = json_decode($this->_extra);
  		$this->_body = "<p>" . _('This pluslet was cloned from another pluslet.') . "</p>";
  		$this->_body .=  "<input class=\"clone-input\" required aria-required=\"true\" type=\"text\" name=\"Clone-extra-master\" value=\"{$master->master}\"></input>";
  		
  	} else {
  		$this->_body = "<p>" . _('Your pluslet has been cloned. You will need to save the page to view its contents.') . "</p>";
  		$this->_body .=  '<input class="clone-input" required aria-required="true" type="text" name="Clone-extra-master" value=""></input>';
  		
  	}
  	
   
  }

  protected function onViewOutput()
  {

  
  $master = json_decode($this->_extra);
  
  $db = new Querier;
  $output = $db->query("SELECT body from pluslet WHERE pluslet_id = '{$master->master}'");
  
  $this->_body = "<p>{$output[0]['body']}</p>";
  
  	
  
  }

  public function getSubjects() {
  	
  	$subjects = $this->db->query("SELECT s.shortform, s.subject FROM subject s
  	INNER JOIN tab t
  	ON s.subject_id = t.subject_id
  	INNER JOIN section sec
  	ON t.tab_id = sec.tab_id
  	INNER JOIN pluslet_section ps
  	ON sec.section_id = ps.section_id
  	GROUP BY s.subject ");
  	
  	return $subjects;
  	
  }
  
  
  
  static function getMenuName()
  {
    return _('Clone');
  }


}