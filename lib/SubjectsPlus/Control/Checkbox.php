<?php
   namespace SubjectsPlus\Control;
/**
 *   @file 
 *   @brief 
 *
 *   @author adarby
 *   @date 
 *   @todo
 */

class Checkbox {
  
  private $_cb_name;
  private $_selected;
  private $_value;
  private $_label;

  public function __construct($cb_name, $selected="", $value, $label) {
    $this->_cb_name = $cb_name;
	 $this->_selected = $selected;
	 $this->_value = $value;
	 $this->_label= $label;
	 
  }
  

	public function display() {
		
		if ($this->_selected == $this->_value) {$azchecked = "checked";} 
		$checkbox = "<input type=\"checkbox\" name=\"$this->_cb_name\" value=\"$this->_value\" $azchecked />$this->_label";
		
		return $checkbox;	
		
	}
  
}

?>