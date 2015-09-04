<?php

/**
 *   @file Catalog.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date June 2015
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Catalog extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "Catalog";
    $this->_pluslet_bonus_classes = "type-catalog";
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

  static function getMenuIcon()
    {
      $icon="<i class=\"fa fa-book\" title=\"" . _("Catalog Search") . "\" ></i><span class=\"icon-text\">" . _("Catalog Search") . "</span>";
        return $icon;
    }

  
  
  

}