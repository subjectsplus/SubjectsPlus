<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_3
 *   @brief The number corresponds to the ID in the database.  Numbered pluslets are UNEDITABLE clones
 * 	this one displays a Table of Contents style outline of all teh page's pluslets
 *
 *   @author agdarby
 *   @date Mar 2011
 *   @todo
 */
class Pluslet_6 extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id) {
        parent::__construct($pluslet_id, $flag, $subject_id);

        $this->_editable = FALSE;
        $this->_subject_id = $subject_id;
        $this->_pluslet_id = 6;
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_title = _("Guide Outline");
        $this->_pluslet_bonus_classes = "no_overflow";
    }

    public function output($action="", $view="public") {

        // public vs. admin
        parent::establishView($view);

        // Get librarians associated with this guide
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
				AND p.pluslet_id != '$this->_pluslet_id'
				ORDER BY ps.prow ASC";

        //print $qs;

        $tocArray = $querier->query($qs);

        if ($tocArray) {

            $total_rows = count($tocArray);
            $num_per_row = ceil($total_rows / 3);
            $row_count = 1;

            foreach ($tocArray as $value) {


                if ($row_count == "1" OR $row_count == 1 + $num_per_row OR $row_count == 1 + $num_per_row + $num_per_row) {
                    $this->_body .= "<div class=\"toc\">";
                }

                $this->_body .= "<a href=\"#box-$value[0]\" class=\"smaller\" id=\"boxid-$value[0]\">$value[1]</a><br />\n";

                if ($row_count == $num_per_row OR $row_count == $num_per_row * 2 or $row_count == $total_rows) {
                    $this->_body .= "</div>\n";
                }
                $row_count++;
            }

            $this->_body .= "";

        } else {
            $this->_body = _("There are no contents for this guide yet!");
        }

        parent::assemblePluslet();

        return $this->_pluslet;
    }

}

?>