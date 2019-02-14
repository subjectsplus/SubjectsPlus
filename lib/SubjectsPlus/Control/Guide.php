<?php
namespace SubjectsPlus\Control;
use Assetic\Exception\Exception;
use SubjectsPlus\Control\Guide\PlusletData;
use SubjectsPlus\Control\Guide\SubjectBBCourse;

/**
 * @file sp_Guide
 * @brief manage guide metadata
 *
 * @author agdarby, rgilmour
 * @date Jan 2011
 * @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */

class Guide
{

    private $_subject_id;
    private $_subject;
    private $_shortform;
    private $_description;
    private $_keywords;
    private $_redirect_url;
    private $_active;
    private $_type;
    private $_extra;
    private $_message;
    private $_all_tabs;
    private $_parents;
    private $_header;
    private $_debug;
    private $_course_code;
    private $_instructor;

    public $_ok_staff = array();
    public $main_col_size;

    public $_isAdmin;

    public function __construct($subject_id = "", $flag = "")
    {

        $this->db = new Querier;
    	
        global $use_disciplines;

        if ($flag == "" && $subject_id == "") {
            $flag = "empty";
        }

        $this->_isAdmin = FALSE;
        $this->_all_tabs = NULL;

        switch ($flag) {
            case "empty":
                // Set default staffer, but nothing else; this will create an empty record
                $this->_staffers = array(0 => array($_SESSION["staff_id"], $_SESSION["fname"] . " " . $_SESSION["lname"]));
                $this->_extra = array();
                $this->_discipliners = array();

                break;
            case "post":
                // prepare record for insertion or update
                // data stored in subject table
                $this->_subject_id = $_POST["subject_id"];
                $this->_subject = $_POST["subject"];
                $this->_shortform = $_POST["shortform"];
                $this->_description = $_POST["description"];
                $this->_keywords = $_POST["keywords"];
                $this->_redirect_url = $_POST["redirect_url"];
                $this->_active = $_POST["active"];
                $this->_type = $_POST["type"];
                $this->_shortform = $_POST["shortform"];
                $this->_extra = $_POST['extra'];
                $this->_header = $_POST['header'];
                $this->_course_code = isset($_POST['coursecode']) ? $_POST['coursecode'] : "";
                $this->_instructor = isset($_POST['instructor']) ? $_POST['instructor'] : "";

                //add http to redirect url if not present
                $this->_redirect_url = strpos($this->_redirect_url, "http://") === 0 || strpos($this->_redirect_url, "https://") === 0
                || $this->_redirect_url == "" ? $this->_redirect_url : "http://" . $this->_redirect_url;

                // data stored in staff_subject table
                $this->_staff_id = $_POST["staff_id"]; // array
                $this->_staff_count = count($this->_staff_id); // # of items in above array

                // data stored in subject_subject table
                $this->_parent_id = $_POST["parent_id"]; // array
                $this->_parent_count = count($this->_parent_id); // # of items in above array                   

                if ($use_disciplines == TRUE) {
                    //data stored in subject_discipline table
                    $this->_discipline_id = $_POST["discipline_id"]; // array
                    $this->_discipline_count = count($this->_discipline_id); // # of items in above array
                }


                break;
            case "delete":
                // kind of redundant, but just set up to delete appropriate tables?
                // $this->_staffers needed to see if they have permission to delete this record
                $this->_subject_id = $subject_id;

                self::getAssociatedStaff();

                //$this->_staffers = array(0 => array($_SESSION["staff_id"], $_SESSION["fname"] . " " . $_SESSION["lname"]));

                break;
            default:

                $this->_subject_id = $subject_id;

                /////////////
                // Get subject table info
                /////////////

                $db = new Querier();
                $q1 = "select subject_id, subject, active, shortform, description, keywords, redirect_url, type, extra, header, course_code, instructor from subject where subject_id = " . $this->_subject_id;
                $guideArray = $db->query($q1);

                $this->_debug .= "<p>Subject query: $q1";
                // Test if these exist, otherwise go to plan B
                if ($guideArray == FALSE) {
                    $this->_message = _("There is no active record with that ID.  Why not create a new one?");
                } else {
                    $this->_subject = $guideArray[0]["subject"];
                    $this->_shortform = $guideArray[0]["shortform"];
                    $this->_description = $guideArray[0]["description"];
                    $this->_keywords = $guideArray[0]["keywords"];
                    $this->_redirect_url = $guideArray[0]["redirect_url"];
                    $this->_active = $guideArray[0]["active"];
                    $this->_type = $guideArray[0]["type"];
                    $this->_extra = json_decode($guideArray[0]["extra"], true);
                    $this->_header = $guideArray[0]["header"];
                    $this->_course_code = isset($guideArray[0]["course_code"]) ? $guideArray[0]["course_code"] : "";
                    $this->_instructor = isset($guideArray[0]["instructor"]) ? $guideArray[0]["instructor"] : "";
                }

                ///////////////////
                // Query Staff table
                // used to get our set of staff associated
                // ////////////////

                self::getAssociatedStaff();

                ///////////////////
                // Query subject_subject table
                // used to get our set of subjects associated
                // ////////////////

                self::getAssociatedParents();

                ///////////////////
                // Query Discipline table
                // used to get our set of disciplines associated
                // ////////////////
                if ($use_disciplines == TRUE) {
                    self::getAssociatedDisciplines();
                }

                break;
        }
    }

    public function getAssociatedStaff()
    {

        $db = new Querier();
        $q2 = "SELECT s.staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff s, staff_subject ss WHERE s.staff_id = ss.staff_id AND ss.subject_id = " . $this->_subject_id . " ORDER BY  staff_guide_order";

        $this->_staffers = $db->query($q2);

        foreach ($this->_staffers as $value) {
            $this->_ok_staff[] = $value[0];
        }

        $this->_debug .= "<p>Staff query: $q2";
    }

    public function getAssociatedParents()
    {

        $current_parent_querier = new Querier();
        $current_parent_query =
        "SELECT DISTINCT parent.subject_id AS parent_id, parent.subject AS parent_subject,  child.subject AS child_subject, child.subject_id AS child_id,  has.date, has.subject_parent
        FROM subject_subject AS has
        JOIN subject as child ON child.subject_id = has.subject_child
        JOIN subject as parent ON parent.subject_id = has.subject_parent
        WHERE child.subject_id = '$this->_subject_id'
        ORDER BY has.date DESC";

        $this->_parents = $current_parent_querier->query($current_parent_query);

        foreach ($this->_parents as $value) {
            $this->_ok_parents[] = $value[0];
        }

        $this->_debug .= "<p>Parents query: $current_parent_query";

    }

