<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_Heading
 *   @brief 
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo 
 */
class Pluslet_Heading extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_pluslet_bonus_classes = "type-heading";

        $this->_body = "";
    }

    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-header\" title=\"" . _("Heading") . "\" ></i><span class=\"icon-text\">" . _("Heading") . "</span>";
        return $icon;
    }

    public function output($action="", $view="public") {
        
        global $title_input_size;

        // Public vs. Private
        parent::establishView($view);

        if ($action == "edit") {

            if ($this->_pluslet_id) {
                $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
                $this->_pluslet_name_field = "";
                $this->_title = "<input type=\"text\" class=\"required_field edit-input\" id=\"pluslet-update-title-$this->_pluslet_id\" value=\"$this->_title\" size=\"$title_input_size\" />";
            } else {
                $new_id = rand(10000, 100000);
                $this->_pluslet_bonus_classes = "unsortable";
                $this->_pluslet_id_field = $new_id;
                $this->_pluslet_name_field = "new-pluslet-Heading";
                $this->_title = "<input type=\"text\" class=\"required_field edit-input\" id=\"pluslet-new-title-$new_id\" name=\"new_pluslet_title\" value=\"$this->_title\" size=\"$title_input_size\" />";
            }

            global $title_input_size; // alter size based on column


            parent::startPluslet();
            print $this->_body;
            parent::finishPluslet();

            return;
        } else {

            parent::assemblePluslet();

            return $this->_pluslet;
        }
    }

}

?>