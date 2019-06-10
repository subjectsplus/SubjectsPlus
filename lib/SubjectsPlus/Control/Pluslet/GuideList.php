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

    // loop through our source types
    foreach ($guide_types as $key => $value) {

      $query ="select subject_id, subject, shortform, type, description, keywords 
      FROM subject where active = '1' and type != 'Placeholder' and type = '" . $value .  "' order by subject";

      $guides = $this->db->query($query);

      $total_rows = count($guides); // total number of guides
      $switch_row = round($total_rows / 2);

      if ($total_rows > 0) {

        $guide_list_div = "<div class=\"pure-u-1 two-columns\"><ul class=\"guide-listing\">";

        foreach ($guides as $myrow) {


          $guide_location = $guide_path . $myrow['shortform'];
          $list_bonus = "";

          if ($myrow['description'] != "") {$list_bonus .= $myrow['description'] . "<br /><br />"; } // add description
          if ($myrow['keywords'] != "") {$list_bonus .= "<strong>Keywords:</strong> " . $myrow['keywords']; } // add keywords

          $our_item = "<li><i class=\"fa fa-plus-square\"></i> <a href=\"$guide_location\">" . htmlspecialchars_decode($myrow['subject']) . "</a>

            <div class=\"guide_list_bonus\">$list_bonus</div>
            </li>";

          $guide_list_div .= $our_item;

        }//end foreach

        $list_guides .= "<div class=\"pure-g guide_list\"><div class=\"pure-u-1 guide_list_header\"><a name=\"section-$value\"></a><h3>$value</h3></div>" . $guide_list_div ."</ul></div></div>";
      }

    }

    // ANCHOR buttons for guide types
    //**************************************
    $guide_type_btns = "<ul>";

    // We don't want our placeholder
    if (in_array('Placeholder', $guide_types)) { unset($guide_types[array_search('Placeholder',$guide_types)]); }

    foreach ($guide_types as $key) {
      $guide_type_btns .= "<li><a id=\"show-" . ucfirst($key) . "\" name=\"show$key\" href=\"#section-" . ucfirst($key) . "\">";

      $guide_type_btns .= ucfirst($key) . " Guides</a></li>\n";
    }

    $guide_type_btns .= "</ul>";

    $final_list = "<div class=\"pills-label\">" . _("Select:") ."</div><div class=\"pills-container\">" . $guide_type_btns . "</div>" . $list_guides;


    return $final_list;

  }

  static function getMenuName()
  {
    return _('List Guides');
  }

  static function getMenuIcon()
  {
    $icon="<i class=\"fa fa-bars\" title=\"" . _("List Guides") . "\" ></i><span class=\"icon-text\">"  . _("List Guides") . "</span>";
    return $icon;
  }


}
