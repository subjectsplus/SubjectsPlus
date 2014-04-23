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
    private $_parent;


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
                $this->_parent = $_POST['parent'];

                //add http to redirect url if not present
                $this->_redirect_url = strpos($this->_redirect_url, "http://") === 0 || strpos($this->_redirect_url, "https://") === 0
                || $this->_redirect_url == "" ? $this->_redirect_url : "http://" . $this->_redirect_url;

                // data stored in staff_subject table
                $this->_staff_id = $_POST["staff_id"]; // array
                $this->_staff_count = count($this->_staff_id); // # of items in above array

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
                $q1 = "select subject_id, subject, active, shortform, description, keywords, redirect_url, type, extra from subject where subject_id = " . $this->_subject_id;
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
                }

                ///////////////////
                // Query Staff table
                // used to get our set of staff associated
                // ////////////////

                self::getAssociatedStaff();

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
            <form action=\"" . $action . "\" method=\"post\" id=\"new_record\" accept-charset=\"UTF-8\">
            <input type=\"hidden\" name=\"subject_id\" value=\"" . $this->_subject_id . "\" />
            <div style=\"float: left; margin-right: 20px;\">
            <div class=\"box\">
            <h2 class=\"bw_head\">$guide_title_line</h2>

            <span class=\"record_label\">" . _("Guide") . "</span><br />
            <input type=\"text\" name=\"subject\" id=\"record_title\" size=\"50\" class=\"required_field\" value=\"" . $this->_subject . "\">

            <span class=\"record_label\">" . _("Short Form") . "</span><br />
            <input type=\"text\" name=\"shortform\" id=\"record_shortform\" size=\"20\" class=\"required_field\" value=\"" . $this->_shortform . "\">
            <br />
            <span class=\"smaller\">* " . _("Short label for subject--minus spaces, ampersands, etc.") . "</span>

            <p>
            <span class=\"record_label\">" . _("Type of Guide") . "</span>
            ";

        /////////////////////
        // Guide types dropdown
        /////////////////////

        $guideMe = new Dropdown("type", $guide_types, $this->_type, "50");
        $guide_string = $guideMe->display();
        echo $guide_string;

        ?>




        <?php

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

        ?>
        <p>
            <span class="record_label"> Department </span>


            <select name="department">
                <?php

                foreach ($deptArray as $dept) {
                    echo "<option value='" . $dept["department_id"] . "'>" . $dept["name"] . "</option>";
                }

                ?>

            </select>
        </p>

        <?php

        ////////
        // Parent dropdown
        ////////

        $querier = new Querier();
        $subject_query = "SELECT subject_id, subject FROM subject;";
        $subject_array = $querier->query($subject_query);

        $current_parent_querier = new Querier();
        $current_parent_query =
            "SELECT DISTINCT child.subject AS child_subject, child.subject_id AS child_id, parent.subject AS parent_subject, parent.subject_id AS parent_id, has.date, has.subject_parent
    FROM subject_subject AS has
    JOIN subject as child ON child.subject_id = has.subject_child
    JOIN subject as parent ON parent.subject_id = has.subject_parent
    WHERE child.subject_id = '$this->_subject_id'
    ORDER BY has.date DESC
    LIMIT 1";

        $current_parent_array = $current_parent_querier->query($current_parent_query);



        ?>
        <span class="record_label"> Parent Guide </span>
        <select name="parent">

            <option value="<?php if ($current_parent_array) {
                echo $current_parent_array[0]['parent_id'];
            } ?>">

                <?php if ($current_parent_array) {
                    echo $current_parent_array[0]['parent_id'];
                } ?> </option>

            <?php
            foreach ($subject_array as $subject) {
                echo "<option value='" . $subject["subject_id"] . "'>" . $subject["subject"] . "</option>";


            }
            ?>
        </select>




        <?php


        //////////////////////
        // Guide parent
        //////////////////////

        //echo "<span class='record_label'>" ._("Guide Parent") . "</span><br />";
        // To-do: Add dropdown to chose guide parent?


        /////////////////////
        // Is Live
        ////////////////////


        $is_live = "<span class=\"record_label\">" . _("Publish Guide (publicly accessible)?") . "</span><br />
    <input name=\"active\" type=\"radio\" value=\"1\"";
        if ($this->_active == 1) {
            $is_live .= " checked=\"checked\"";
        }
        $is_live .= " /> " . _("Yes") . " &nbsp;&nbsp;&nbsp; <input name=\"active\" type=\"radio\" value=\"0\"";
        if ($this->_active == 0) {
            $is_live .= " checked=\"checked\"";
        }
        $is_live .= " /> " . _("No") . "
    <br class=\"clear-both\" /><br />";

        ///////////////////
        // Screen layout
        // /////////////

        if (!empty($this->_extra)) {

            $jobj = $this->_extra;
            $main_col_size = $jobj['maincol'];

        } else {
            $main_col_size = "0-8-4";

        }
        $screen_layout = "<span class=\"record_label\">" . _("Column widths (add up to 12):") . " <span id=\"main_col_width\">$main_col_size</span></span><br />
    <div id=\"slider\"></div>
    <input type=\"hidden\" id=\"extra\" name=\"extra[maincol]\" value=\"$main_col_size\" />";


        echo "

    <br /><br />
    $is_live
    $screen_layout
    </div>
    </div>
    <!-- right hand column -->
    <div style=\"float: left;min-width: 200px;\">
	<div id=\"record_buttons\" class=\"box\">
    <input type=\"submit\" name=\"submit_record\" class=\"button save_button\" value=\"" . _("Save Now") . "\">";

        // if a) it's not a new record, and  b) we're an admin or c) we are listed as a librarian for this guide, show delete button
        // make sure they're allowed to delete

        if ($this->_subject_id != "") {
            if (in_array($_SESSION["staff_id"], $this->_ok_staff) || $_SESSION["admin"] == 1) {
                echo "<input type=\"submit\" name=\"delete_record\" class=\"button delete_button\" value=\"" . _("Delete Forever!") . "\">";
            }
            $last_mod = _("Last modified: ") . lastModded("guide", $this->_subject_id);
            echo "<div id=\"last_edited\">$last_mod</div>";
        }

        echo "</div>";

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

        echo "
    <div class=\"box no_overflow\" id=\"staff_menu\">
    <h2 class=\"bw_head\">" . _("Staff") . "</h2>

    $staff_string
    <div id=\"item_list\">$staffer_list</div> <!-- staff inserted here -->
    </div>
    <div class=\"box\"  id=\"metadata_menu\">
    <h2 class=\"bw_head\">" . _("Metadata") . "</h2>


    <span class=\"record_label\">" . _("Description") . "</span><br />
    <textarea name=\"description\" id=\"record_description\" class=\"\" cols=\"35\" rows=\"2\">" . $this->_description . "</textarea>
    <br />
    <br />
    <span class=\"record_label\">" . _("Keywords (separate with commas)") . "</span><br />
    <input type=\"text\" name=\"keywords\" id=\"record_keywords\" size=\"40\" class=\"\" value=\"" . $this->_keywords . "\">
    <br />
    <br />
    <span class=\"record_label\">" . _("Redirect Url") . "</span><br />
    <input type=\"text\" name=\"redirect_url\" id=\"record_redirect_url\" size=\"40\" class=\"\" value=\"" . $this->_redirect_url . "\">
    <br />
    <br />";

        if ($use_disciplines == TRUE) {

            echo "<span class=\"record_label\">" . _("Parent Disciplines") . "</span><br />
        $discipline_string
        <div id=\"discipline_list\">$discipliner_list</div> <!-- disciplines inserted here -->
        </div>";

        }


        echo "</div>
    </form>";
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

        if ($delete_result != 0) {
            //delete subject
            $q2 = "DELETE subject,staff_subject FROM subject LEFT JOIN staff_subject ON subject.subject_id = staff_subject.subject_id WHERE subject.subject_id = '" . $this->_subject_id . "'";
            $delete_result2 = $db->query($q2);
            $this->_debug .= "<p>Del query 2: $q2";
        } else {
            // message
            $this->_message = _("There was a problem with your delete (stage 1 of 2).");
            return FALSE;
        }

        if ($delete_result2) {
            // message
            if ($_GET["wintype"] == "pop") {
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

        $qInsertSubject = "INSERT INTO subject (subject, shortform, description, keywords, redirect_url, active, type, extra) VALUES (
        " . $db->quote(scrubData($this->_subject, "text")) . ",
        " . $db->quote(scrubData($this->_shortform, "text")) . ",
        " . $db->quote(scrubData($this->_description, "text")) . ",
        " . $db->quote(scrubData($this->_keywords, "text")) . ",
        " . $db->quote(scrubData($this->_redirect_url, "text")) . ",
        " . $db->quote(scrubData($this->_active, "integer")) . ",
        " . $db->quote(scrubData($this->_type, "text")) . ",
        " . $db->quote($json_extra) . "
        )";

        $db = new Querier;
        $rInsertSubject = $db->exec($qInsertSubject);

        $this->_subject_id = $db->last_insert();


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
        $dept_query = "INSERT INTO subject_department (subject_id, department_id) VALUES ('$this->_subject_id ', '$this->_department')";
        $insert_department->exec($dept_query);


        // Insert parent guide relationship

        $insert_parent = new Querier();
        $parent_query = "INSERT INTO subject_subject (subject_child, subject_parent) VALUES ('$this->_subject_id', '$this->_parent')";
        $insert_parent->exec($parent_query);


        // message
        $this->_message = _("Thy Will Be Done.") . " <a href=\"guide.php?subject_id=" . $this->_subject_id . "\">" . _("View Your Guide") . "</a>";
    }

    public function updateRecord()
    {

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

        $qUpSubject = "UPDATE subject SET subject = '" . $db->quote(scrubData($this->_subject, "text")) . "',
        shortform = '" . $db->quote(scrubData($this->_shortform, "text")) . "',
        description = '" . $db->quote(scrubData($this->_description, "text")) . "',
        keywords = '" . $db->quote(scrubData($this->_keywords, "text")) . "',
        redirect_url = '" . $db->quote(scrubData($this->_redirect_url, "text")) . "',
        active = '" . $db->quote(scrubData($this->_active, "integer")) . "',
        type = '" . $db->quote(scrubData($this->_type, "text")) . "',
        extra = '" . $db->quote($json_extra) . "'
        WHERE subject_id = " . scrubData($this->_subject_id, "integer");

        $rUpSubject = $db->query($qUpSubject);


        // Insert subject_department relationship
        $insert_department = new Querier();
        $dept_query = "INSERT INTO subject_department (subject_id, department_id) VALUES ('$this->_subject_id ', '$this->_department')";
        $insert_department->exec($dept_query);


        // Insert parent guide relationship

        $insert_parent = new Querier();
        $parent_query = "INSERT INTO subject_subject (subject_child, subject_parent) VALUES ('$this->_subject_id', '$this->_parent')";
        $insert_parent->exec($parent_query);




        /////////////////////
        // clear staff_subject
        /////////////////////

        $qClearSS = "DELETE FROM staff_subject WHERE subject_id = " . $this->_subject_id;

        $rClearSS = $db->query($qClearSS);

        $this->_debug .= "<p>2. clear staff_subject: $qClearSS</p>";

        if (!$rClearSS) {
            echo blunDer("We have a problem with the clear staff_subject query: $qClearSS");
        }

        /////////////////////
        // insert into staff_subject
        ////////////////////

        self::modifySS();

        /////////////////////
        // clear subject_discipline
        /////////////////////


        /*
        $qClearSD = "DELETE FROM subject_discipline WHERE subject_id = " . $this->_subject_id;

        $rClearSD = $db->query($qClearSD);

        $this->_debug .= "<p>2. clear subject_discipline: $qClearSD</p>";

        if (!$rClearSD) {
            echo blunDer("We have a problem with the clear subject_discipline query: $qClearSD");
        }
         */

        /////////////////////
        // insert into subject_discipline
        ////////////////////

        self::modifySD();

        // /////////////////////
        // Alter chchchanges table
        // table, flag, item_id, title, staff_id
        ////////////////////

        $updateChangeTable = changeMe("guide", "update", $this->_subject_id, $this->_subject, $_SESSION['staff_id']);

        // message
        $this->_message = _("Thy Will Be Done.  Guide updated.");
    }

    public function getTabs()
    {
        if (isset($this->_all_tabs)) {
            return $this->_all_tabs;
        }

        $db = new Querier;

        // Find our existing tabs for this guide
        $qtab = "SELECT DISTINCT tab_id, label, tab_index, external_url FROM tab WHERE subject_id = '{$this->_subject_id}' ORDER BY tab_index";
        $rtab = $db->query($qtab);

        $all_tabs = array();

        foreach ($rtab as $myrow) {
            $tab['label'] = $myrow[1];
            $tab['external_url'] = $myrow[3];

            $all_tabs[] = $tab;
        }

        $this->_all_tabs = $all_tabs;
        return $this->_all_tabs;
    }

    public function outputNavTabs()
    {
        $all_tabs = $this->getTabs();

        $tabs = $this->_isAdmin ? "<ul><li id=\"add_tab\">+</li>" : "<ul>"; // init tabs (in header of body of guide)
        foreach ($all_tabs as $key => $lobjTab) {
            $tabs .= "<li class=\"dropspotty\" style=\"height: auto;\" data-external-link=\"{$lobjTab['external_url']}\"><a href=\"#tabs-$key\">{$lobjTab['label']}</a>";
            $tabs .= $this->_isAdmin ? "<span class='ui-icon ui-icon-wrench alter_tab' role='presentation'>Remove Tab</span></li>" : "</li>";
        }

        //$tabs .= "<li><a id=\"newtab\" href=\"#tabs-new\">{+}</a></li>";
        $tabs .= "</ul>"; // close out our tabs

        echo $tabs;
    }

    public function outputTabs()
    {
        $all_tabs = $this->getTabs();

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
    		print "<div id=\"section_{$lobjSection['section_id']}\" class=\"sp_section\">";

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
    			global $is_responsive;

    			$query = "select extra from subject where subject_id = '{$this->_subject_id}'";
    			$result = $db->query($query);

    			//print_r ($result);
    			$jobj = json_decode($result[0]["extra"]);
    			$col_widths = explode("-", $jobj->{'maincol'});
    			$purified = ""; // init

    			if (isset($col_widths[0]) && $col_widths[0] > 0) {
    				$purified = reduce($col_widths[0], 12);
    				$pure_left = "pure-u-" . $purified[0] . "-" . $purified[1];
    				$left_width = $col_widths[0];
    			} else {
    				$left_width = 0;
    			}

    			if (isset($col_widths[1])) {
    				$purified = reduce($col_widths[1], 12);
    				$pure_center = "pure-u-" . $purified[0] . "-" . $purified[1];
    				$main_width = $col_widths[1];
    			} else {
    				$main_width = 0;
    			}

    			if (isset($col_widths[2]) && $col_widths[2] > 0) {
    				$purified = reduce($col_widths[2], 12);
    				$pure_right = "pure-u-" . $purified[0] . "-" . $purified[1];
    				$side_width = $col_widths[2];
    			} else {
    				$side_width = 0;
    			}

    			// responsive or not?
    			if (isset($is_responsive) && $is_responsive == TRUE) {
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

    			} else {
    				print "<div id=\"leftcol\">
				$left_col_pluslets
				</div>
				<div id=\"maincol\">
				$main_col_pluslets
				</div>
				<div id=\"rightcol\">
				$sidebar_pluslets
				</div>";
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

    function updateExtra()
    {

        // Encode our extra as json
        $json_extra = json_encode($this->_extra);

        /////////////////////
        // update subject table
        /////////////////////

        $qUpExtra = "UPDATE subject
        SET extra = '" . $db->quote($json_extra) . "'
        WHERE subject_id = " . scrubData($this->_subject_id, "integer");

        $rUpExtra = $db->query($qUpExtra);

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

    function deBug()
    {
        print $this->_debug;
    }

}

?>