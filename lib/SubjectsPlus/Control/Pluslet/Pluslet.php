<?php
namespace SubjectsPlus\Control;
/**
 *   @file sp_Guide
 *   @brief manage guide metadata
 *
 *   @author agdarby, rgilmour, dgonzalez
 *   @date Jan 2011
 *   @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */
class Pluslet {

    protected $_pluslet_id;
    protected $_title;
    protected $_body;
    protected $_clone;
    protected $_type;
    protected $_extra;
    protected $_pluslet_bonus_classes = "";
    protected $_pluslet_id_field = "";
    protected $_pluslet_name_field = "";
    protected $_pluslet_body_bonus_classes = "";
    protected $_editable = "";
    protected $_icons = "";
    // added v 3
    protected $_hide_titlebar;
    protected $_collapse_body;
    protected $_titlebar_styling;
    protected $_debug;

    protected $_pluslet;

    // added v4
    protected $_favorite_box;
    protected $_target_blank_links;

    public function __construct($pluslet_id="", $flag="", $subject_id = "", $isclone = 0) {

        $this->_pluslet_id = $pluslet_id;

        if (!$this->_pluslet_id) {
            return;
        }

        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id; // default css id of item

        $this->_subject_id = $subject_id;
        $this->_isclone = $isclone;

        // By default we make pluslets editable (in admin), this is overriden in some pluslet types
        $this->_editable = TRUE;

        /////////////
        // Get pluslet table info
        /////////////

        $querier = new Querier();
        $q1 = "SELECT pluslet_id, title, body, clone, type, extra, hide_titlebar, collapse_body, titlebar_styling, favorite_box, target_blank_links
        FROM pluslet WHERE pluslet_id = " . $this->_pluslet_id;


        $plusletArray = $querier->query($q1);

        $this->_debug .= "<p>Pluslet query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($plusletArray == FALSE) {
            $this->_message = _("There is no box with that ID.  Why not create a new one?");
        } else {
            $this->_title = $plusletArray[0]["title"];
            $this->_body = $plusletArray[0]["body"];
            $this->_clone = $plusletArray[0]["clone"];
            $this->_type = $plusletArray[0]["type"];
            $this->_extra = $plusletArray[0]["extra"];
            $this->_hide_titlebar = $plusletArray[0]["hide_titlebar"];
            $this->_collapse_body = $plusletArray[0]["collapse_body"];
            $this->_titlebar_styling = $plusletArray[0]["titlebar_styling"];


            $this->_favorite_box = $plusletArray[0]["favorite_box"];
            $this->_target_blank_links = $plusletArray[0]["target_blank_links"];

        }


