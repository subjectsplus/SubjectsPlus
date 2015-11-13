<?php
/**
 *   @file GuideList.php
 *   @brief A list of all the guides
 *   @author agdarby
 *   @date Nov 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_GuideList extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "GuideList";
    $this->_pluslet_bonus_classes = "type-guidelist";

    $this->db = new Querier ();

  }

  protected function onEditOutput()
  {
  	
    $output = $this->outputGuides();
    $this->_body = $output;
   
  }

  protected function onViewOutput()
  {

    $output = $this->outputGuides();
    $this->_body = $output;

  }

  public function outputGuides() {

  global $mod_rewrite;
  global $PublicPath;
  global $guide_types;

    // let's use our Pretty URLs if mod_rewrite = TRUE or 1
    if ($mod_rewrite == 1) {
       $guide_path = "";
    } else {
       $guide_path = $PublicPath . "guide.php?subject=";
    }

    $layout = ""; //init

    // loop through our source types
    foreach ($guide_types as $key => $value) {

      $query ="select subject_id, subject, shortform, type, description, keywords 
      FROM subject where active = '1' and type != 'Placeholder' and type = '" . $value .  "' order by subject";

      $guides = $this->db->query($query);

      $total_rows = count($guides); // total number of guides
      $switch_row = round($total_rows / 2);

      if ($total_rows > 0) {

        $col_1 = "<div class=\"pure-u-1-2\">";
        $col_2 = "<div class=\"pure-u-1-2\">";

        $row_count = 1;

        foreach ($guides as $myrow) {

        $guide_location = $guide_path . $myrow[0];
        $list_bonus = "";

        if ($myrow[4] != "") {$list_bonus .= $myrow[4] . "<br />"; } // add description
        if ($myrow[5] != "") {$list_bonus .= $myrow[5] . "<br />"; } // add keywords
        $list_bonus .= $myrow[3] . "<br />";

        $our_item = "<li><a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
        <div class=\"list_bonus\">$list_bonus</div>
        </li>";

          if ($row_count <= $switch_row) {
            // first col
            $col_1 .= $our_item;
            
          } else {
            // even
            $col_2 .= $our_item;
          }

        $row_count++;
        }

        $col_1 .= "</div>";
        $col_2 .= "</div>";

        $layout .= "<div class=\"pure-u-1 guide_list_header\">$value</div>" . $col_1 . $col_2;

        $list_guides = "<div class=\"pure-g guide_list\">$layout</div>";
      }

    }
  // Get our newest guides


  




  return $list_guides;

  }

  static function getMenuName()
  {
    return _('List Guides');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text googlescholar-text\">" . _("List Guides") . "</span>";
        return $icon;
    }


}