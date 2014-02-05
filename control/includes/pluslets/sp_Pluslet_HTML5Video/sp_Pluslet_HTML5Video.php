<?php

/**
 *   @file sp_Pluslet_HTML5Video
 *   @brief 
 *
 *   @author agdarby, jlittle
 *   @date Dec 2013
 *   @todo 
 */
class sp_Pluslet_HTML5Video extends sp_Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  }

  public function output($action="", $view) {

    parent::establishView($view);

    if ($action == "edit") {

      // make an editable body and title type

      global $title_input_size; // alter size based on column
      //
      //////////////////////
      // New or Existing?
      //////////////////////

      if ($this->_pluslet_id) {
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_pluslet_name_field = "";
        $this->_title = "<input type=\"text\" class=\"required_field\" id=\"pluslet-update-title-$this->_pluslet_id\" value=\"$this->_title\" size=\"$title_input_size\" />";
        $this_instance = "pluslet-update-body-$this->_pluslet_id";
      } else {
        $new_id = rand(10000, 100000);
        $this->_pluslet_bonus_classes = "unsortable";
        $this->_pluslet_id_field = $new_id;
        $this->_pluslet_name_field = "new-pluslet-HTML5Video";
        $this->_title = "<input type=\"text\" class=\"required_field\" id=\"pluslet-new-title-$new_id\" name=\"new_pluslet_title\" value=\"$this->_title\" size=\"$title_input_size\" />";
        $this_instance = "pluslet-new-body-$new_id";
      }

      
      // Create and output object
     
      ob_start();
include 'views/test.html';
$view = ob_get_clean();

$this->_body = $view;


            parent::startPluslet();
            print $this->_body;
            parent::finishPluslet();


      
      return;
    } else {

      // notitle hack
      if (trim($this->_title) == "notitle") { $hide_titlebar = 1;} else {$hide_titlebar = 0;}

      // Look for tokens, tokenize
      parent::tokenizeText();

      parent::assemblePluslet($hide_titlebar);

      return $this->_pluslet;
    }
  }



}

?>
