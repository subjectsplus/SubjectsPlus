<?php
/**
 *   @file NewGuides.php
 *   @brief
 *   @author agdarby, based on Related Guides pluslet
 *   @date Oct 2015
 */
namespace SubjectsPlus\Control;

require_once ("Pluslet.php");

class Pluslet_CollectionList extends Pluslet {
  public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
    parent::__construct ( $pluslet_id, $flag, $subject_id, $isclone );
    
    $this->_subject_id = $subject_id;
    $this->_type = "CollectionList";
    $this->_pluslet_bonus_classes = "type-collectionlist";
    
    $this->db = new Querier ();
  }
  protected function onEditOutput() {
    
    $this->_body = "<div class=\"faq-alert\">" . _("This box will show the collections of guides") . "</div>";
  }
  public function outputCollectionList() {

  global $mod_rewrite;
  global $PublicPath;

    $db = new Querier();
    
    $whereclause = "";
    global $guide_path;

    $q = "SELECT collection_id, title, description, shortform FROM collection ORDER BY title";

   // $r = $db->query($q);
    //print $q;
    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    $list_collections = "<ul>";

    foreach ($db->query($q) as $myrow) {

        $row_colour = ($row_count % 2) ? $colour1 : $colour2;

        $guide_location = "collection.php?d=" . $myrow[3];

        $list_collections .= "<li><a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
        <div style=\"font-size: .9em;\">$myrow[2]</div></li>\n";

        $row_count++; 
    }

    $list_collections .= "</ul>";
    
    return $list_collections;

  }

  protected function onViewOutput() {
    $output = $this->outputCollectionList ();
    $this->_body = "<div>$output</div>";
  }

  static function getMenuIcon()
    {
      $icon="<span class=\"icon-text guidesearch-text\">" . _("Guide Collections") . "</span>";
        return $icon;
    }

  static function getMenuName() {
    return _ ( 'CollectionList' );
  }

  
}
