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
        $q1 = "SELECT pluslet_id, title, body, clone, type, extra, hide_titlebar, collapse_body, titlebar_styling
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
                $helptext = _("Help");
                $settingstext = _("Box Settings");

				if(strtolower($this->_type) != 'special')
				{
					$this->_icons = "
		                <a id=\"settings-$this->_pluslet_id\"><img src=\"$IconPath/settings-26.png\" border=\"0\" title=\"$settingstext\" class=\"pluslet-icon\" alt=\"" . _("help") . "\" /></img></a>
		                <!--<img src=\"$IconPath/help.png\" border=\"0\" title=\"$helptext\" class=\"pluslet-icon help-$this->_type\" alt=\"" . _("help") . "\" /></img>
		                <a class=\"togglebody\"><img class=\"pluslet-icon\"  src=\"$IconPath/toggle_small.png\"  alt=\"" . _("toggle me") . "\" title=\"" . _("toggle me") . "\" /></img></a>-->";
				}

                // If editable, give the pencil icon
                if ($this->_editable == TRUE) {
                    // Deal with All Items by Source type (#1 in db)
                    if ($this->_pluslet_id == 1) {
                        $this->_icons .= " <a class=\"showmedium\" href=\"manage_items.php?subject_id=$this->_subject_id&amp;wintype=pop\"><img class=\"pluslet-icon\"  src=\"$IconPath/pencil.png\"  alt=\"" . _("Edit") . "\" title=\"" . _("Edit") . "\" /> </img> </a>";
                    } else {
                        $this->_icons .= " <a id=\"edit-$this->_pluslet_id-$this->_type\"><img class=\"pluslet-icon\"  src=\"$IconPath/pencil.png\" alt=\"" . _("Edit") . "\" title=\"" . _("Edit") . "\" /></img></a>";
                    }
                }
                // Everyone gets a delete button in the admin
                $this->_icons .= " <a id=\"delete-$this->_pluslet_id\"><img class=\"pluslet-icon\"  src=\"$IconPath/delete.png\"" . _("Remove item from this guide") . "\" title=\"" . _("Remove item from this guide") . "\" border=\"0\" /></i></a>";

                // Show the item id --it's handy for debugging
                $this->_visible_id = "<span class=\"pluslet_id smallgrey\">$this->_pluslet_id</span>";

                // get our relative path to the legacy fixed_pluslet folder
                $this->_relative_asset_path = "../../assets/";

                break;
            default:
                // Default is the public view
                // don't show the item id
                $this->_visible_id = "";

                // get our relative path to the legacy fixed_pluslet folder
                $this->_relative_asset_path = "../assets/";

                break;
        }
    }

    protected function assemblePluslet($hide_titlebar=0) {
		global $IconPath;

        // if we're using a simple pluslet, things are diff
        // we use $this->_visible_id to make sure this is only on the frontend

        if (($hide_titlebar == 1 && $this->_visible_id == "" || $this->_title == "" && $this->_visible_id == "")) {
            $this->_pluslet .= "
            <div id=\"$this->_pluslet_id_field\" class=\"pluslet_simple no_overflow $this->_pluslet_id_field\"><a name=\"box-" . $this->_pluslet_id . "\"></a>" . htmlspecialchars_decode($this->_body);
            // this div closed outside of if/else
        } else {

			$this->_pluslet_name_field = empty($this->_pluslet_name_field) ? $this->_type : "settings-{$this->_pluslet_name_field}";

            $this->_pluslet .= "<div class=\"pluslet $this->_pluslet_bonus_classes $this->_pluslet_id_field\" id=\"$this->_pluslet_id_field\" name=\"$this->_pluslet_name_field\">
			<a name=\"box-" . $this->_pluslet_id . "\"></a>
            <div class=\"titlebar\">";

        	//only if on admin side, display sort icon
	        if( $this->_visible_id != '' ) {
	        	$this->_pluslet .= "<img src=\"$IconPath/hand_cursor-26.png\" class=\"pluslet_sort\" />";
			}


        	//if public view, add selected style
        	if( $this->_visible_id != '' ) {
        		$this->_pluslet .= "<div class=\"titlebar_text\">$this->_title $this->_visible_id</div>";
        	}else
        	{
        		$this->_pluslet .= "<div class=\"titlebar_text {$this->_titlebar_styling}\">$this->_title $this->_visible_id</div>";

                // since we're here, let's see if the body should be collapsed
                if ($this->_collapse_body == 1) {
                    $this->_pluslet_body_bonus_classes .= " noshow";

                }

        	}

        	$this->_pluslet .= "\n<div class=\"titlebar_options\">$this->_icons</div>";

           if ($this->_visible_id != "") {
                $this->_pluslet .= self::boxSettings(); // add in our hidden div full of box config options

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
        <div class=\"pluslet $this->_pluslet_bonus_classes\" id=\"$this->_pluslet_id_field\" name=\"$this->_pluslet_name_field\">
            <div class=\"titlebar\">
                <div class=\"titlebar_text\">$this->_title $this->_visible_id</div>
                <div class=\"titlebar_options\">$this->_icons</div>
            ";

        echo self::boxSettings(); // add in our hidden div full of box config options

        echo "</div>
        <div class=\"pluslet_body $this->_pluslet_body_bonus_classes\">";
    }

    protected function finishPluslet() {

        echo "</div>
            </div>";
    }

    protected function boxSettings() {

        global $titlebar_styles;

        // generate our titlebar styles
        $tb_styles = "";
        foreach ($titlebar_styles as $key => $value) {
            $tb_styles .= "<option value=\"$value\" style=\"$value\"";
                if ($this->_titlebar_styling == $value) { $tb_styles .= " selected";}
            $tb_styles .= ">$key</option>";
        }

            $box_settings = "<div class=\"box_settings\">
            <form class=\"pure-form pure-form-aligned\">
            <label for=\"notitle-$this->_pluslet_id\" class=\"pure-checkbox\">
                <input id=\"notitle-$this->_pluslet_id\" type=\"checkbox\"";

                if ($this->_hide_titlebar == 1) {$box_settings .= " checked";}

            $box_settings .= "> " . _("Hide titlebar") . "
            </label>
            <label for=\"start-collapsed-$this->_pluslet_id\" class=\"pure-checkbox\">
                <input id=\"start-collapsed-$this->_pluslet_id\" type=\"checkbox\"";

                if ($this->_collapse_body == 1) {$box_settings .= " checked";}

            $box_settings .= "> " . _("Hide box body by default (public site)") . "
            </label>
            <label for=\"titlebar-styling-$this->_pluslet_id\">" . _("Titlebar Styling") . "</label>
                <select id=\"titlebar-styling-$this->_pluslet_id\">
                    $tb_styles
                </select>
            </form>
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
        $tokenized = "";

        $parts = preg_split('/<span[^>]*>{{|}}<\/span>/', $this->_body);

    	if( count($parts) == 1 )
    		$parts = preg_split('/{{|}}/', $this->_body);

        if (count($parts) > 1) { // there are tokens in $body
            foreach ($parts as $part) {
                if (preg_match('/^dab},\s?{\d+},\s?{.+},\s?{[01]{2}$/', $part) || preg_match('/^faq},\s?{(\d+,)*\d+$/', $part)
                	|| preg_match('/^cat},\s?{.+},\s?{.*},\s?{\w+$/', $part) || preg_match('/^fil},\s?{.+},\s?{.+$/', $part)
                	|| preg_match('/^sss},\s?{[^}]*/', $part) || preg_match('/^toc},\s?{[^}]*/', $part) ) { // $part is a properly formed token
                    $fields = preg_split('/},\s?{/', $part);
                    $prefix = substr($part, 0, 3);
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
                            //print_r($fields);
                            $description = "";
                            ///////////////////
                            // Check for icons or descriptions in fields[3]
                            // 00 = neither; 10 = icons no desc; 01 = desc no icons; 11 = both
                            ///////////////////
                            if (isset($fields["3"])) {
                                switch ($fields["3"]) {
                                    case "00":
                                        $show_icons = "";
                                        $show_desc = "";
                                        $show_rank = 0;
                                        break;
                                    case "10":
                                        $show_icons = "yes";
                                        $show_desc = "";
                                        $show_rank = 0;
                                        break;
                                    case "01":
                                        $show_icons = "";
                                        $show_desc = 1;
                                        $icons = "";
                                        break;
                                    case "11":
                                        $show_icons = "yes";
                                        $show_desc = 1;
                                        break;
                                }
                            }
                            $query = "SELECT location, access_restrictions, format, ctags, helpguide, citation_guide, description, call_number, t.title
                                    FROM location l, location_title lt, title t
                                    WHERE l.location_id = lt.location_id
                                    AND lt.title_id = t.title_id
                                    AND t.title_id = $fields[1]";
                            //print $query . "<br /><br />";
                            $result = $db->query($query);

                            foreach ($result as $myrow) {

                                // eliminate final line breaks -- offset fixed 11/15/2011 agd
                                $myrow[6] = preg_replace('/(<br \/>)+/', '', $myrow[6]);
                                // See if it's a web format
                                if ($myrow[2] == 1) {
                                    if ($myrow[1] == 1) {
                                        $url = $myrow[0];
                                        $rest_icons = "unrestricted";
                                    } else {
                                        $url = $proxyURL . $myrow[0];
                                        $rest_icons = "restricted";
                                    }

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
                                        }

                                        $override = findDescOverride($subject_id, $fields[1]);
                                        // if they do want to display the description:
                                        if ($override != "") {
                                            // show the subject-specific "description_override" if it exists
                                            $description = "<br />" . scrubData($override);
                                        } else {
                                            $description = "<br />" . scrubData($myrow[6]);
                                        }
                                        //$description = "<br />$myrow[9]";
                                    }
                                    $tokenized.= "<a href=\"$url\" $target>$myrow[8]</a> $icons $description";
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
                                        if ($override != "") {
                                            // show the subject-specific "description_override" if it exists
                                            $description = "<br />" . $override;
                                        } else {
                                            $description = "<br />" . $myrow[6];
                                        }
                                        //$description = "<br />$myrow[9]";
                                    
                                    }
                                    // end diane fall 2014

                                    // Simple Print (2), or Print with URL (3)
                                    if ($myrow[2] == 3) {
                                        $tokenized.= "<em>$myrow[8]</em><br />" . _("") . "
                                        <a href=\"$myrow[0]\" $target>$myrow[7]</a>
                                        $icons $description";
                                    } else {

                                        // check if it's a url
                                        if (preg_match('/^(https?|www)/', $myrow[0])) {
                                            $tokenized.= "<a href=\"$myrow[0]\" $target>$myrow[8]</a> $icons $description";
                                        } else {
                                            $tokenized.= "$myrow[8] <em>$myrow[0]</em> $icons $description";
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
                } elseif (preg_match('/{|}/', $part) && preg_match('/\bdab\b|\bfaq\b|\bcat\b|\bfil\b/', $part)) { // looks kinda like a token
                    $tokenized.= "<span style='background-color:yellow'>BROKEN TOKEN: " . $part . "</span>";
                } else {
                    $tokenized.= $part;
                }
            } // end foreach
        } else {

            $this->_body = $this->_body;
            return;
        }
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
				$this->_pluslet_name_field = "new-pluslet-HTML5Video";
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

}

?>
