<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_2
 *   @brief The number corresponds to the ID in the database.  Numbered pluslets are UNEDITABLE clones
 * 		this one displays the the Key to Icons
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo 
 */
class Pluslet_2 extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id) {
        parent::__construct($pluslet_id, $flag, $subject_id);

        $this->_editable = FALSE;
        $this->_subject_id = $subject_id;
        $this->_pluslet_id = 2;
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_title = _("Key to Icons");

    }

    public function output($action="", $view="public") {

        global $IconPath;
        global $all_ctags;
        // public vs. admin
        parent::establishView($view);

        // Add restriction icon to beginning of array (if not present)
        if (!in_array("restricted", $all_ctags)) {
            array_unshift($all_ctags, "restricted"); 
        }
       

        // Output the icons via showIcons function
        $this->_body .= "<p class=\"smaller\" style=\"margin-left: 5px;\">";
        $this->_body .= showIcons($all_ctags, 1);
        $this->_body .= "</p>";

        parent::assemblePluslet();

        return $this->_pluslet;
    }
    
    
      static function getMenuName()
  {
  	return _('Key to Icons');
  }

  static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-info\" title=\"" . _("Key to Icons") . "\" ></i><span class=\"icon-text\">" . _("Key to Icons") . "</span>";
        return $icon;
    }
 
  


}



?>
