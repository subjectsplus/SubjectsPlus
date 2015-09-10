<?php
/**
 *   @file ArticlesPlus.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_ArticlesPlus extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "ArticlesPlus";
    $this->_pluslet_bonus_classes = "type-articleplus";
  }

  protected function onEditOutput()
  {
  	
    $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your Articles+ search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  $output = $this->loadHtml(__DIR__ . '/views/ArticlesPlus.html');
  	
  $this->_body = "$output";

  }

  static function getMenuName()
  {
    return _('Articles+ Search');
  }

  static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-file-text-o\" title=\"" . _("Articles+ Search") . "\" ></i><span class=\"icon-text articlesplus-text\">" . _("Articles+ Search") . "</span>";
        return $icon;
    }


}