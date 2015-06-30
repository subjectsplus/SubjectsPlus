<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Test extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "Test";
  }



  protected function onEditOutput()
  {


    $this->_body = "<p>Edit output</p>";
   
  }

  protected function onViewOutput()
  {


    $this->_body = "<p>View output</p>";
  

  }

  static function getMenuName()
  {
    return _('Test');
  }


}