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
          AND ptags LIKE '%librarian%'
    	    GROUP BY s.staff_id
    	    ORDER BY RAND()
          LIMIT 0,4";

    $expertArray = $this->db->query($q);

    // init list item
    $expert_item = "";

    // additional text
    $bonus_text = _("Need help?  Ask an expert.");

    // additional text - button 
    $button_text = _("See all experts");  


    foreach ($expertArray as $key => $value) {

      $exp_image = getHeadshotFull($value['email']);

      $librarian_email = $value['email'];
      $name_id = explode("@", $librarian_email);

      $exp_profile = "<li><div class=\"expert-img\">" . $exp_image . "</div><div class=\"expert-label\"><a href=\"" . PATH_TO_SP . "subjects/staff_details.php?name=" . $name_id[0] . "\">" . $value['fullname'] . "</a><br /><div class=\"expert-subject-min\">" . $value['subject'] . "</div></div><div class=\"expert-tooltip\" id=\"tooltip-" . $name_id[0] . "\"><div class=\"expert-title\">" . $value['title'] ."</div><div class=\"expert-subjects\"><strong>Subjects:</strong> " . $value['subject'] ." ...</div></div></li>";

      $expert_item .= $exp_profile;     
    }

    $guide_experts = "$expert_item";

    $list_guide_experts = "<div class=\"find-expert-area-circ\">$bonus_text<br /><ul class=\"expert-list-circ\">$guide_experts</ul><div class=\"expert-btn-area\"><a href=\"" . PATH_TO_SP ."subjects/staff.php?letter=Subject Librarians A-Z\" class=\"expert-button\">" . $button_text . "</a></div></div>";

  return $list_guide_experts;

  }

  static function getMenuName()
  {
    return _('Experts');
  }

  static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-users\" title=\"" . _("Experts") . "\" ></i><span class=\"icon-text\">"  . _("Experts") . "</span>";
        return $icon;
    }


}