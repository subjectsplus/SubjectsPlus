<?php
namespace SubjectsPlus\Control;
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
    private $_department;
    private $_parents;
    private $_header;
    private $_debug;

    public $_ok_staff = array();
    public $main_col_size;

    public $_isAdmin;

    public function __construct($subject_id = "", $flag = "")
    {

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
                $this->_department = $_POST['department'];
                $this->_header = $_POST['header'];

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
                $q1 = "select subject_id, subject, active, shortform, description, keywords, redirect_url, type, extra, header from subject where subject_id = " . $this->_subject_id;
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
        $q2 = "SELECT s.staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff s, staff_subject ss WHERE s.staff_id = ss.staff_id AND ss.subject_id = " . $this->_subject_id;

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

        if ($this->_subject_id) {
            $guide_title_line = _("Edit Existing Guide Metadata");
        } else {
            $guide_title_line = _("Create New Guide");
        }

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

            <label for=\"record_title\">" . _("Guide") . "</label>
            <input type=\"text\" name=\"subject\" id=\"record_title\" class=\"pure-input-1-2 required_field\" value=\"" . $this->_subject . "\">

            <label for=\"record_shortform\">" . _("Short Form") . "</label>
            <input type=\"text\" name=\"shortform\" id=\"record_shortform\" size=\"20\" class=\"pure-input-1-4 required_field\" value=\"" . $this->_shortform . "\">

            <span class=\"smaller\">* " . _("Short label that shows up in URL--don't use spaces, ampersands, etc.") . "</span>

            <label for=\"type\">" . _("Type of Guide") . "</label>
            ";

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

        /////////
        // Department dropdown
        ////////

        $querier = new Querier();
        $dept_query = "SELECT department_id, name FROM department;";
        $deptArray = $querier->query($dept_query);


        $current_dept = new Querier();
        $current_dept_query =
            "SELECT DISTINCT subject.subject, subject.subject_id, department.name, department.department_id, subject_department.date
            FROM subject_department
            JOIN subject ON subject.subject_id = subject_department.id_subject
            JOIN department ON department.department_id = subject_department.id_department
            WHERE subject.subject_id = '$this->_subject_id'
            ORDER BY date DESC
            LIMIT 1";

        $current_dept_array = $current_dept->query($current_dept_query);
        
      
        print "
        </div>
        </div>
        <div class=\"pluslet\">
        
                    <div class=\"titlebar\">
                      <div class=\"titlebar_text\">" . _("Associations/Listings (optional)") . "</div>
                      <div class=\"titlebar_options\"></div>
                    </div>
                <div class=\"pluslet_body\">
                <span class=\"smaller\"> " . _("<strong>Department</strong> lets you group guides to provide a separate listing for a group of guides, say, Special Collections.  
                <br /><strong>Parent Guide</strong> allows you to build a hierarchy for display.") . "</span>";
        ?>

            <label for="department"> Department </label>


            <select name="department">

<?php
    if ($current_dept_array) {
        foreach ($current_dept_array as $dept) {
            echo "<option value='" . $dept["department_id"] . "'>" . $dept["name"] . "</option>";
        }
    } else {
        
        print "<option value='0'>--none--</option>";
        
        foreach ($deptArray as $dept) {
            echo "<option value='" . $dept["department_id"] . "'>" . $dept["name"] . "</option>";
        }
    }
    ?>

            </select>
            
        <?php
 
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
        $subject_query = "SELECT subject_id, subject FROM subject WHERE subject_id != '$this->_subject_id'";
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
    $last_mod = _("Last modified: ") . lastModded("record", $this->_subject_id);
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

        $qStaff = "select staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE ptags LIKE '%records%' ORDER BY lname, fname";

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

        $staff_box = "$staff_string <div id=\"item_list\">$staffer_list</div> <!-- staff inserted here -->";

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


        echo "</div>\n</form>";
    }

    public function outputStaff($value)
    {
        global $IconPath;

        $ourstaff = "
        <div class=\"selected_item_wrapper\">
        <div class=\"selected_item\">
        <input name=\"staff_id[]\" value=\"$value[0]\" type=\"hidden\" />
        $value[1]<br />
        </div>
        <div class=\"selected_item_options\">
        <img src=\"$IconPath/delete.png\" class=\"delete_item\" alt=\"" . _("delete") . "\" title=\"" . _("delete") . "\" border=\"0\">
        </div>
        </div>";

        return $ourstaff;
    }

    public function outputParents($value)
    {
        global $IconPath;

        $ourparents = "
        <div class=\"selected_item_wrapper\">
        <div class=\"selected_item\">
        <input name=\"parent_id[]\" value=\"$value[0]\" type=\"hidden\" />
        $value[1]<br />
        </div>
        <div class=\"selected_item_options\">
        <img src=\"$IconPath/delete.png\" class=\"delete_item\" alt=\"" . _("delete") . "\" title=\"" . _("delete") . "\" border=\"0\">
        </div>
        </div>";

        return $ourparents;
    }

    public function outputDisciplines($value)
    {
        global $IconPath;

        $ourdisciplines = "
        <div class=\"selected_item_wrapper\">
        <div class=\"selected_item\">
        <input name=\"discipline_id[]\" value=\"$value[0]\" type=\"hidden\" />
        $value[1]<br />
        </div>
        <div class=\"selected_item_options\">
        <img src=\"$IconPath/delete.png\" class=\"delete_item\" alt=\"" . _("delete") . "\" title=\"" . _("delete") . "\" border=\"0\">
        </div>
        </div>";

        return $ourdisciplines;
    }

    public function deleteRecord()
    {

        // make sure they're allowed to delete
        //print "<p> session staff = " . $_SESSION["staff_id"] . " -- staff_id = " . $this->_staffers[0][0];
        // print "<pre>";
        //print_r($this->_ok_staff);
        if (!in_array($_SESSION["staff_id"], $this->_ok_staff) && $_SESSION["admin"] != "1") {
            $this->_message = _("You do not have permission to delete this guide.");
            return FALSE;
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
                $this->_message = _("Thy will be done.  Offending Guide (and associated boxes) deleted.  Close window to continue.");
            } else {
                $this->_message = _("Thy will be done.  Offending Guide (and associated boxes) deleted.");
            }

            // /////////////////////
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

        $qInsertSubject = "INSERT INTO subject (subject, shortform, description, keywords, redirect_url, active, type, header, extra) VALUES (
        " . $db->quote(scrubData($this->_subject, "text")) . ",
        " . $db->quote(scrubData($this->_shortform, "text")) . ",
        " . $db->quote(scrubData($this->_description, "text")) . ",
        " . $db->quote(scrubData($this->_keywords, "text")) . ",
        " . $db->quote(scrubData($this->_redirect_url, "text")) . ",
        " . $db->quote(scrubData($this->_active, "integer")) . ",
        " . $db->quote(scrubData($this->_type, "text")) . ",
        " . $db->quote(scrubData($this->_header, "text")) . ",
        " . $db->quote($json_extra) . "
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


        // Insert subject_department relationship
        $insert_department = new Querier();
        $dept_query = "INSERT INTO subject_department (id_subject, id_department) VALUES ('$this->_subject_id ', '$this->_department')";
        $insert_department->exec($dept_query);
        
        //print_r ($insert_department);

        /////////////////////
        // insert into subject_subject for parent-child
        ////////////////////

        self::modifySubSub();


        // message
        $this->_message = _("Thy Will Be Done.") . " <a href=\"guide.php?subject_id=" . $this->_subject_id . "\">" . _("Add Content To Your New Guide") . "</a>";
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
        extra = " . $db->quote($json_extra) . "
        WHERE subject_id = " . scrubData($this->_subject_id, "integer");

        $rUpSubject = $db->exec($qUpSubject);


        // Insert subject_department relationship
        $insert_department = new Querier();
        $dept_query = "INSERT INTO subject_department (subject_id, department_id) VALUES ('$this->_subject_id ', '$this->_department')";
        $insert_department->exec($dept_query);

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

        // message
        $this->_message = _("Thy Will Be Done.  Guide updated.");
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

            $all_tabs[$myrow[2]] = $tab;
        }

        $this->_all_tabs = $all_tabs;
        return $this->_all_tabs;
    }

    public function outputNavTabs( $lstrFilter = "" )
    {
        global $IconPath;
        global $HomeTabText;
        $HomeTabText = "Main";

        if (isset($HomeTabText)) {
            $home_tab_text = $HomeTabText;
            $home_tab_class = "";
        } else {
            $home_tab_text = "<img src=\"$IconPath/home-white.png\" />";
            $home_tab_class = "hometab";
        }

        $all_tabs = $this->getTabs($lstrFilter);

        $tabs = $this->_isAdmin ? "<ul><li id=\"add_tab\">+</li>" : "<ul>"; // init tabs (in header of body of guide)
        foreach ($all_tabs as $key => $lobjTab) {
        	$class = "dropspotty";
        	$class .= $lobjTab['visibility'] == 0 ? ' hidden_tab' : '';
            if (!$this->_isAdmin && $key == 0) {
            $tabs .= "<li class=\"$class\" style=\"height: auto;\" data-external-link=\"{$lobjTab['external_url']}\" data-visibility=\"{$lobjTab['visibility']}\"><a href=\"#tabs-$key\" class=\"$home_tab_class\">$home_tab_text</a>";

            } else {
            $tabs .= "<li class=\"$class\" style=\"height: auto;\" data-external-link=\"{$lobjTab['external_url']}\" data-visibility=\"{$lobjTab['visibility']}\"><a href=\"#tabs-$key\">{$lobjTab['label']}</a>";

            }
            $tabs .= $this->_isAdmin ? "<span class='ui-icon ui-icon-wrench alter_tab' role='presentation'>Remove Tab</span></li>" : "</li>";
        }

        //$tabs .= "<li><a id=\"newtab\" href=\"#tabs-new\">{+}</a></li>";
        $tabs .= "</ul>"; // close out our tabs

        $tabs .= $this->_isAdmin ? "" : "<div id=\"shadowkiller\"></div>"; // this div allows me to cover bottom of box-shadow on tabs.  crazy, bien sur

        echo $tabs;
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
    		print "<div id=\"section_{$lobjSection['section_id']}\" class=\"sp_section\" data-layout=\"{$lobjSection['layout']}\">";

            if ($this->_isAdmin) {
        		print "<div class=\"sp_section_controls\">
    						<img src=\"$IconPath/hand_cursor-26.png\" class=\"section_sort\" />
    						<img src=\"$IconPath/delete.png\" class=\"section_remove\" />
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
        $col = "<div id=\"container-" . $i . "\" style=\"position: relative; float: left; width: 30%;\">
        <div class=\"dropspotty unsortable\" id=\"dropspot-" . $itext . "-1\">
        <img src=\"$IconPath/air_force-26.png\"  alt=\"" . _('Drop Content Here') . "\" />
        <span class=\"dropspot-text\">" . _('Drop Here') . "</span>
        </div>
        <div class=\"portal-column sort-column portal-column-" . $i . "\" class=\"float-left\"> " .
            $content . "<div><br /></div>"
            . '</div></div>';

        return $col;
    }

    function modifySS()
    {

        $de_duped = array_unique($this->_staff_id);

        foreach ($de_duped as $value) {
            if (is_numeric($value)) {
                $db = new Querier;
                $qUpSS = "INSERT INTO staff_subject (staff_id, subject_id) VALUES (
				" . scrubData($value, 'integer') . ",
				" . scrubData($this->_subject_id, 'integer') . ")";
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
					global $AssetPath;
					$page_title = 'Guide Unavailable';

					$body = 'This guide is currently unavailable. It may be under maintenance, or just resting.<br />';
					$body .= '<a href="index.php">Find another guide.</a>';

					include(dirname(dirname(dirname(dirname(__FILE__)))) . "/subjects/includes/header.php");
					makePluslet('Guide Not Public', $body, "no_overflow");
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

?>