    public function getAssociatedDisciplines()
    {

        $querier3 = new Querier();
        $q3 = "SELECT d.discipline_id, d.discipline FROM discipline d, subject_discipline sd WHERE d.discipline_id = sd.discipline_id
            AND sd.subject_id = " . $this->_subject_id;

        $this->_discipliners = $querier3->query($q3);

        if ($this->_discipliners) {
            foreach ($this->_discipliners as $value) {
                $this->_ok_disciplines[] = $value[0];
            }
        }

        $this->_debug .= "<p>Disciplines query: $q3";
    }

    public function getRelatedGuides()
    {
        $db  = new Querier();
        $children = $db->query ( 'SELECT * FROM subject
                          INNER JOIN subject_subject
                          ON subject.subject_id = subject_subject.subject_child
                          WHERE subject_parent = ' . $this->_subject_id . '
                          OR subject_child = ' . $this->_subject_id );

        return $children;
    }


    public function addExtra($key, $value)
    {
        if ($key != '') {
            $this->_extra[$key] = $value;
        }
    }

    public function outputMetadataForm($wintype = "")
    {

        global $wysiwyg_desc;
        global $IconPath;
        global $guide_types;
        global $guide_headers;
        global $use_disciplines;

        //print "<pre>";print_r($this->_staffers); print "</pre>";

        $action = htmlentities($_SERVER['PHP_SELF']) . "?subject_id=" . $this->_subject_id;

        if ($wintype != "") {
            $action .= "&wintype=pop";
        }
		if ($wintype == "pop") {
		$copy_guide = "";
		} else {
		$copy_guide = "<a href='guide_copy.php'>" . _("Copy an Existing Guide") . "</a>";
		}
        if ($this->_subject_id) {
            $guide_title_line = _("Edit Existing Guide Metadata");
			
        } else {
            $guide_title_line = _("Create New Guide");
        }

        $db = new Querier();
        $objInstructors = new SubjectBBCourse($db);
        $instructor_option_boxes = $objInstructors->getInstructorsDropDownItems($this->_instructor);

        $all_instructors = " 
            <select name=\"instructor\" id=\"instructor\"> 
            <option id='intructor_place_holder'>" . _("None") . "</option> 
            $instructor_option_boxes 
            </select> 
             
            <script> 
            $(document).ready(function() { 
        $('#instructor').select2(); 
 
    }); 
            </script> 
            ";

        echo "
            <form action=\"" . $action . "\" method=\"post\" id=\"new_record\" class=\"pure-form pure-form-stacked\" accept-charset=\"UTF-8\">
            <input type=\"hidden\" name=\"subject_id\" value=\"" . $this->_subject_id . "\" />
            <div class=\"pure-g\">
              <div class=\"pure-u-1-2\">
                <div class=\"pluslet\">
                    <div class=\"titlebar\">
                      <div class=\"titlebar_text\">$guide_title_line</div>
                      <div class=\"titlebar_options\"></div>
                    </div>
                <div class=\"pluslet_body\">
				$copy_guide
			<label for=\"record_title\">" . _("Guide Title") . "</label>
            <input type=\"text\" name=\"subject\" id=\"record_title\" class=\"pure-input-1-2 required_field\" value=\"" . $this->_subject . "\">

            <label for=\"record_shortform\">" . _("Short Form") . "</label>
            <input type=\"text\" name=\"shortform\" id=\"record_shortform\" size=\"20\" class=\"pure-input-1-4 required_field\" value=\"" . $this->_shortform . "\">

            <span class=\"smaller\">* " . _("Short label that shows up in URL--don't use spaces, ampersands, etc.") . "</span>";

        global $lti_enabled;
        if (isset($lti_enabled)) {
            if ($lti_enabled) {
                echo "
                <label for=\"course_code\">" . _("Course Code") . "</label>
            <input type=\"text\" name=\"coursecode\" id=\"course_code\" size=\"20\" class=\"pure - input - 1 - 4\" value=\"" . $this->_course_code . "\" >

            <label for=\"instructor\" > " . _("Instructor") . "</label>
            <div class=\"all - instructors - dropdown dropdown_list\">" . $all_instructors . "</div>
                ";
            }
        }
            
        echo "<label for=\"type\">" . _("Type of Guide") . "</label>";

        /////////////////////
        // Guide types dropdown
        /////////////////////

        $guideMe = new Dropdown("type", $guide_types, $this->_type, "50");
        $guide_string = $guideMe->display();
        echo $guide_string;

        echo "<label for=\"header\">" . _("Header Type") . "</label>";

        /////////////////////
        // Header switcher dropdown
        /////////////////////

        $headerMe = new Dropdown("header", $guide_headers, $this->_header, "50");
        $header_string = $headerMe->display();
        echo $header_string;

        echo "<span class=\"smaller\">* " . _("If you're not sure, stick with default") . "</span>";

        /////////////////////
        // Is Live
        ////////////////////

        $is_live = "<label for=\"active\">" . _("Visibility") . "</label>
    <input name=\"active\" type=\"radio\" value=\"1\"";
        if ($this->_active == 1) {
            $is_live .= " checked=\"checked\"";
        }
        $is_live .= " /> " . _("Public:  Everyone can see") . " <br />
        <input name=\"active\" type=\"radio\" value=\"0\"";
        if ($this->_active == 0) {
            $is_live .= " checked=\"checked\"";
        }
        $is_live .= " /> " . _("Hidden:  Not listed, but visible if you have the URL") . " <br />
        <input name=\"active\" type=\"radio\" value=\"2\"";
        if ($this->_active == 2) {
            $is_live .= " checked=\"checked\"";
        }
        $is_live .= " /> " . _("Suppressed:  Must be logged in to SP to view");

        print $is_live;

        ////////////////////////////
        // Parenthood
        ///////////////////////////

        $parents_list = "";

        if ($this->_parents == FALSE) {
            // No results
            $parents_list = "";
        } else {
            // loop through results
            foreach ($this->_parents as $value) {

                $parents_list .= self::outputParents($value);
            }
        }

        ////////
        // Parent dropdown
        ////////

        $querier = new Querier();
        $subject_query = "SELECT subject_id, subject FROM subject WHERE subject_id != '$this->_subject_id' ORDER BY subject ASC ";
        $subjectArray = $querier->query($subject_query);

        $parentMe = new Dropdown("parent_id[]", $subjectArray, "", "50", "--Select--");
        $parent_string = $parentMe->display();

        $parenthood = "$parent_string <div id=\"parent_list\">$parents_list</div> <!-- parent guides inserted here -->";

        // this is for legacy reasons, methinks
        if (isset($main_col_size)) {
        } else {
        $main_col_size = null;
        }

        $screen_layout = "<input type=\"hidden\" id=\"extra\" name=\"extra[maincol]\" value=\"$main_col_size\" />";


        echo "
        <label for=\"parent\">" . _("Parent Guides") . "</label>
        $parenthood
        <br style=\"clear: both;\" />
        <span class=\"smaller\">* " . _("Parent guides allow you to create a hierarchy") . "</span>
        $screen_layout
    </div>
    </div>
    </div>
    <!-- right hand column -->
    <div class=\"pure-u-1-2\">";

    $content = "<input type=\"submit\" name=\"submit_record\"  class=\"pure-button pure-button-primary save-guide\" value=\"" . _("Save Now") . "\" />";

        // if a) it's not a new record, and  b) we're an admin or c) we are listed as a librarian for this guide, show delete button
        // make sure they're allowed to delete

    if ($this->_subject_id != "") {
            if (in_array($_SESSION["staff_id"], $this->_ok_staff) || $_SESSION["admin"] == 1) {
            $content .= " <input type=\"submit\" name=\"delete_record\" class=\"pure-button delete_button pure-button-warning delete-guide\" value=\"" . _("Delete Forever!") . "\" />";
        }
    }
    // get edit history
    $last_mod = _("Last modified: ") . lastModded("guide", $this->_subject_id);
    $title = "<div id=\"last_edited\">$last_mod</div>";

    makePluslet($title, $content, "no_overflow");

        /////////////////
        // Staffers
        /////////////////
        $staffer_list = "";

        if ($this->_staffers == FALSE) {
            // No results
            $staffer_list = "";
        } else {
            // loop through results
            foreach ($this->_staffers as $value) {

                $staffer_list .= self::outputStaff($value);
            }
        }

        $qStaff = "select staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE ptags LIKE '%records%' AND active = '1' ORDER BY lname, fname";

        $querierStaff = new Querier();
        $staffArray = $querierStaff->query($qStaff);

        $staffMe = new Dropdown("staff_id[]", $staffArray, "", "50", "--Select--");
        $staff_string = $staffMe->display();

        /////////////////
        // Disciplines
        /////////////////

        if ($use_disciplines == TRUE) {

            $discipliner_list = "";

            if ($this->_discipliners == FALSE) {
                // No results
                $discipliner_list = "";
            } else {
                // loop through results
                foreach ($this->_discipliners as $value) {

                    $discipliner_list .= self::outputDisciplines($value);
                }
            }

            $qDiscipline = "select discipline_id, discipline FROM discipline ORDER BY discipline";

            $querierDiscipline = new Querier();
            $disciplineArray = $querierStaff->query($qDiscipline);

            $disciplineMe = new Dropdown("discipline_id[]", $disciplineArray, "", "50", "--Select--");
            $discipline_string = $disciplineMe->display();
        }

        $staff_box = "$staff_string <div id=\"item_list\" class=\"sortable-staff-list ui-sortable\">$staffer_list</div> <!-- staff inserted here -->";

        makePluslet(_("Staff"), $staff_box, "no_overflow");

        //////////////
        // Metadata
        //////////////

        $metadata_box = "
        <label for=\"description\">" . _("Description") . "</label>
        <textarea name=\"description\" id=\"record_description\" class=\"\" cols=\"35\" rows=\"2\">" . $this->_description . "</textarea>

        <label for=\"keywords\">" . _("Keywords (separate with commas)") . "</label>
        <input type=\"text\" name=\"keywords\" id=\"record_keywords\" size=\"40\" class=\"\" value=\"" . $this->_keywords . "\">
        <label for=\"record_label\">" . _("Redirect Url (for non-SubjectsPlus content)") . "</label>
        <input type=\"text\" name=\"redirect_url\" id=\"record_redirect_url\" size=\"40\" class=\"\" value=\"" . $this->_redirect_url . "\">
        ";

        if ($use_disciplines == TRUE) {

        $metadata_box .= "<label for=\"discipline_list\">" . _("Parent Disciplines") . "</span><br />
        $discipline_string
        <div id=\"discipline_list\">$discipliner_list</div> <!-- disciplines inserted here -->
        </div>";

        }

        makePluslet(_("Metadata (optional)"), $metadata_box, "no_overflow");

        ////////////////
        // Thumbnail Option
        ////////////////

        $thumbnail_box = _("If you want to associate a thumbnail image with this guide, put a file called [shortform].jpg in assets/images/guide_thumbs/ on the server.");
        $thumbnail_box .= "<p>" . _("E.g., musichistory.jpg, if your shortform is \"musichistory\".");
        $thumbnail_box .= "<p>" . _("Note that this is NOT required, and might NOT be implemented in your version of SubjectsPlus.");

        makePluslet(_("Thumbnail (VERY optional)"), $thumbnail_box, "no_overflow");

        echo "</div>\n</form>";
    }

    public function outputStaff($value)
    {
        global $IconPath;

        $ourstaff = "
        <div class=\"selected_item_wrapper staffwrapper sortable-staff-wrapper\">
        <div class=\"selected_item\">
        <input name=\"staff_id[]\" value=\"$value[0]\" type=\"hidden\" />
        <i class=\"fa fa-bars\" aria-hidden=\"true\"></i> $value[1]
        <br />
        </div>
        <div class=\"selected_item_options\">
        <i class=\"fa fa-times delete_item delete_staff pointer\" alt=\"" . _("delete") . "\" title=\"" . _("delete") . "\"></i>
        </div>
        </div>";

        return $ourstaff;
    }

    public function outputParents($value)
    {
        global $IconPath;

        $ourparents = "
        <div class=\"selected_item_wrapper parentwrapper\">
        <div class=\"selected_item\">
        <input name=\"parent_id[]\" value=\"$value[0]\" type=\"hidden\" />
        $value[1]<br />
        </div>
        <div class=\"selected_item_options\">
        <i class=\"fa fa-times pointer delete_item delete_parent\" alt=\"" . _("delete") . "\" title=\"" . _("delete") . "\"></i>
        </div>
        </div>";

        return $ourparents;
    }

    public function outputDisciplines($value)
    {
        global $IconPath;

        $ourdisciplines = "
        <div class=\"selected_item_wrapper disciplinewrapper\">
        <div class=\"selected_item\">
        <input name=\"discipline_id[]\" value=\"$value[0]\" type=\"hidden\" />
        $value[1]<br />
        </div>
        <div class=\"selected_item_options\">
        <i class=\"fa fa-times pointer delete_item delete_discipline\" alt=\"" . _("delete") . "\" title=\"" . _("delete") . "\"></i>
        </div>
        </div>";

        return $ourdisciplines;
    }

    public function isParentGuide($subject_id) {

        $db  = new Querier();
        $connection = $db->getConnection();
        $statement = $connection->prepare("SELECT subject_parent FROM subject_subject
											WHERE subject_parent  = :subject_id");

        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $id = $statement->fetch();

        if ( (isset($id)) && ($id != null) ) {
            return true;
        }
        return false;

    }

    public function isChildGuide($subject_id) {

        $db  = new Querier();
        $connection = $db->getConnection();
        $statement = $connection->prepare("SELECT subject_child FROM subject_subject
											WHERE subject_child  = :subject_id");

        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $id = $statement->fetch();

        if ( (isset($id)) && ($id != null) ) {
            return true;
        }
        return false;
    }

    public function deleteParentChildRelationship($subject_id) {

        $db  = new Querier();
        $connection = $db->getConnection();

        $statement = $connection->prepare("DELETE FROM subject_subject
                                            WHERE subject_child  = :subject_id");

        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        return;
    }

    public function hasMasterPluslets($subject_id) {

        $pluslet_data = new PlusletData($this->db);

        $masters = $pluslet_data->getClonedPlusletsBySubjectId($subject_id);

        if( (isset($masters)) && (!empty($masters[0])) ) {
            return true;
        }
        return false;
    }

    public function hasSpecialPluslets($subject_id) {

        $db  = new Querier();
        $connection = $db->getConnection();
        $statement = $connection->prepare("SELECT ps.pluslet_section_id FROM pluslet p INNER JOIN pluslet_section ps
        ON p.pluslet_id = ps.pluslet_id
        INNER JOIN section sec
        ON ps.section_id = sec.section_id
        INNER JOIN tab t
        ON sec.tab_id = t.tab_id
        INNER JOIN subject s
        ON t.subject_id = s.subject_id
        WHERE p.type = 'Special' AND s.subject_id = :subject_id");

        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $special_pluslets = $statement->fetchAll();
        return $special_pluslets;
    }

    public function deleteSpecialFromPlusletSection($pluslet_section_id) {

        $db  = new Querier();
        $connection = $db->getConnection();

        $statement = $connection->prepare("DELETE FROM pluslet_section
                                            WHERE pluslet_section_id  = :pluslet_section_id");

        $statement->bindParam ( ":pluslet_section_id", $pluslet_section_id );
        $statement->execute();
        return;

    }

    public function deleteRecord()
    {

        //if guide has special type pluslets, delete the row in pluslet_section table
        $special_pluslets = $this->hasSpecialPluslets($this->_subject_id);
        if( (isset($special_pluslets)) && (!empty($special_pluslets['0'])) ) {

            foreach($special_pluslets as $special_pluslet) {
                $this->deleteSpecialFromPlusletSection($special_pluslet['pluslet_section_id']);
            }
        }

        // make sure they're allowed to delete
        //print "<p> session staff = " . $_SESSION["staff_id"] . " -- staff_id = " . $this->_staffers[0][0];
        // print "<pre>";
        //print_r($this->_ok_staff);
        if (!in_array($_SESSION["staff_id"], $this->_ok_staff) && $_SESSION["admin"] != "1") {
            $this->_message = _("You do not have permission to delete this guide.");
            return FALSE;
        }

        $hasMasterClones = $this->hasMasterPluslets($this->_subject_id);
        if($hasMasterClones == true) {
            $this->_message = _("This guide cannot be deleted because it contains master boxes " . "<a class=\"master-feedback-link\" href=\"index.php\">" . _("Back to Browse Guides.") . "</a>");
            return FALSE;
        }

        //is this a parent guide? if so, cannot delete because it will leave orphans
        $isParentGuide = $this->isParentGuide($this->_subject_id);
        if($isParentGuide == true) {
            $this->_message = _("This guide cannot be deleted because it is a parent guide. " . "<a class=\"master-feedback-link\" href=\"index.php\">" . _("Back to Browse Guides.") . "</a>");
            return FALSE;
        }

        //is this a child guide? If so, delete the relationship in subject_subject table
        $isChildGuide = $this->isChildGuide($this->_subject_id);
        if($isChildGuide == true) {
            $this->deleteParentChildRelationship($this->_subject_id);
        }


        $db = new Querier;

        // Delete the records from pluslet that are associated with subject
        $q = "DELETE p
        FROM pluslet p INNER JOIN pluslet_section ps
        ON p.pluslet_id = ps.pluslet_id
        INNER JOIN section sec
        ON ps.section_id = sec.section_id
        INNER JOIN tab t
        ON sec.tab_id = t.tab_id
        INNER JOIN subject s
        ON t.subject_id = s.subject_id
        WHERE p.type != 'Special' AND s.subject_id = '" . $this->_subject_id . "'";

        $delete_result = $db->exec($q);
        
        $this->_debug = "<p>Del query: $q";

        if (isset($delete_result)) {
            //delete subject
            $q2 = "DELETE subject,staff_subject FROM subject LEFT JOIN staff_subject ON subject.subject_id = staff_subject.subject_id WHERE subject.subject_id = '" . $this->_subject_id . "'";
            $delete_result2 = $db->exec($q2);
       
            $this->_debug .= "<p>Del query 2: $q2";
        } else {
            // message
            $this->_message = _("There was a problem with your delete (stage 1 of 2).");
            return FALSE;
        }

        if (isset($delete_result2)) {
            // message
            if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
                $this->_message = "<div class=\"master-feedback\" style=\"display:block;\">" . _("Thy will be done.  Offending Guide (and associated boxes) deleted. ") . "<a class=\"master-feedback-link\" href=\"index.php\">" . _("Back to Browse Guides.") . "</a></div>";
            } else {
                $this->_message = "<div class=\"master-feedback\" style=\"display:block;\">" . _("Thy will be done.  Offending Guide (and associated boxes) deleted. ") . "<a class=\"master-feedback-link\" href=\"index.php\">" . _("Back to Browse Guides.") . "</a></div>";
            }

            ///////////////////////
            // Alter chchchanges table
            // table, flag, item_id, title, staff_id
            ////////////////////

            $updateChangeTable = changeMe("guide", "delete", $this->_subject_id, $this->_subject, $_SESSION['staff_id']);

            return TRUE;
        } else {
            // message
            $this->_message = _("There was a problem with your delete (stage 2 of 2).");
            return FALSE;
        }
    }

    public function insertRecord()
    {
        $db = new Querier;

        // Make sure there isn't a guide with this title or shortform already
        $is_dupe = self::dupeCheck();

        if ($is_dupe == TRUE) {
            $this->_message = _("There is already a guide with this SHORTFORM.  The shortform must be unique.");
            return;
        }

        //////////////////
        // Encode our extra as json
        /////////////////

        $json_extra = json_encode($this->_extra);

        /////////////////////
        // update subject table
        /////////////////////

        $qInsertSubject = "INSERT INTO subject (subject, shortform, description, keywords, redirect_url, active, type, header, extra, course_code, instructor) VALUES (
        " . $db->quote(scrubData($this->_subject, "text")) . ",
        " . $db->quote(scrubData($this->_shortform, "text")) . ",
        " . $db->quote(scrubData($this->_description, "text")) . ",
        " . $db->quote(scrubData($this->_keywords, "text")) . ",
        " . $db->quote(scrubData($this->_redirect_url, "text")) . ",
        " . $db->quote(scrubData($this->_active, "integer")) . ",
        " . $db->quote(scrubData($this->_type, "text")) . ",
        " . $db->quote(scrubData($this->_header, "text")) . ",
        " . $db->quote($json_extra) . ",
        " . $db->quote(scrubData($this->_course_code, "text")) . ",
        " . $db->quote(scrubData($this->_instructor, "text")) . "     
        )";

        $db = new Querier;
        $rInsertSubject = $db->exec($qInsertSubject);

        $this->_subject_id = $db->last_id();


        $this->_debug = "<p>1. insert subject: $qInsertSubject</p>";
        if (!$rInsertSubject) {
            echo blunDer("We have a problem with the title query: $qInsertSubject");
        }

        /////////////////////
        // insert into staff_subject
        ////////////////////

        self::modifySS();

        /////////////////////
        // insert into subject_discipline
        ////////////////////

        self::modifySD();

        ///////////////////
        // create inital tab
        ///////////////////

        self::modifyTabs();

        // /////////////////////
        // Alter chchchanges table
        // table, flag, item_id, title, staff_id
        ////////////////////

        $updateChangeTable = changeMe("guide", "insert", $this->_subject_id, $this->_subject, $_SESSION['staff_id']);

        /////////////////////
        // insert into subject_subject for parent-child
        ////////////////////

        self::modifySubSub();


        // message
        $this->_message = _("Thy Will Be Done.") . " <a class=\"master-feedback-link\" href=\"guide.php?subject_id=" . $this->_subject_id . "\">" . _("Add Content To Your New Guide") . "</a>";
    }

    public function updateRecord()
    {
        global $use_disciplines;

        // Check to make sure the title or shortform haven't been changed to create dupes
        $is_dupe = self::dupeCheck();

        if ($is_dupe == TRUE) {
            $this->_message = _("There is already a guide with this SHORTFORM.  The shortform must be unique.");
            return;
        }

        //print_r($_POST);

        //////////////////
        // Encode our extra as json
        /////////////////

        $json_extra = json_encode($this->_extra);


        /////////////////////
        // update subject table
        /////////////////////
	   $db = new Querier();

    	$qUpSubject = "UPDATE subject SET subject = " . $db->quote(scrubData($this->_subject, "text")) . ",
        shortform = " . $db->quote(scrubData($this->_shortform, "text")) . ",
        description = " . $db->quote(scrubData($this->_description, "text")) . ",
        keywords = " . $db->quote(scrubData($this->_keywords, "text")) . ",
        redirect_url = " . $db->quote(scrubData($this->_redirect_url, "text")) . ",
        active = " . $db->quote(scrubData($this->_active, "integer")) . ",
        type = " . $db->quote(scrubData($this->_type, "text")) . ",
        header = " . $db->quote(scrubData($this->_header, "text")) . ",
        extra = " . $db->quote($json_extra) . ",
        course_code = " . $db->quote(scrubData($this->_course_code, "text")) . ",
        instructor = " . $db->quote(scrubData($this->_instructor, "text")) . "
        WHERE subject_id = " . scrubData($this->_subject_id, "integer");

        $rUpSubject = $db->exec($qUpSubject);

        /////////////////////
        // clear staff_subject
        /////////////////////

        $qClearSS = "DELETE FROM staff_subject WHERE subject_id = " . $this->_subject_id;

        $rClearSS = $db->exec($qClearSS);

        $this->_debug .= "<p>2. clear staff_subject: $qClearSS</p>";

        /////////////////////
        // insert into staff_subject
        ////////////////////

        self::modifySS();


        /////////////////////
        // clear subject_subject -- for parent-child relationships
        /////////////////////


        $qClearSubS = "DELETE FROM subject_subject WHERE subject_child = " . $this->_subject_id;

        $rClearSubS = $db->exec($qClearSubS);

        $this->_debug .= "<p>2. clear subject_subject: $qClearSubS</p>";


        /////////////////////
        // insert into subject_subject
        ////////////////////

        self::modifySubSub();

        /////////////////////
        // clear subject_discipline
        /////////////////////

        if ($use_disciplines == TRUE) {

            $qClearSD = "DELETE FROM subject_discipline WHERE subject_id = " . $this->_subject_id;

            $rClearSD = $db->query($qClearSD);

            $this->_debug .= "<p>2. clear subject_discipline: $qClearSD</p>";

            /////////////////////
            // insert into subject_discipline
            ////////////////////

            self::modifySD();

        }
        // /////////////////////
        // Alter chchchanges table
        // table, flag, item_id, title, staff_id
        ////////////////////

        $updateChangeTable = changeMe("guide", "update", $this->_subject_id, $this->_subject, $_SESSION['staff_id']);

        if (isset($_REQUEST["wintype"]) && $_REQUEST["wintype"] == "pop") {
        	$this->_message = _("Thy Will Be Done.  Guide updated.");
        	 
        } else 
        { 
        	
        	$this->_message = _("Thy Will Be Done.") . " <a href=\"guide.php?subject_id=" . $this->_subject_id . "\">" . _("Add Content To Your New Guide") . "</a>";
        }
        
    }

    public function getTabs( $lstrFilter = "" )
    {
        if (isset($this->_all_tabs) && $lstrFilter == "") {
            return $this->_all_tabs;
        }

        $db = new Querier;

    	switch( $lstrFilter )
    	{
    		case 'hidden':
    			// Find our existing tabs for this guide that is hidden
    			$qtab = "SELECT DISTINCT tab_id, label, tab_index, external_url, visibility FROM tab WHERE subject_id = '{$this->_subject_id}' AND visibility = 0 ORDER BY tab_index";
    			break;
    		case 'public':
    			// Find our existing tabs for this guide that is public
    			$qtab = "SELECT DISTINCT tab_id, label, tab_index, external_url, visibility FROM tab WHERE subject_id = '{$this->_subject_id}' AND visibility = 1 ORDER BY tab_index";
    			break;
    		default:
    			// Find ALL our existing tabs for this guide
    			$qtab = "SELECT DISTINCT tab_id, label, tab_index, external_url, visibility FROM tab WHERE subject_id = '{$this->_subject_id}' ORDER BY tab_index";
    			break;
    	}

    	
        $rtab = $db->query($qtab);

        $all_tabs = array();

        foreach ($rtab as $myrow) {
            $tab['label'] = $myrow[1];
            $tab['external_url'] = $myrow[3];
            $tab['visibility'] = $myrow[4];
            $tab['tab_id'] = $myrow[0];
            $all_tabs[$myrow[2]] = $tab;
        }

        $this->_all_tabs = $all_tabs;
        return $this->_all_tabs;
    }

    public function outputNavTabs( $lstrFilter = "" )
    {
        global $IconPath;
        global $HomeTabText;

        if (isset($HomeTabText)) {
            $home_tab_text = $HomeTabText;
            $home_tab_class = "";
        } else {
            //$home_tab_text = "<img src=\"$IconPath/home_spacer.png\" alt=\"Home\" title=\"Home\" />";
            $home_tab_class = "hometab";
        }

        $all_tabs = $this->getTabs($lstrFilter);
        $main_tabs = $this->db->query("SELECT tab_id FROM tab WHERE parent = '' AND children =  '[]'");
        
        $tabs = $this->_isAdmin ? "<ul><li id=\"add_tab\"><i class=\"fa fa-plus\"></i></li>" : "<ul>"; // init tabs (in header of body of guide)
        foreach ($all_tabs as $key => $lobjTab) {
        	
        $children = $this->db->query("SELECT children FROM tab WHERE tab_id = {$lobjTab['tab_id']}");
        $child_ids = array();
        	 
        foreach($children as $child) {
           $decoded_children = json_decode($child[0]); 	
           if ($decoded_children) {
               foreach($decoded_children as $decoded_child) {

                    $child_id = $decoded_child->child;
                     array_push($child_ids, $child_id);
               }
           }
        }

        $childs = implode($child_ids, ',');
        
        	// Modded to handle tab children
        	
        $class = "dropspotty";
        $class .= $lobjTab['visibility'] == 0 ? ' hidden_tab' : '';
        
        // Output the tabs
        
        if (!$this->_isAdmin && $key == 0) {
        	// Home tab
        	
        	$tabs .= "<li id=\"{$lobjTab['tab_id']}\" class=\"$class\" style=\"height: auto;\" data-external-link=\"{$lobjTab['external_url']}\" data-visibility=\"{$lobjTab['visibility']}\"><a href=\"#tabs-$key\" class=\"$home_tab_class\">{$lobjTab['label']}</a>";
        
        } else {
        		
        		if (!empty($childs)) {
        			// Parents
        			$tabs .= "<li id=\"{$lobjTab['tab_id']}\" data-children=\"$childs\"  class=\"$class parent-tab\" style=\"height: auto;\" data-external-link=\"{$lobjTab['external_url']}\" data-visibility=\"{$lobjTab['visibility']}\"><a href=\"#tabs-$key\">{$lobjTab['label']}</a>";
        			 
        		} else {
        			// Children 
        			$tabs .= "<li id=\"{$lobjTab['tab_id']}\"  class=\"$class child-tab\" style=\"height: auto;\" data-external-link=\"{$lobjTab['external_url']}\" data-visibility=\"{$lobjTab['visibility']}\"><a href=\"#tabs-$key\">{$lobjTab['label']}</a>";
        			 
        		}
        		        		
        		
        	
        }
        $tabs .= $this->_isAdmin ? "<span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>" : "</li>";
        }
        
        $tabs .= "<li id=\"expand_tab\"><i class=\"fa fa-chevron-up\"></i></li>"; //place collapse/expand trigger here
        $tabs .= "</ul>"; // close out our tabs
        
        $tabs .= $this->_isAdmin ? "" : "<div id=\"shadowkiller\"></div>"; // this div allows me to cover bottom of box-shadow on tabs.  crazy, bien sur
        
        	
        
        echo $tabs;
    }

    public function outputMobile( $lstrFilter = "" )
    {
        global $HomeTabText;

        if (isset($HomeTabText)) {
            $home_tab_class = "";
        } else {
            $home_tab_class = "hometab";
        }

        $all_tabs = $this->getTabs($lstrFilter);

        $tabs_mobile = $this->_isAdmin ? "" : "<select id=\"select_tabs\"><option></option>"; // init tabs in mobile as select
        foreach ($all_tabs as $key => $lobjTabMobile) {

            $children = $this->db->query("SELECT children FROM tab WHERE tab_id = {$lobjTabMobile['tab_id']}");
            $child_ids = array();

            foreach($children as $child) {
                $decoded_children = json_decode($child[0]);
                if ($decoded_children) {
                    foreach($decoded_children as $decoded_child) {

                        $child_id = $decoded_child->child;
                        array_push($child_ids, $child_id);
                    }
                }
            }

            $childs = implode($child_ids, ',');

            $class = "";
            $class .= $lobjTabMobile['visibility'] == 0 ? ' hidden_tab' : '';

            // Output the tabs as options with value

            if (!$this->_isAdmin && $key == 0) {

                $tabs_mobile .= "<option id=\"{$lobjTabMobile['tab_id']}\" class=\"$home_tab_class $class\" data-external-link=\"{$lobjTabMobile['external_url']}\" data-visibility=\"{$lobjTabMobile['visibility']}\" value =\"#tabs-$key\">{$lobjTabMobile['label']}</option>";

            } else {

                if (!empty($childs)) {
                    // Parents
                    $tabs_mobile .= "<option id=\"{$lobjTabMobile['tab_id']}\" data-children=\"$childs\" class=\"parent-tab $class\" data-external-link=\"{$lobjTabMobile['external_url']}\" data-visibility=\"{$lobjTabMobile['visibility']}\" value =\"#tabs-$key\">{$lobjTabMobile['label']}</option>";

                } else {
                    // Children
                    $tabs_mobile .= "<option id=\"{$lobjTabMobile['tab_id']}\" class=\"child-tab $class\"  data-external-link=\"{$lobjTabMobile['external_url']}\" data-visibility=\"{$lobjTabMobile['visibility']}\" value =\"#tabs-$key\">{$lobjTabMobile['label']}</option>";
                }
            }
            $tabs_mobile .= $this->_isAdmin ? "" : "";
        }

        $tabs_mobile .= "</select>"; // close mobile select tabs

        echo $tabs_mobile;
    }

    public function outputTabs( $lstrFilter = "" )
    {
        $all_tabs = $this->getTabs($lstrFilter);

        // Now loop through tab content
        foreach ($all_tabs as $key => $lobjTab) {
            print "<div id=\"tabs-$key\" class=\"sptab\">";
            // get our content
            $this->dropTabs($key);
            print "</div><!-- close div $key -->"; // close tab div
        }
    }

    public function dropTabs($selected_tab = 0)
    {
    	global $IconPath;

		//get all sections for current tab
    	$q = "SELECT sec.*
    		  FROM section sec
    		  INNER JOIN tab t
    		  ON sec.tab_id = t.tab_id
    		  INNER JOIN subject s
    		  ON t.subject_id = s.subject_id
    		  WHERE t.tab_index = $selected_tab
    		  AND s.subject_id = '{$this->_subject_id}'
    		  ORDER BY section_index ASC";

    	$db = new Querier;
    	$lobjSections = $db->query($q);

    	foreach( $lobjSections as $lobjSection )
    	{
    		print "<div id=\"section_{$lobjSection['section_id']}\" class=\"sp_section pure-g\" data-layout=\"{$lobjSection['layout']}\">";

            if ($this->_isAdmin) {
        		print "<div class=\"sp_section_controls\">
    						<i class=\"fa fa-arrows section_sort\" title=\"Move Section\"></i>
                            <i class=\"fa fa-trash-o section_remove\" title=\"Delete Section\"></i>

    						<div id=\"slider_section_{$lobjSection['section_id']}\"  class=\"sp_section_slider\"></div>
    				   </div>";
            }
    		$qc = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra
		           FROM pluslet p
		           INNER JOIN pluslet_section ps
		           ON p.pluslet_id = ps.pluslet_id
		           INNER JOIN section sec
		           ON ps.section_id = sec.section_id
		           WHERE sec.section_id = {$lobjSection['section_id']}
		           ORDER BY prow ASC";

    		$rc = $db->query($qc);

    		//init
    		$left_col_pluslets = "";
    		$main_col_pluslets = "";
    		$sidebar_pluslets = "";

    		foreach ($rc as $myrow) {

    			// Get our guide type
    			// Make sure it's not blank, as that will throw an error
    			if ($myrow["type"] != "") {

    				if ($myrow["type"] == "Special") {
    					$obj = __NAMESPACE__ . "\Pluslet_" . $myrow[0];
    				} else {
    					$obj = __NAMESPACE__ . "\Pluslet_" . $myrow[4];
    				}


    				//global $obj;
    				$record = new $obj($myrow[0], "", $this->_subject_id);
    				$view = $this->_isAdmin ? "admin" : "public";

    				switch ($myrow[3]) {
    					case 0:
    						# code...
    						$left_col_pluslets .= $record->output("view", $view);
    						break;
    					case 1:
    					default:
    						# code...
    						$main_col_pluslets .= $record->output("view", $view);
    						break;
    					case 2:
    						# code...
    						$sidebar_pluslets .= $record->output("view", $view);
    						break;
    				}

    			}
    			unset($record);
    		}

    		if ($this->_isAdmin) {
    			print $this->dropBoxes(0, 'left', $left_col_pluslets);
    			print $this->dropBoxes(1, 'center', $main_col_pluslets);
    			print $this->dropBoxes(2, 'sidebar', $sidebar_pluslets);
    			print '<div id="clearblock" style="clear:both;"></div> <!-- this just seems to allow the space to grow to fit dropbox areas -->';
    		} else {

    			$col_widths = explode("-", $lobjSection['layout']);
    			$purified = ""; // init

    			if (isset($col_widths[0]) && $col_widths[0] > 0) {
    				$purified = reduce($col_widths[0], 12);
    				$pure_left = "pure-u-1 pure-u-md-" . $purified[0] . "-" . $purified[1];
    				$left_width = $col_widths[0];
    			} else {
    				$left_width = 0;
    			}

    			if (isset($col_widths[1])) {
    				$purified = reduce($col_widths[1], 12);
    				$pure_center = "pure-u-1 pure-u-md-" . $purified[0] . "-" . $purified[1];
    				$main_width = $col_widths[1];
    			} else {
    				$main_width = 0;
    			}

    			if (isset($col_widths[2]) && $col_widths[2] > 0) {
    				$purified = reduce($col_widths[2], 12);
    				$pure_right = "pure-u-1 pure-u-md-" . $purified[0] . "-" . $purified[1];
    				$side_width = $col_widths[2];
    			} else {
    				$side_width = 0;
    			}

    				// make sure they aren't 0

    				if ($left_width > 0) {
    					/*print "<div class='span$left_width'>
    					   $left_col_pluslets
    					   </div>";*/
    					print "<div class='$pure_left'>
    					$left_col_pluslets
    					</div>";
    				}

    				if ($main_width > 0) {
    					print "<div class='$pure_center'>
    					$main_col_pluslets
    					</div>";

    					/*print "<div class='span$main_width'>
    					   $main_col_pluslets
    					   </div>";*/
    				}

    				if ($side_width > 0) {
    					print "<div class='$pure_right'>
    					$sidebar_pluslets
    					</div>";

    					/*print "<div class='span$side_width'>
    					   $sidebar_pluslets
    					   </div>";*/
    				}

    		}

    		print "</div>";
    	}
    }

    public function dropBoxes($i, $itext, $content)
    {
        global $AssetPath;
        global $IconPath;
        $col = "<div id=\"container-" . $i . "\" class='pure-u-1-3'  \">
            <div class=\"container-area\">
                <div class=\"dropspotty unsortable drop_area\" id=\"dropspot-" . $itext . "-1\">       
                    <span class=\"dropspot-text\"> <i class=\"fa fa-dot-circle-o fa-lg\"></i> " . _('Drop Here') . "</span>
                </div>
        
                <div class=\"portal-column sort-column portal-column-" . $i . "\" class=\"float-left\"> " .
                $content . "<div></div>"
            . '</div></div></div>';

        return $col;
    }

    function modifySS()
    {

        $de_duped = array_unique($this->_staff_id);

        $i = 0;
        foreach ($de_duped as $value) {
            if (is_numeric($value)) {
                $db = new Querier;
                $qUpSS = "INSERT INTO staff_subject (staff_id, subject_id, staff_guide_order) VALUES (
				" . scrubData($value, 'integer') . ",
				" . scrubData($this->_subject_id, 'integer') . ",
				" . $i++ . ")";
                $db = new Querier;
                $rUpSS = $db->exec($qUpSS);

                $this->_debug .= "<p>3. (insert staff_subject loop) : $qUpSS</p>";

            }
        }
    }

    function modifySubSub()
    {

        $de_duped = array_unique($this->_parent_id);

        foreach ($de_duped as $value) {
            if (is_numeric($value)) {
                $db = new Querier;
                $qUpSS = "INSERT INTO subject_subject (subject_child, subject_parent) VALUES (
                " . scrubData($this->_subject_id, 'integer') . ",
                " . scrubData($value, 'integer') . ")";
                $db = new Querier;
                $rUpSS = $db->exec($qUpSS);

                $this->_debug .= "<p>3. (insert subject_subject loop) : $qUpSS</p>";

            }
        }
    }

    function modifySD()
    {
        global $use_disciplines;
        if ($use_disciplines != TRUE) {
            return;
        }

        $de_duped = array_unique($this->_discipline_id);

        foreach ($de_duped as $value) {
            if (is_numeric($value)) {
                $db = new Querier;
                $qUpSD = "INSERT INTO subject_discipline (subject_id, discipline_id) VALUES (
                " . scrubData($this->_subject_id, 'integer') . ",
                " . scrubData($value, 'integer') . ")";

                $rUpSD = $db->exec($qUpSD);

                $this->_debug .= "<p>3. (insert subject_discipline loop) : $qUpSD</p>";
                if (!$rUpSD) {
                    echo blunDer("We have a problem with the subject_discipline query: $qUpSD");
                }
            }
        }
    }

    function modifyTabs()
    {
        $db = new Querier;
        $lstrQuery = "INSERT INTO tab (subject_id, tab_index) VALUES ('"
            . scrubData($this->_subject_id, "integer") . "', '0')";

        $rscResponse = $db->exec($lstrQuery);

        $this->_debug .= "<p>4. (insert new tab) : $lstrQuery</p>";
        if (!$rscResponse) {
            echo blunDer("We have a problem with the new tab query: $rscResponse");
        }
    }

    function prepareDisciplines()
    {
        // get all disciplines currently showing in discipline list
        // de-dupe

    }

    function dupeCheck()
    {
        $db = new Querier;

        // returns TRUE is there is already an item with that subject or shortform
        if ($this->_subject_id == "") {
            // INSERT
            $qcheck = "SELECT shortform FROM subject WHERE shortform = " . $db->quote(scrubData($this->_shortform));
        } else {
            // UPDATE
            $qcheck = "SELECT shortform FROM subject WHERE shortform = " . $db->quote(scrubData($this->_shortform)) . " AND subject_id != " . $this->_subject_id;
        }
        //print $qcheck;
        $db = new Querier;
        $rcheck = $db->query($qcheck);

        $this->_debug .= "<p>Dupe check: $qcheck</p>";

        if (count($rcheck) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function getMessage()
    {
        return $this->_message;
    }

    function getRecordId()
    {
        return $this->_subject_id;
    }

    //returns title ids that are found in current guide
    public function getRelatedTitles()
    {
        $db = new Querier();

        //get title ids in pluslets' resource token connected to guide
        $q = "SELECT p.body
            FROM subject AS s
            INNER JOIN tab AS tb ON s.subject_id = tb.subject_id
            LEFT JOIN section AS sc ON tb.tab_id = sc.tab_id
            LEFT JOIN pluslet_section AS ps ON sc.section_id = ps.section_id
            LEFT JOIN pluslet AS p ON ps.pluslet_id = p.pluslet_id
            WHERE p.body LIKE  '%{{dab}%'
            AND s.subject_id = $this->_subject_id";

        $lobjResults = $db->query($q);
        $lobjMatches = array();
        $lobjTitleIds = array();

        foreach( $lobjResults as $lobjResult )
        {
            preg_match_all( '/\{\{dab\},\{([^}]*)\}/', $lobjResult['body'] , $lobjMatches );
            $lobjTitleIds = array_merge($lobjTitleIds, $lobjMatches[1]);
        }

        return $lobjTitleIds;
    }

    function deBug()
    {
        print $this->_debug;
    }
    
    public  function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

	public function checkVisibility()
	{
		global $BaseURL;

		switch( $this->_active )
		{
			case 0: //direct URL so return true for hidden or public
			case 1:
				return TRUE;
				break;
			case 2: //suppressed so check ptag guide and is logged in
				session_start();

				if( isset( $_SESSION['staff_id'] ) && isset( $_SESSION['records'] ) && $_SESSION['records'] == 1 )
				{
					return TRUE;
				}else
				{
                    header("location:{$BaseURL}subjects/noguide.php");
					return FALSE;
				}
				break;
			default: //not implemented to redirect to index page
				header("location:{$BaseURL}subjects/index.php");
				return FALSE;
				break;
		}
	}

}
