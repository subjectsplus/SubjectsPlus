<?php
/**
 *   @file GoogleSearch.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date September 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_GoogleSearch extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "GoogleSearch";
    $this->_pluslet_bonus_classes = "type-googlesearch";
  }

  protected function onEditOutput()
  {
  	
    $this->_body = "<p>" . _("Click 'Save' to view your search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  $output = $this->loadHtml(__DIR__ . '/views/GoogleSearch.html');
  	
  $this->_body = "$output";

  }

  static function getMenuName()
  {
    return _('Google Search');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text googlesearh-text\">" . _("Google Search") . "</span>";
        return $icon;
    }


}