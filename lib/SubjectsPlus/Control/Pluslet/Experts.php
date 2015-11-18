<?php
/**
 *   @file Experts.php
 *   @brief This is to generate 
 *   @author agdarby
 *   @date Nov 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Experts extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "Experts";
    $this->_pluslet_bonus_classes = "type-experts";

    $this->db = new Querier ();

  }

  protected function onEditOutput()
  {
  	
    $output = $this->outputExperts();
    $this->_body = $output;
   
  }

  protected function onViewOutput()
  {

    $output = $this->outputExperts();
    $this->_body = $output;

  }

  public function outputExperts() {

  global $mod_rewrite;
  global $PublicPath;
  global $guide_types;

    // let's use our Pretty URLs if mod_rewrite = TRUE or 1
    if ($mod_rewrite == 1) {
       $guide_path = "";
    } else {
       $guide_path = $PublicPath . "guide.php?subject=";
    }

    // get all of our librarian experts into an array
    $q = "SELECT DISTINCT (s.staff_id), CONCAT(s.fname, ' ', s.lname) AS fullname, s.email, s.tel, s.title, sub.subject  FROM staff s, staff_subject ss, subject sub
          WHERE s.staff_id = ss.staff_id
          AND ss.subject_id = sub.subject_id
          AND s.active = 1
          AND sub.active = 1
    	  GROUP BY s.staff_id
    	  ORDER BY RAND()
          LIMIT 0,3";

    $expertArray = $this->db->query($q);

    // init our columns
    $col_1 = "<div class=\"pure-u-1-2\">";
    $col_2 = "<div class=\"pure-u-1-2\">";    
    // the text that shows up in the blank box
    $bonus_text = "<br />" . _("Need help?  Ask an expert.");

    $row_count = 0;

    foreach ($expertArray as $key => $value) {

      $image = getHeadshot($value['email'], "smaller","staff_photo");
      $profile = "<div>" . $image . "<br />" . $value['fullname'] . "</div>";

      // this is to display two cols, col_1 with expert then text, col_2 with expert then expert
      if ($row_count % 2 == 0) {
        $col_2 .= $profile;
      } else {
        $col_1 .= $profile;
      }

      $row_count++;
    }

  
    $col_1 .= "$bonus_text</div>";
    $col_2 .= "</div>";

    $list_guides = "<div class=\"pure-g expert_list\">$col_1 $col_2</div>";

  return $list_guides;

  }

  static function getMenuName()
  {
    return _('Experts');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text googlescholar-text\">" . _("Experts") . "</span>";
        return $icon;
    }


}