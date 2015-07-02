<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Clone extends Pluslet {

  private $_master;
	
  public function __construct($pluslet_id, $flag="", $subject_id, $isclone="") {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone="");
  
    $this->_type = "Clone";

  }

  protected function onEditOutput()
  {
  	if ($this->_extra != "") {
  		$master = json_decode($this->_extra);
  		$this->_body =  "<input required aria-required=\"true\" type=\"text\" id=\"master\" name=\"Clone-extra-master\" value=\"{$master->master}\"></input>";
  		
  	} else {
  		
  		$this->_body =  '<input required aria-required="true" type="text" id="master" name="Clone-extra-master" value=""></input>';
  		
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


}