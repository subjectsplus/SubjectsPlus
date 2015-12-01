<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_3
 *   @brief The number corresponds to the ID in the database.  Numbered pluslets are UNEDITABLE clones
 *		this one displays the Subject Specialist
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo
 */


class Pluslet_3 extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id) {
      parent::__construct($pluslet_id, $flag, $subject_id);

      $this->_editable = FALSE;
      $this->_subject_id = $subject_id;
      $this->_pluslet_id = 3;
      $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
      $this->_title = _("Subject Specialist");

  }


  public function output($action="", $view="public") {
    global $tel_prefix;
    // public vs. admin
    parent::establishView($view);

    // Get librarians associated with this guide
    $querier = new Querier();
    $qs = "SELECT lname, fname, email, tel, title from staff s, staff_subject ss WHERE s.staff_id = ss.staff_id and ss.subject_id = " . $this->_subject_id . " ORDER BY lname, fname";

    //print $qs;

    $staffArray = $querier->query($qs);

    foreach ($staffArray as $value) {

        // get username from email
        $truncated_email = explode("@", $value[2]);

        $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

        // Output Picture and Contact Info
        $this->_body .= "
        <div class=\"subjectSpecialistPlusletOld\">
          <div class=\"staff-image\"><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" /></div>
          <div class=\"staff-content\"><p><a href=\"mailto:$value[2]\">$value[1] $value[0]</a><br />$value[4]<br />
             Tel: $tel_prefix $value[3]</p></div>
        </div>\n";
    }


    parent::assemblePluslet();

    return $this->_pluslet;

	}

	public static function getCkPluginName()
	{
		return 'subsplus_sub_spe';
	}
	
	
	static function getMenuName()
  {
  	return _('Subject Specialist');
  }

  static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-male\" title=\"" . _("Subject Specialists") . "\" ></i><span class=\"icon-text\">" . _("Subject Specialists") ."</span>";
        return $icon;
    }

}

?>
