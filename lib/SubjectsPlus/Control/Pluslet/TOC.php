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
  	$qs = "SELECT p.pluslet_id AS id, p.title, p.body, ps.pcolumn, p.type, p.extra,t.tab_index AS parent_id, t.label AS name
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
        $this->_pluslet_bonus_classes = "type-toc ";
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_pluslet_name_field = "";
        $this->_title = "<input type=\"text\" class=\"\" id=\"pluslet-update-title-$this->_current_id\" value=\"$this->_title\" size=\"$title_input_size\" />";
        $this_instance = "pluslet-update-body-$this->_pluslet_id";
      } else {
        $new_id = rand(10000, 100000);
        $this->_current_id = $new_id;
        $this->_pluslet_bonus_classes = "type-toc unsortable no_overflow";
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


  function buildTree(array $source, $parent_id = 0) {

    $items = array();
    foreach($source as $item):

      if($item['parent_id'] == $parent_id) {

        $items['parent'] = array( 'parent_id' => $item['parent_id'], "name" => $item['name']);
        $items['children'][] =  array( "id" => $item['id'], "title" => $item['title'], "pcolumn" => $item['pcolumn'], "type" => $item['type']);

      }

    endforeach;

    return $items;
  }

  function generateTOC($action) {
    if ($this->_tocArray) {

      $tab_index_array = array();
      foreach($this->_tocArray as $tab):
        $tab_index_array[] = $tab['parent_id'];
      endforeach;

      $tab_index_array =  array_unique($tab_index_array );

      $items = array();
      foreach($tab_index_array as $tab):
        $items[] = $this->buildTree($this->_tocArray, $tab);
      endforeach;

      foreach($items as $item):

        if( !empty($item)) {



           // $this->_body .= "<h4 class=\"toc-heading\">". $item['parent']['name'] . "</h4>";


          $parent_id = $item['parent']['parent_id'];
          $parent_name = $item['parent']['name'];

          // Edit
          if ($action == "edit") {

            if (isset($this->_ticked_items)) {
              // show ticked items as pre-ticked
              if (!in_array($parent_id, $this->_ticked_items)) {
                $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$parent_id\" />";
              } else {
                $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$parent_id\" checked=\"checked\" />";
              }
            } else {
              $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$parent_id\" checked=\"checked\" />";
            }


              $this->_body .= "<h4 href=\"#box-$parent_id\" class=\"table-of-contents-header\" id=\"boxid-$parent_id\">$checkbox $parent_name</h4>";



                foreach( $item['children'] as $child):
                  $child_title = $child['title'];

                  $child_id = $child['id'];
                  $p_column = $child['pcolumn'];



                  if (isset($this->_ticked_items)) {
                    // show ticked items as pre-ticked
                    if (!in_array($child_id, $this->_ticked_items)) {
                      $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$child_id\" />";
                    } else {
                      $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$child_id\" checked=\"checked\" />";
                    }
                  } else {
                    $checkbox = "<input type=\"checkbox\" name=\"checkbox-$this->_current_id\" value=\"$child_id\" checked=\"checked\" />";
                  }
                  if ($p_column == 1) {
                    $this->_body .= "$checkbox <a href=\"#box-$child_id\" class=\"table-of-contents-edit\" id=\"boxid-$parent_id-$child_id\">$child_title</a><br />\n";
                  } else {
                    $this->_body .= "$checkbox <a href=\"#box-$child_id\" class=\"table-of-contents-edit\" id=\"boxid-$parent_id-$child_id\">$child_title</a><br />\n";
                  }

                endforeach;

          } else {
            // View
            // display only ticked items
            if ($this->_ticked_items) {
              if (in_array($parent_id, $this->_ticked_items)) {

                $this->_body .= "<h4 class=\"table-of-contents-header\">$parent_name</h4>\n";

              }

              foreach ($item['children'] as $child) {

                $child_title = $child['title'];
                $child_id = $child['id'];

                if (in_array($child_id, $this->_ticked_items)) {

                 $this->_body .= "<a href=\"#box-$child_id\"
                                     class=\"table-of-contents\"
                                     data-tab_index=\"$parent_id\"
                                     data-pluslet_id=\"$child_id\"
                                     id=\"boxid-$parent_id-$child_id\">$child_title</a>\n";

                }
              }
            }else
            {
              return 'No items ticked. Please edit.';
            }
          }

        }

      endforeach;

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
