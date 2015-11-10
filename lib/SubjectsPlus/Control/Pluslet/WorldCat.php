<?php
/**
 *   @file WorldCat.php
 *   @brief A worldcat search box
 *   @author little9 (Jamie Little)
 *   @date June 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_WorldCat extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "WorldCat";
    $this->_pluslet_bonus_classes = "type-worldcat";
  }

  protected function onEditOutput()
  {
  	
    $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  $output = $this->loadHtml(__DIR__ . '/views/WorldCat.html');
  	
  $this->_body = "$output";

  }

  static function getMenuName()
  {
    return _('WorldCat Search');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text worldcat-text\">" . _("WorldCat Search") . "</span>";
        return $icon;
    }


}