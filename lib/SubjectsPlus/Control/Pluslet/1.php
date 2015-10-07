<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_1
 *   @brief The number corresponds to the ID in the database.  Numbered pluslets are UNEDITABLE clones
 * 	this one displays the All Items by Source
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo
 */
class Pluslet_1 extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id) {
        parent::__construct($pluslet_id, $flag, $subject_id);

        // Editable here is special; it leads to a popup window where you can
        // rearrange results.  Popup established in sp_Pluslet::establishView()
        $this->_editable = TRUE;

        $this->_pluslet_id = 1;

        // make sure it's a number and not something goofy
        if (is_numeric($subject_id)) {
            $this->_subject_id = $subject_id;
        } else {
            $this->_subject_id = 1;
        }

        $this->_title = _("All Items by Source");

        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
    }

    public function output($action="", $view="public") {

        // public vs. admin
        parent::establishView($view);

        global $proxyURL;
        global $check_this;
        global $IconPath;
        global $all_ctags;

        $last_source_id = "";

        /////////////////////////
        // Create TOC
        /////////////////////////

        $TOC = self::createTOC();

        $this->_body = $TOC;

    	$db = new Querier();

        // Display all items for a subject, as tagged in Records tab

        $q = "select title, description, location, source, source.source_id, restrictions, location.format,
		restrictions_id, title.title_id, location.access_restrictions, rank.subject_id,
		rank.title_id, location.helpguide, display_note, ctags, description_override, call_number
		FROM title, restrictions, location, location_title, source, rank
		WHERE title.title_id = location_title.title_id and location.location_id = location_title.location_id
		AND restrictions_id = access_restrictions and rank.subject_id = '$this->_subject_id' and rank.title_id = title.title_id
		AND source.source_id = rank.source_id
        ORDER BY source.rs asc, source.source, rank.rank asc, title.title, format";

        //print $q;

        $r = $db->query($q);

        // set up some row colours
        $row_count = 0;
        $colour1 = "oddrow";
        $colour2 = "evenrow";
        $results = ""; // init

        foreach ($r as $myrow) {


            $label = $myrow["title"];

            $url = $myrow["location"];
            $restrictions = $myrow["restrictions_id"];
            $display_note = $myrow["display_note"];

            // Use description override if it exists
            if ($myrow["description_override"] != "") {
                $blurb = $myrow["description_override"];
            } else {
                $blurb = $myrow["description"];
            }

            if (($restrictions == 2) OR ($restrictions == 3)) {
                // Check if it is restricted to local students
                $rest_icons = "restricted";
                $final_url = $proxyURL . $myrow[2];
                //$rest_icons = "<img src=\"$IconPath/lock.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Available to Students, Faculty and Staff\" title=\"Available to Students, Faculty and Staff\" /> ";
            } else {
                $rest_icons = "";
                $final_url = $myrow[2];
                //$rest_icons = "<img src=\"$IconPath/lock_unlock.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Unrestricted Resource\" title=\"Unrestricted Resource\" /> ";
            }

            // Generate our icons, comparing current ctags to the master list (in config.php)
            $pub_icons = "";
            $current_ctags = explode("|", $myrow["ctags"]);

            // add our $rest_icons info to this array at the beginning
            array_unshift($current_ctags, $rest_icons);

            $pub_icons = showIcons($current_ctags);

            //Check if there is a display note

            if ($display_note == NULL) {
                $display_note_text = "";
            } else {
                $display_note_text = "<strong>note: </strong>$display_note";
            }

            $row_colour = ($row_count % 2) ? $colour1 : $colour2;

            // Do we need a subdivision header?
            $this_source_id = $myrow[4];

            if ($this_source_id != $last_source_id) {
                $results .= "<p class=\"leftcolheader\"><a name=\"$myrow[4]\"></a>$myrow[3]</p>\n\n";
                $last_source_id = $this_source_id;
                $res_class = "dbresults no-border";
            } else {
                $res_class = "dbresults";
            }

            // clean up blurb
            $blurb = stripslashes($blurb) . "<br />";

            // let's set our targets
            $target = targetBlanker();
            // Display results


            switch ($myrow["format"]) {
                case "1": // web
                    $first_line = "<a style=\"text-decoration: underline\" href=\"$final_url\" $target>$label</a> $pub_icons<br />";

                    break;
                case "2": // print
                    $first_line = "<em>$label</em><br /><strong>" . _("Print Location:") . "</strong> $final_url $pub_icons<br />";
                    if ($myrow["8"] == $last_title_id) {
                        $blurb = "";
                        $res_class = "dbresults-inset";
                    }

                    break;

                case "3": // print with url
                    $first_line = "<em>$label</em><br /><strong>" . _("Print Location:") . "</strong>
                    <a style=\"text-decoration: underline\" href=\"$final_url\" $target>" . $myrow["call_number"] . "</a> $pub_icons<br />";
                    if ($myrow["8"] == $last_title_id) {
                        $blurb = "";
                        $res_class = "dbresults-inset";
                    }

                    break;
            }

            $results .= "<div class=\"$res_class\">
                $first_line
                $blurb
                $display_note_text
            </div>";


            $last_title_id = $myrow["8"];
            $row_count++; // Add 1 to the row count, for the "even/odd" row striping
        }


        $this->_body .= $results;

        if ($view =="admin") {
            
            $this->_body = "<p class=\"admin-prompt\"><i class=\"fa fa-list\"></i> <a class=\"showmedium\" href=\"manage_items.php?subject_id=$this->_subject_id&amp;wintype=pop\">" . _("Organize Resources") . "</a></p>";
            $this->_body .= $results;
        }

        

        parent::assemblePluslet();

        return $this->_pluslet;
    }

    public function createTOC() {
        $toc = "<p class=\"clearboth\" font-size: 10px; text-align: center;\">Table of Contents</p>";
        $toc = "";

    	$db = new Querier();

        $source_string = "select distinct source, source.source_id
		FROM title, restrictions, location, location_title, source, rank
		WHERE title.title_id = location_title.title_id and location.location_id = location_title.location_id
		AND restrictions_id = access_restrictions and rank.subject_id = '$this->_subject_id' and rank.title_id = title.title_id
		AND source.source_id = rank.source_id
        ORDER BY source.source asc";

        //print $source_string;

        $source_result = $db->query($source_string);
        $total_rows = count($source_result);

        $num_per_row = ceil($total_rows / 3);

        $row_count = 1;

        foreach ($source_result as $myrow) {


            $source_id = $myrow["1"];
            $source_name = $myrow["0"];

            if ($row_count == "1" OR $row_count == 1 + $num_per_row OR $row_count == 1 + $num_per_row + $num_per_row) {
                $toc .= "<div style=\"float: left; width: 33%\" class=\"toc1\">";
            }

            $toc .= "<a href=\"#$source_id\" class=\"smaller\">$source_name</a><br />\n";

            if ($row_count == $num_per_row OR $row_count == $num_per_row * 2 or $row_count == $total_rows) {
                $toc .= "</div>\n";
            }
            $row_count++;
        }

        $toc .= "<br class=\"clearboth\"\" />";
        //$toc .= "<br />";
        return $toc;
    }
    
    
      static function getMenuName()
  {
  	return _('All Items by Source');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text worldcat-text\">" . _("All Items / Source") . "</span>";
        return $icon;
    }

}

?>
