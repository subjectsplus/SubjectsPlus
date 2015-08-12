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
  }

  protected function onEditOutput()
  {
  	
    $this->_body = "<p>" . _("Click 'Save' to view your search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  $output = $this->loadHtml(__DIR__ . '/views/ArticlesPlus.html');
  	
  $this->_body = "<p>$output</p>";

  }

  static function getMenuName()
  {
    return _('Articles+ Search');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text articlesplus-text\">" . _("Articles+ Search") . "</span>";
        return $icon;
    }


}