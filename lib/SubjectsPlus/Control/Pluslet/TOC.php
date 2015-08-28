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

  protected $_ticked_items = array();

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

    //$this->_editable = TRUE;
    $this->_subject_id = $subject_id;
    $this->_pluslet_bonus_classes = "no_overflow";
  }

  static function getMenuName()
  {
	return _('Table of Contents');
  }

  static function getMenuIcon()
    {
      $icon="<i class=\"fa fa-list-alt\" title=\"" . _("Table of Contents") . "\" ></i><span class=\"icon-text\">" . _("TOC") . "</span>";
        return $icon;
    }

  public function output($action="", $view="public") {

    global $title_input_size; // alter size based on column

    // Get pluslets associated with this
    $querier = new Querier();
  	$qs = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra,t.tab_index
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
        $this->_title = "<input type=\"text\" class=\"\" id=\"pluslet-update-title-$this->_current_id\" value=\"$this->_title\" size=\"$title_input_size\" />";
        $this_instance = "pluslet-update-body-$this->_pluslet_id";
      } else {
        $new_id = rand(10000, 100000);
        $this->_current_id = $new_id;
        $this->_pluslet_bonus_classes = "unsortable no_overflow";
        $this->_pluslet_id_field = $new_id;
        $this->_pluslet_name_field = "new-pluslet-TOC";
        $this->_title = "<input type=\"text\" class=\"\" id=\"pluslet-new-title-$new_id\" name=\"new_pluslet_title\" value=\"" . ("Table of Contents") . "\" size=\"$title_input_size\" />";
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
      if (!isset( $this->_hide_titlebar ))
      {
      	if(trim($this->_title) == "notitle") { $this->_hide_titlebar = 1;} else {$this->_hide_titlebar = 0;}
      }

      parent::assemblePluslet($this->_hide_titlebar);

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
            $left_col .= "$checkbox <a href=\"#box-$value[0]\" class=\"table-of-contents-edit\" id=\"boxid-$value[6]-$value[0]\">$value[1]</a><br />\n";
          } else {
            $right_col .= "$checkbox <a href=\"#box-$value[0]\" class=\"table-of-contents-edit\" id=\"boxid-$value[6]-$value[0]\">$value[1]</a><br />\n";
          }
        }
      } else {
        // View
        // display only ticked items
        if ($this->_ticked_items) {
          foreach ($this->_tocArray as $value) {
            if (in_array($value[0], $this->_ticked_items)) {
              if ($value[3] == 1) {
                $left_col .= "<a href=\"#box-$value[0]\" class=\"table-of-contents\" id=\"boxid-$value[6]-$value[0]\">$value[1]</a>\n";
              } else {
                $right_col .= "<a href=\"#box-$value[0]\" class=\"table-of-contents\" id=\"boxid-$value[6]-$value[0]\">$value[1]</a>\n";
              }
            }
          }
        }else
        {
          return 'No items ticked. Please edit.';
        }
      }


      $this->_body .= "<div class=\"pure-g\"><div class=\"pure-u-1-2\">$left_col</div>
                <div class=\"pure-u-1-2\">$right_col</div></div>";

    } else {
      $this->_body = _("There are no contents for this guide yet!");
    }
  }

  public static function getCkPluginName()
  {
	return 'subsplus_toc';
  }

  public function setTickedItems( $lobjTicked )
  {
  	if( is_array( $lobjTicked ) )
  	{
  		$this->_ticked_items = $lobjTicked;
  	}
  }

}

?>