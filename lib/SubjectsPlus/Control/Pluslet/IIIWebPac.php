<?php
/**
 *   @file IIIWebPac.php
 *   @brief A III WebPac search box
 *   @author agdarby
 *   @date September 2018
 */
namespace SubjectsPlus\Control;

require_once("Pluslet.php");

class Pluslet_IIIWebPac extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "IIIWebPac";
    //$this->_pluslet_bonus_classes = "type-googlescholar";
  }

  protected function onEditOutput()
  {
    
    $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  $output = $this->loadHtml(__DIR__ . '/views/IIIWebPac.php');
    
  $this->_body = "$output";

  }

  static function getMenuName()
  {
    return _('III WebPac');
  }

  static function getMenuIcon()
    {
        
        $icon="<i class=\"fa fa-book\" title=\"" . self::getMenuName() . "\" ></i><span class=\"icon-text\">" . self::getMenuName() . "</span>";
        return $icon;
    }


}