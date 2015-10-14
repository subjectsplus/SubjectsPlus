<?php
/**
 *   @file NewGuides.php
 *   @brief
 *   @author agdarby, based on Related Guides pluslet
 *   @date Oct 2015
 */
namespace SubjectsPlus\Control;

require_once ("Pluslet.php");

class Pluslet_NewGuides extends Pluslet {
  public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
    parent::__construct ( $pluslet_id, $flag, $subject_id, $isclone );
    
    $this->_subject_id = $subject_id;
    $this->_type = "NewGuides";
    $this->_pluslet_bonus_classes = "type-newguides";
    
    $this->db = new Querier ();
  }
  protected function onEditOutput() {
    
    $this->_body = "<div class=\"faq-alert\">" . _("This box will show the most recently created guides") . "</div>";
  }
  public function outputNewGuides() {

  global $mod_rewrite;
  global $PublicPath;

  // let's use our Pretty URLs if mod_rewrite = TRUE or 1
  if ($mod_rewrite == 1) {
     $guide_path = "";
  } else {
     $guide_path = $PublicPath . "guide.php?subject=";
  }

  // Get our newest guides
  $newguides = $this->db->query("select subject, subject_id, shortform from subject where active = '1' and type != 'Placeholder' order by subject_id DESC limit 0,5");

  $newest_guides = "<ul>\n";

  foreach ($newguides as $myrow2 ) {
      $guide_location = $guide_path . $myrow2[2];
      $newest_guides .= "<li><a href=\"$guide_location\">" . trim($myrow2[0]) . "</a></li>\n";
  }

  $newest_guides .= "</ul>\n";

  return $newest_guides;

  }

  protected function onViewOutput() {
    $output = $this->outputNewGuides ();
    $this->_body = "<div>$output</div>";
  }

  static function getMenuIcon()
    {
      $icon="<span class=\"icon-text guidesearch-text\">" . _("New Guides") . "</span>";
        return $icon;
    }

  static function getMenuName() {
    return _ ( 'NewGuides' );
  }

  
}
