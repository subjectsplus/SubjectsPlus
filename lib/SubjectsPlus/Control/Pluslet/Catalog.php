<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Catalog extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "Catalog";
  }

  protected function onEditOutput()
  {
  	
    $this->_body = $this->loadHtml(__DIR__  . "/views/Catalog.html");
   
  }

  protected function onViewOutput()
  {

  	$this->_body = $this->loadHtml(__DIR__  . "/views/Catalog.html");

  }

  static function getMenuName()
  {
    return _('Catalog Search');
  }

  
  
  

}