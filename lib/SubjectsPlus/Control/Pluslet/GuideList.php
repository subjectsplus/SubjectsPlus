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

    // let's use our Pretty URLs if mod_rewrite = TRUE or 1
    if ($mod_rewrite == 1) {
       $guide_path = "";
    } else {
       $guide_path = $PublicPath . "guide.php?subject=";
    }

  // Get our newest guides
  $guides = $this->db->query("select subject_id, subject, shortform from subject where active = '1' and type != 'Placeholder' order by subject");

        $col_1 = "<div class=\"pure-u-1-2\">";
        $col_2 = "<div class=\"pure-u-1-2\">";

        $row_count = 0;

        foreach ($guides as $myrow) {

        $guide_location = $guide_path . $myrow[0];

        $our_item = "<li><a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
        <div class=\"list_bonus\">$myrow[2]</div>
        </li>";

          if ($row_count & 1) {
            // odd
            $col_2 .= $our_item;
          } else {
            // even
            $col_1 .= $our_item;
          }

        $row_count++;
        }

        $col_1 .= "</div>";
        $col_2 .= "</div>";


        $list_guides = "<div class=\"pure-g guide_list\">" . $col_1 . $col_2 . "</div>";


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