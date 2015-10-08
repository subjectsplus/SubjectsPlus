<?php
/**
 *   @file NewDBs.php
 *   @brief
 *   @author agdarby, based on Related Guides pluslet
 *   @date Oct 2015
 */
namespace SubjectsPlus\Control;

require_once ("Pluslet.php");

class Pluslet_NewDBs extends Pluslet {
  public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
    parent::__construct ( $pluslet_id, $flag, $subject_id, $isclone );
    
    $this->_subject_id = $subject_id;
    $this->_type = "NewDBs";
    $this->_pluslet_bonus_classes = "type-newdbs";
    
    $this->db = new Querier ();
  }
  protected function onEditOutput() {
    
    $this->_body = "<div class=\"faq-alert\">" . _("This box will show the databases flagged as 'new.'") . "</div>";
  }
  public function outputNewDBs() {
  
  global $proxyURL;  

  $databases = $this->db->query("SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5");

  $newlist = "<ul>\n";

    foreach ($databases as $myrow) {
      $db_url = "";

      // add proxy string if necessary
      if ($myrow[0][2] != 1) {
        $db_url = $proxyURL;
      }

      $newlist .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";
    }

  $newlist .= "</ul>\n";

  return $newlist;

  }

  protected function onViewOutput() {
    $output = $this->outputNewDBs ();
    $this->_body = "<div>$output</div>";
  }

  static function getMenuIcon()
    {
      $icon="<i class=\"fa fa-database\" title=\""  . _("New DBs") . "\" ></i><span class=\"icon-text\">" . _("New DBs") . "</span>";
        return $icon;
    }

  static function getMenuName() {
    return _ ( 'NewDBs' );
  }

  
}
