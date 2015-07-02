<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_WorldCat extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "WorldCat";
  }

  protected function onEditOutput()
  {
  	
    $this->_body = "<p>" . _("Click 'Save' to view your search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  $output = $this->loadHtml(__DIR__ . '/views/WorldCat.html');
  	
  $this->_body = "<p>$output</p>";

  }

  static function getMenuName()
  {
    return _('WorldCat Search');
  }


}