        // Now if it's a clone, empty out the pluslet_id
        if ($this->_isclone == 1) {
            $this->_pluslet_id = "";
        }


    }

    protected function establishView($view) {
        global $IconPath;

        switch ($view) {

            case "admin":
                $delete_text = _("Remove item from this guide");


                //If type is NOT Special
                if(strtolower($this->_type) != 'special')
                {
                    $this->_icons = "";
                }

                // If editable, use gear icon to access edit mode and settings
                if ($this->_editable == TRUE) {
                    // Deal with All Items by Source type (#1 in db)
                    if ($this->_pluslet_id == 1) {
                        $this->_icons .= "<a id=\"delete-$this->_pluslet_id\"><i class=\"fa fa-trash-o\" title=\"$delete_text\" /></i></a>";
                    } else {
                        $this->_icons .= "<a id=\"edit-$this->_pluslet_id-$this->_type\"><i class=\"fa fa-cog\" title=\"" . _("Edit & Box Settings") . "\" /></i></a>";
                    }
                }
                else {

                    //If not editable, show only delete icon
                    $this->_icons .= "<a id=\"delete-$this->_pluslet_id\"><i class=\"fa fa-trash-o\" title=\"$delete_text\" /></i></a>";

                }

                // Show the item id --it's handy for debugging
                $this->_visible_id = "$this->_pluslet_id";

                // get our relative path to the legacy fixed_pluslet folder
                $this->_relative_asset_path = "../../assets/";

                break;
            default:
                // Default is the public view
                // don't show the item id
                $this->_visible_id = "";

                // get our relative path to the legacy fixed_pluslet folder
                // I don't know why this needs to be relative, but this conks out the location of the subject specialists otherwise (if mod_rewrite is used)

                if (strpos($_SERVER['REQUEST_URI'], "guide.php") !== false ) {
                    $this->_relative_asset_path = "../../assets/";
                } else {
                    $this->_relative_asset_path = "../assets/";
                }

                break;
        }
    }

    protected function assemblePluslet($hide_titlebar=0) {
        global $IconPath;

        // if we're using a simple pluslet, things are diff
        // we use $this->_visible_id to make sure this is only on the frontend


        //when TITLEBAR is HIDDEN - PV
        if (($hide_titlebar == 1 && $this->_visible_id == "" || $this->_title == "" && $this->_visible_id == "")) {
	        $this->_pluslet_name_field = empty($this->_pluslet_name_field) ? $this->_type : "settings-{$this->_pluslet_name_field}";
            $this->_pluslet .= "
            <div id=\"$this->_pluslet_id_field\" class=\"pluslet_simple no_overflow $this->_pluslet_id_field\" name=\"$this->_pluslet_name_field\"><a name=\"box-" . $this->_pluslet_id . "\"></a>";

            if($this->_target_blank_links == 1) {
                $this->_pluslet_body_bonus_classes .= "target_blank_links";
            }

            // since we're here, let's see if the body should be collapsed
            if ($this->_collapse_body == 1) {
                $this->_pluslet_body_bonus_classes .= "noshow";
            }

            if ($this->_body != "") {
                $this->_pluslet .= "<div class=\"pluslet_body $this->_pluslet_body_bonus_classes\">
                            " . htmlspecialchars_decode($this->_body) . "
                    </div>";
            }




            // this div closed outside of if/else

        } else {
            //when TITLEBAR is SHOWN - PV

            $this->_pluslet_name_field = empty($this->_pluslet_name_field) ? $this->_type : "settings-{$this->_pluslet_name_field}";
            $this->_pluslet .= "<div class=\"pluslet $this->_pluslet_bonus_classes $this->_pluslet_id_field\" id=\"$this->_pluslet_id_field\" name=\"$this->_pluslet_name_field\">
			     <a name=\"box-" . $this->_pluslet_id . "\"></a>";


            if ($this->_visible_id != "") {
                $this->_pluslet .= self::boxSettings(); // add in our hidden div full of box config options
            }

            $this->_pluslet .= "<div class=\"titlebar pluslet_sort\">";


            if($this->_target_blank_links == 1) {
                $this->_pluslet_body_bonus_classes .= "target_blank_links";
            }

            //if public view, add selected style
            if( $this->_visible_id != '' ) {
                $this->_pluslet .= "<div class=\"titlebar_text\">$this->_title</div>";
            }else
            {
                $this->_pluslet .= "<div class=\"titlebar_text {$this->_titlebar_styling}\">$this->_title</div>";

                // since we're here, let's see if the body should be collapsed
                if ($this->_collapse_body == 1) {
                    $this->_pluslet_body_bonus_classes .= "noshow";
                }


            }

            //only if on admin side, display sort icon
            if( $this->_visible_id != '' ) {
                $this->_pluslet .= "\n<div class=\"titlebar_options\">$this->_icons</div>";
            }


            $this->_pluslet .= "</div>";

            if ($this->_body != "") {
                $this->_pluslet .= "<div class=\"pluslet_body $this->_pluslet_body_bonus_classes\">
                            " . htmlspecialchars_decode($this->_body) . "
                    </div>";
            }

        }



        $this->_pluslet .= "</div>";
    }

    ///////////////////
    // The next two functions are for when the pluslet needs to appear live
    // without being stored in a variable, for instance, when using ckeditor
    ///////////////////

    protected function startPluslet() {

        echo "
        <div class=\"pluslet $this->_pluslet_bonus_classes\" id=\"$this->_pluslet_id_field\" name=\"$this->_pluslet_name_field\">";

        echo self::boxSettings(); // add in our hidden div full of box config options

        echo "
            <div class=\"titlebar pure-g\">
                <div class=\"titlebar_text pure-u-2-3\">".stripslashes($this->_title)."</div>
                <div class=\"titlebar_options pure-u-1-3\">$this->_icons</div>
            </div>
            <div class=\"pluslet_body $this->_pluslet_body_bonus_classes\">";

    }

    protected function finishPluslet() {

        echo "</div>
            </div>";
    }

    protected function boxSettings() {

        global $titlebar_styles;

        global $IconPath;

        // generate our titlebar styles
        $tb_styles = "";
        $helptext = _("Help: About this box");

        foreach ($titlebar_styles as $key => $value) {
            $tb_styles .= "<option value=\"$value\" style=\"$value\"";
            if ($this->_titlebar_styling == $value) { $tb_styles .= " selected";}
            $tb_styles .= ">$value</option>";
        }

        $box_settings = "<div class=\"box_settings pure-u-1\">
             <div class=\"pure-g\">
                <div class=\"pure-u-1-2\"><a class=\"close-settings\"><i class=\"fa fa-times\" title=\"" . _("Close Settings Panel") . "\" /></i></a></div>
                <div class=\"pure-u-1-2 delete-trigger\"><a id=\"delete-$this->_pluslet_id\"><i class=\"fa fa-trash-o\" title=\"" . _("Remove item from this guide") . "\" /></i></a></div>
            </div>

            <form class=\"pure-form box-settings-form\">               
            <h3>" . _("Settings") . "</h3>
                <div class=\"onoffswitch titlebar_set\">
                        <input type=\"checkbox\" class=\"onoffswitch-checkbox\" id=\"notitle-$this->_pluslet_id\"";

        if ($this->_hide_titlebar == 1) {$box_settings .= " checked";}

        $box_settings .= ">
                        <label class=\"onoffswitch-label\" for=\"notitle-$this->_pluslet_id\">
                            <span class=\"onoffswitch-inner\"></span>
                            <span class=\"onoffswitch-switch\"></span>
                        </label>";

        $box_settings .= "<span class=\"settings-label-text\">" . _("Hide Titlebar") . "</span>
                </div>

                <div class=\"onoffswitch body_set\">
                        <input type=\"checkbox\" class=\"onoffswitch-checkbox\" id=\"start-collapsed-$this->_pluslet_id\"";

        if ($this->_collapse_body == 1) {$box_settings .= " checked";}

        $box_settings .= ">
                        <label class=\"onoffswitch-label\" for=\"start-collapsed-$this->_pluslet_id\">
                            <span class=\"onoffswitch-inner\"></span>
                            <span class=\"onoffswitch-switch\"></span>
                        </label>";

        $box_settings .= "<span class=\"settings-label-text\">" . _("Hide Box Content") . "</span>
                </div>


                <div class=\"onoffswitch fav_set\">
                        <input type=\"checkbox\" class=\"onoffswitch-checkbox favorite_pluslet_input\" id=\"favorite_box-$this->_pluslet_id\"";

        if ($this->_favorite_box == 1) {$box_settings .= " checked";}

        $box_settings .= ">
                        <label class=\"onoffswitch-label\" for=\"favorite_box-$this->_pluslet_id\">
                            <span class=\"onoffswitch-inner\"></span>
                            <span class=\"onoffswitch-switch\"></span>
                        </label>";

        $box_settings .= "<span class=\"settings-label-text\">" . _("Favorite Box") . "</span>
                </div>

                <div class=\"onoffswitch links_set\">
                        <input type=\"checkbox\" class=\"onoffswitch-checkbox target_blank_links\" id=\"target_blank_links-$this->_pluslet_id\"";

        if ($this->_target_blank_links == 1) {$box_settings .= " checked";}

        $box_settings .= ">
                        <label class=\"onoffswitch-label\" for=\"target_blank_links-$this->_pluslet_id\">
                            <span class=\"onoffswitch-inner\"></span>
                            <span class=\"onoffswitch-switch\"></span>
                        </label>";

        $box_settings .= "<span class=\"settings-label-text\">" . _("Open All Links in New Tab") . "</span>
                </div>
                <h3>" . _("Styles") . "</h3>
                
                <div class=\"titlebar-styling-section\">
                        <div class=\"titlebar-styling-select\">
                            <select id=\"titlebar-styling-$this->_pluslet_id\" class=\"box-setting-select\">
                                $tb_styles
                            </select>
                        </div>   
                                  
                        <div class=\"titlebar-styling-label\">
                           <label for=\"titlebar-styling-$this->_pluslet_id\"></label>
                        </div>
                </div> 
            </form>
            <div class=\"pure-g pluslet-metadata\">
                <div class=\"pure-u-1-2 pluslet_id\">ID $this->_visible_id</div>
                <div class=\"pure-u-1-2 pluslet-help\"><img src=\"$IconPath/info-circle.png\" border=\"0\" title=\"$helptext\" class=\"help-$this->_type\" alt=\"" . _("help") . "\" /></img></div>
            </div>            
            </div>";

        return $box_settings;

    }

    protected function tokenizeText() {
        global $proxyURL;
        global $PublicPath;
        global $FAQPath;
        global $UserPath;
        global $IconPath;
        global $open_string;
        global $close_string;
        global $open_string_kw;
        global $close_string_kw;
        global $open_string_cn;
        global $close_string_cn;
        global $open_string_bib;

        $db = new Querier();

        $icons = "";
        //$target = "target=\"_" . $target . "\"";
        $target = "";
        $target = targetBlanker();
        $tokenized = "";

        $parts = preg_split('/<span[^>]*>{{|}}<\/span>/', $this->_body);

        if( count($parts) == 1 )
            $parts = preg_split('/{{|}}/', $this->_body);

        if (count($parts) > 1) { // there are tokens in $body
            foreach ($parts as $part) {



                if (
                    preg_match('/^dab},\s?{\d+},\s?{.+},\s?{[01]{3}$/', $part)
                    || preg_match('/^dab},\s?{\d+},\s?{.+},\s?{[01]{2}$/', $part)
                    || preg_match('/^faq},\s?{(\d+,)*\d+$/', $part)
                    || preg_match('/^cat},\s?{.+},\s?{.*},\s?{\w+$/', $part)
                    || preg_match('/^fil},\s?{.+},\s?{.+$/', $part)
                    || preg_match('/^sss},\s?{[^}]*/', $part)
                    || preg_match('/^toc},\s?{[^}]*/', $part) ) { // $part is a properly formed token
                    $fields = preg_split('/},\s?{/', $part);
                    $prefix = substr($part, 0, 3);

                    //print_r($fields);

                    switch ($prefix) {
                        case "faq":
                            $query = "SELECT faq_id, question FROM `faq` WHERE faq_id IN(" . $fields[1] . ") ORDER BY question";
                            $result = $db->query($query);
                            $tokenized.= "<ul>";
                            foreach ($result as $myrow) {
                                $tokenized.= "<li><a href=\"$FAQPath" . "?faq_id=$myrow[0]\" $target>" . stripslashes(htmlspecialchars_decode($myrow[1])) . "</a></li>";
                            }
                            $tokenized.= "</ul>";
                            break;
                        case "fil":
                            $ext = explode(".", $fields[1]);
                            $i = count($ext)-1;
                            $our_icon = showDocIcon($ext[$i]);
                            $file = "$UserPath/$fields[1]";
                            $tokenized.= "<a href=\"$file\" $target>$fields[2]</a> <img style=\"position:relative; top:.3em;\" src=\"$IconPath/$our_icon\" alt=\"$ext[$i]\" />";
                            break;
                        case "cat":
                            $pretext = "";
                            switch ($fields[3]) {
                                case "subject":
                                    $cat_url = $open_string . $fields[1] . $close_string;
                                    $pretext = $fields[2] . " ";
                                    $linktext = $fields[1];
                                    break;
                                case "keywords":
                                    $cat_url = $open_string_kw . $fields[1] . $close_string_kw;
                                    $linktext = $fields[2];
                                    break;
                                case "call_num":
                                    $cat_url = $open_string_cn . $fields[1] . $close_string_cn;
                                    $linktext = $fields[2];
                                    break;
                                case "bib":
                                    $cat_url = $open_string_bib . $fields[1];
                                    $linktext = $fields[2];
                                    break;
                            }
                            $tokenized.= "$pretext<a href=\"$cat_url\" $target>$linktext</a>";
                            break;
                        case "dab":

                            $description = "";
                            ///////////////////
                            // Check for icons or descriptions in fields[3]
                            // 00 = neither; 10 = icons no desc; 01 = desc no icons; 11 = both
                            ///////////////////

                            if (isset($fields["3"])) {
                                // Transform the number into an array of values
                                $options = str_split($fields["3"]);

                                $show_icon_option = $options[0];
                                $show_desc_option = $options[1];


                                if ($show_icon_option == 1) {
                                    $show_icons = "yes";
                                    $show_rank = 0;
                                } else {
                                    $show_icons = "";
                                }

                                if ($show_desc_option == 1) {
                                    $show_desc = 1;
                                    $show_rank = 0;
                                } else {
                                    $show_desc = "";
                                }

                                // This option was not in previous version so it needs to be checked
                                if(isset($options[2])) {

                                    $show_note_option = $options[2];

                                    if ($show_note_option == 1) {
                                        $show_note  =  1;
                                    } else {
                                        $show_note = "";
                                    }
                                }



                            }

                            $query = "SELECT location, access_restrictions, format, ctags, helpguide, citation_guide, description, call_number, t.title, display_note, t.pre
                                    FROM location l, location_title lt, title t
                                    WHERE l.location_id = lt.location_id
                                    AND lt.title_id = t.title_id
                                    AND t.title_id = $fields[1]";

                            $result = $db->query($query);

                            foreach ($result as $myrow) {


                                // eliminate final line breaks -- offset fixed 11/15/2011 agd
                                $myrow[6] = preg_replace('/(<br \/>)+/', '', $myrow[6]);
                                // See if it's a web format
                                if ($myrow[2] == 1) {

                                	switch ($myrow[1]) {

		                                case 1:
			                                $url = $myrow[0];
			                                $rest_icons = "unrestricted";
			                                break;
		                                case 2:
		                                case 3:
			                                $url = $proxyURL . $myrow[0];
			                                $rest_icons = "restricted";
			                                break;
		                                case 4:
			                                $url = $myrow[0];
			                                $rest_icons = "restricted";
			                                break;
	                                }

//
//                                    if ($myrow[1] == 1) {
//                                        $url = $myrow[0];
//                                        $rest_icons = "unrestricted";
//                                    } else {
//                                        $url = $proxyURL . $myrow[0];
//                                        $rest_icons = "restricted";
//                                    }

                                    $current_ctags = explode("|", $myrow[3]);

                                    // add our $rest_icons info to this array at the beginning
                                    array_unshift($current_ctags, $rest_icons);

                                    if ($show_icons == "yes") {
                                        $icons = showIcons($current_ctags);
                                    } else {
                                        $icons = "";

                                    }

                                    if ($show_desc == 1) {
                                        // if we know the subject_id, good; for public, must look up
                                        $subject_id = '';
                                        if (isset($_GET["subject_id"])) {
                                            $subject_id = $_GET["subject_id"];
                                        } elseif (isset($_GET["subject"])) {
                                            $q1 = "SELECT subject_id FROM subject WHERE shortform = '" . $_GET["subject"] . "'";

                                            $r1 = $db->query($q1);
                                            //$subject_id = $db->last_id($r1);
                                            //$subject_id = $subject_id[0];
                                            $subject_id = $r1[0]["subject_id"];
                                        }elseif (isset($_POST["this_subject_id"])){
                                            $subject_id = $_POST["this_subject_id"];
                                        }

                                        $override = findDescOverride($subject_id, $fields[1]);
                                        // if they do want to display the description:
                                        if (trim($override) != "") {
                                            // show the subject-specific "description_override" if it exists
                                            $description = "<br />" . scrubData($override);
                                        } else {
                                            $description = "<br />" . scrubData($myrow[6], 'richtext_html_purifier');
                                        }
                                        //$description = "<br />$myrow[9]";


                                    }

                                    $note = ""; // init note

                                    if (isset($show_note) && $show_note == 1) {
                                        if ($myrow[9] != "") {
                                            $note = "<br />" ._("Note: ") . $myrow[9];
                                        }

                                    } else {
                                        $note = "";
                                    }

                                    if($myrow['10']) {
                                        $prefixed_label = $myrow['10'] . ' ' . $myrow['8'];
                                    } else {
                                        $prefixed_label = $myrow['8'];
                                    }

                                    $tokenized.= "<a href=\"$url\" $target>$prefixed_label</a> $icons $description $note";
                                } else {
                                    // It's print
                                    $format = "other";

                                    $current_ctags = explode("|", $myrow[3]);

                                    if ($show_icons == "yes") {
                                        $icons = showIcons($current_ctags);
                                    } else {
                                        $icons = "";
                                    }

                                    // added Diane Z fall 2014

                                    if ($show_desc == 1) {
                                        // if we know the subject_id, good; for public, must look up
                                        $subject_id = '';
                                        if (isset($_GET["subject_id"])) {
                                            $subject_id = $_GET["subject_id"];
                                        } elseif (isset($_GET["subject"])) {
                                            $q1 = "SELECT subject_id FROM subject WHERE shortform = '" . $_GET["subject"] . "'";

                                            $r1 = $db->query($q1);
                                            $subject_id = $r1[0]["subject_id"];
                                        }

                                        $override = findDescOverride($subject_id, $fields[1]);
                                        // if they do want to display the description:
                                        if (trim($override) != "") {
                                            // show the subject-specific "description_override" if it exists
                                            $description = "<br />" . $override;
                                        } else {
                                            $description = "<br />" . $myrow[6];
                                        }
                                        //$description = "<br />$myrow[9]";

                                    }
                                    // end diane fall 2014

                                    if (isset($show_note) && $show_note == 1) {
                                        if ($myrow[9] != "") {
                                            $note = "<br />" ._("Note: ") . $myrow[9];
                                        }

                                    } else {
                                        $note = "";
                                    }

                                    // Simple Print (2), or Print with URL (3)
                                    if ($myrow[2] == 3) {
                                        $tokenized.= "<em>$myrow[8]</em><br />" . _("") . "
                                        <a href=\"$myrow[0]\" $target>$myrow[7]</a>
                                        $icons $description";
                                    } else {

                                        // check if it's a url
                                        if (preg_match('/^(https?|www)/', $myrow[0])) {
                                            $tokenized.= "<a href=\"$myrow[0]\" $target>$myrow[8]</a> $icons $description $note";
                                        } else {
                                            $tokenized.= "$myrow[8] <em>$myrow[0]</em> $icons $description $note";
                                        }
                                    }
                                }
                            }
                            break;
                        case 'sss':
                            global $tel_prefix;

                            $querier = new Querier();
                            $qs = "SELECT lname, fname, email, tel, title from staff WHERE email IN ('" . str_replace( ',', "','", $fields[1] ) . "') ORDER BY lname, fname";

                            //print $qs;

                            $staffArray = $querier->query($qs);

                            foreach ($staffArray as $value) {

                                // get username from email
                                $truncated_email = explode("@", $value[2]);

                                $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

                                // Output Picture and Contact Info
                                $tokenized .= "
                    			<div class=\"clearboth\"><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
                    			<p><a href=\"mailto:$value[2]\">$value[1] $value[0]</a><br />$value[4]<br />
                    			Tel: $tel_prefix $value[3]</p>\n</div>\n";
                            }
                            break;
                        case 'toc':
                            $lobjTocPluslet = new Pluslet_TOC('', '', $this->_subject_id);
                            $lobjTocPluslet->setTickedItems( explode(',', $fields[1]) );
                            $lobjTocPluslet->setHideTitleBar(1);
                            $tokenized .= $lobjTocPluslet->output();
                            break;

                    }
                } else {
                    $tokenized.= $part;
                }
            } // end foreach
        } else {
            $this->_body = ($this->_body);
            return;
        }

	    $tokenized = utf8_decode($tokenized);
	    $this->_body = $tokenized;
    }

    protected function onEditOutput()
    {
        $this->_body = "General pluslet output!";
    }

    protected function onViewOutput()
    {
        $this->_body = "General pluslet output!";
    }

    public function output($action="", $view)
    {
        $this->establishView($view);

        if ($action == "edit") {

            global $title_input_size; // alter size based on column

            $this->onEditOutput();

            //
            //////////////////////
            // New or Existing?
            //////////////////////

            if ($this->_pluslet_id) {
                $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
                $this->_pluslet_name_field = "";
                $this->_title = "<input type=\"text\" class=\"\" id=\"pluslet-update-title-$this->_pluslet_id\" value=\"$this->_title\" size=\"$title_input_size\" />";
                $this_instance = "pluslet-update-body-$this->_pluslet_id";
            } else {
                $new_id = rand(10000, 100000);
                $this->_pluslet_bonus_classes = "unsortable";
                $this->_pluslet_id_field = $new_id;
                $this->_pluslet_name_field = "new-pluslet-" . $this->_type;

                $this->_title = "<input type=\"text\" class=\"\" id=\"pluslet-new-title-$new_id\" name=\"new_pluslet_title\" value=\"$this->_title\" size=\"$title_input_size\" />";
                $this_instance = "pluslet-new-body-$new_id";
            }

            $this->startPluslet();
            print $this->_body;
            $this->finishPluslet();

            return;
        } else {

            // notitle hack
            if ($this->_hide_titlebar == 1) { $this->_hide_titlebar = 1;} else {$this->_hide_titlebar = 0;}

            $this->onViewOutput();

            // Look for tokens, tokenize
            $this->tokenizeText();

            $this->assemblePluslet($this->_hide_titlebar);

            return $this->_pluslet;
        }
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setHideTitleBar( $lintHide )
    {
        $this->_hide_titlebar = $lintHide;
    }

    public static function getCkPluginName()
    {
        return '';
    }

    function getRecordId() {
        return $this->_pluslet_id;
    }

    function getExtraInfo() {
        return $this->_extra;
    }

    function deBug() {
        print $this->_debug;
    }

    function getNote() {

    }

    public function loadHtml($path) {

        ob_start();
        include $path;
        $external_html = ob_get_contents();
        ob_end_clean();

        return $external_html;

    }


}