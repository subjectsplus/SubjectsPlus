<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_TOC
 *   @brief
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo
 */
class Pluslet_TOC extends Pluslet {

	protected $_ticked_items = "";

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

    $this->_editable = TRUE;
    $this->_subject_id = $subject_id;
    $this->_pluslet_bonus_classes = "no_overflow";
  }

  static function getMenuName()
  {
	return _('Table of Contents');
  }

  public function output($action="", $view="public") {

    global $title_input_size; // alter size based on column

    // Get pluslets associated with this
    $querier = new Querier();
  	$qs = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra
			FROM pluslet p INNER JOIN pluslet_section ps
			ON p.pluslet_id = ps.pluslet_id
			INNER JOIN section sec
			ON ps.section_id = sec.section_id
			INNER JOIN tab t
			ON sec.tab_id = t.tab_id
			INNER JOIN subject s
			ON t.subject_id = s.subject_id
			WHERE s.subject_id = '$this->_subject_id'
			AND p.type != 'TOC'
			ORDER BY ps.prow ASC";

    //print $qs;

    $this->_tocArray = $querier->query($qs);

    // public vs. admin
    parent::establishView($view);

    if ($this->_extra != "") {

      $jobj = json_decode($this->_extra);
      $this->_ticked_items = explode(',', $jobj->{'ticked'});
    }


    if ($action == "edit") {

      //////////////////////
      // New or Existing?
      //////////////////////

      if ($this->_pluslet_id) {
        $this->_current_id = $this->_pluslet_id;
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_pluslet_name_field = "";
        $this->_title = "<input type=\"text\" class=\"required_field\" id=\"pluslet-update-title-$this->_current_id\" value=\"$this->_title\" size=\"$title_input_size\" />";
        $this_instance = "pluslet-update-body-$this->_pluslet_id";
      } else {
        $new_id = rand(10000, 100000);
        $this->_current_id = $new_id;
        $this->_pluslet_bonus_classes = "unsortable no_overflow";
        $this->_pluslet_id_field = $new_id;
        $this->_pluslet_name_field = "new-pluslet-TOC";
        $this->_title = "<input type=\"text\" class=\"required_field\" id=\"pluslet-new-title-$new_id\" name=\"new_pluslet_title\" value=\"" . ("Table of Contents") . "\" size=\"$title_input_size\" />";
        $this_instance = "pluslet-new-body-$new_id";
      }




      self::generateTOC($action);

      parent::startPluslet();
      print $this->_body;
      parent::finishPluslet();

      return;
    } else {
      // Note we hide the Feed parameters in the name field

      self::generateTOC($action);

      // notitle hack
      if (trim($this->_title) == "notitle") { $hide_titlebar = 1;} else {$hide_titlebar = 0;}

      parent::assemblePluslet($hide_titlebar);

      return $this->_pluslet;
    }
  }

  function generateTOC($action) {
    $left_col = "";
    $right_col = "";

    if ($this->_tocArray) {

      // Edit
      if ($action == "edit") {

        foreach ($this->_tocArray as $value) {

          if (isset($this->_ticked_items)) {
            // show ticked items as pre-ticked
            if (!in_array($value[0], $this->_ticked_items)) {
              $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$value[0]\" />";
            } else {
              $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$value[0]\" checked=\"checked\" />";
            }
          } else {
            $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$value[0]\" checked=\"checked\" />";
          }

          if ($value[3] == 1) {
            $left_col .= "$checkbox <a href=\"#box-$value[0]\" class=\"table-of-contents smaller\" id=\"boxid-$value[0]\">$value[1]</a><br />\n";
          } else {
            $right_col .= "$checkbox <a href=\"#box-$value[0]\" class=\"table-of-contents smaller\" id=\"boxid-$value[0]\">$value[1]</a><br />\n";
          }
        }
      } else {

        // View
        // display only ticked items
        if ($this->_ticked_items) {
          foreach ($this->_tocArray as $value) {
            if (in_array($value[0], $this->_ticked_items)) {
              if ($value[3] == 1) {
                $left_col .= "<a href=\"#box-$value[0]\" class=\"table-of-contents smaller\" id=\"boxid-$value[0]\">$value[1]</a><br />\n";
              } else {
                $right_col .= "<a href=\"#box-$value[0]\" class=\"table-of-contents smaller\" id=\"boxid-$value[0]\">$value[1]</a><br />\n";
              }
            }
          }
        }else
        {
        	return 'No items ticked. Please edit.';
        }
      }

      $this->_body .= "<div style=\"float: left; margin-right: 2em; overflow: none;\">$left_col</div>
                <div class=\"float-left\">$right_col</div>";
    } else {
      $this->_body = _("There are no contents for this guide yet!");
    }
  }

}

?>