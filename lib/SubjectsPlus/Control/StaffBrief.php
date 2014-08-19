<?php

namespace SubjectsPlus\Control;

/**
 *   @file Staff
 *   @brief manage staff
 *
 *   @author agdarby, rgilmour
 *   @date Jan 2011

 */
class Staff {

    private $_staff_id;
    private $_lname;
    private $_fname;
    private $_title;
    private $_tel;
    private $_department_id;
    private $_staff_sort;
    private $_email;
    private $_user_type_id;
    private $_password;
    private $_ptags;
    private $_extra;
    private $_bio;
    private $_message;

    public function __construct($staff_id = "", $flag = "") {

        if ($flag == "" && $staff_id == "") {
            $flag = "empty";
        }

        switch ($flag) {
            case "empty":
                // don't set anything; this will create an empty record
                $this->_staff_sort = 0;
                $this->_active = 1;
                break;
            case "post":
                // prepare record for insertion or update
                // data stored in staff table
                $this->_staff_id = $_POST["staff_id"];
                $this->_lname = $_POST["lname"];
                $this->_fname = $_POST["fname"];
                $this->_title = $_POST["title"];
                $this->_tel = $_POST["tel"];
                $this->_department_id = $_POST["department_id"];
                $this->_staff_sort = $_POST["staff_sort"];
                $this->_email = $_POST["email"];
                $this->_user_type_id = $_POST["user_type_id"];
                $this->_password = $_POST["password"];
                $this->_ptags = $_POST["ptags"];
                $this->_active = $_POST["active"];
                $this->_bio = $_POST["bio"];

                break;
            case "delete":
                // kind of redundant, but just set up to delete appropriate tables?
                // title_id only needed?
                $this->_staff_id = $staff_id;

                break;
            default:
                $this->_staff_id = $staff_id;

                /////////////
                // Get staff table info
                /////////////
                $querier = new Querier();
                $q1 = "select staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, password, ptags, active, bio from staff where staff_id = " . $this->_staff_id;
                $staffArray = $querier->query($q1);

                $this->_debug .= "<p class=\"debug\">Staff query: $q1";
                // Test if these exist, otherwise go to plan B
                if ($staffArray == FALSE) {
                    $this->_message = "There is no active record with that ID.  Why not create a new one?";
                } else {
                    $this->_lname = $staffArray[0]['lname'];
                    $this->_fname = $staffArray[0]['fname'];
                    $this->_fullname = $this->_fname . " " . $this->_lname;
                    $this->_title = $staffArray[0]['title'];
                    $this->_tel = $staffArray[0]['tel'];
                    $this->_department_id = $staffArray[0]['department_id'];
                    $this->_staff_sort = $staffArray[0]['staff_sort'];
                    $this->_email = $staffArray[0]['email'];
                    $this->_ip = $staffArray[0]['ip'];
                    $this->_user_type_id = $staffArray[0]['user_type_id'];
                    $this->_password = $staffArray[0]['password'];
                    $this->_ptags = $staffArray[0]['ptags'];
                    $this->_active = $staffArray[0]['active'];
                    $this->_bio = $staffArray[0]['bio'];
                }

                break;
        }
    }

    public function outputForm($wintype = "") {

        global $wysiwyg_desc;
        global $CKPath;
        global $CKBasePath;
        global $IconPath;
        global $all_ptags;
        global $tel_prefix;

        ///////////////
        // Departments
        ///////////////

        $querierDept = new Querier();
        $qDept = "select department_id, name from department order by name";
        $deptArray = $querierDept->query($qDept);

        // create department dropdown
        $deptMe = new Dropdown("department_id", $deptArray, $this->_department_id);
        $this->_departments = $deptMe->display();

        ///////////////
        // User Types
        ///////////////

        $querierUserType = new Querier();
        $qUserType = "select user_type_id, user_type from user_type order by user_type_id";
        $userTypeArray = $querierUserType->query($qUserType);

        // create type dropdown
        $typeMe = new Dropdown("user_type_id", $userTypeArray, $this->_user_type_id);
        $this->_user_types = $typeMe->display();

        ///////////////
        // Active User?
        ///////////////

        $activeArray = array(
            '0' => array('0', 'No'),
            '1' => array('1', 'Yes')
        );

        // create type dropdown
        $activateMe = new Dropdown("active", $activeArray, $this->_active);
        $this->_active_or_not = $activateMe->display();

        //////////////
        // Telephone setup
        /////////////

        if ($tel_prefix != "") {
            $tel_line = "<input type=\"text\" readonly=\"readonly\" size=\"8\" value=\"$tel_prefix\" name=\"unedit_tel_prefix\" /><input type=\"text\" name=\"tel\" id=\"tel\" size=\"5\" class=\"required_field\" value=\"" . $this->_tel . "\" />";
        } else {
            $tel_line = "<input type=\"text\" name=\"tel\" id=\"tel\" size=\"10\" class=\"required_field\" value=\"" . $this->_tel . "\" />";
        }

        //////////////////
        // Photo
        ////////////

        $headshot = self::getHeadshot($this->_email, "medium");

        if ($this->_staff_id != "") {
            $headshot .= "<p><a href=\"../includes/set_picture.php?staff_id=$this->_staff_id\" id=\"load_photo\">" . _("Click to update photo") . "</a></p>";
        } else {
            $headshot .= "<p>" . _("You can change the photo after saving.") . "</p>";
        }

        /////////////
        // Start the form
        /////////////

        $action = htmlentities($_SERVER['PHP_SELF']) . "?staff_id=" . $this->_staff_id;

        if ($wintype != "") {
            $action .= "&wintype=pop";
        }

        echo "
<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" accept-charset=\"UTF-8\">
<input type=\"hidden\" name=\"staff_id\" value=\"" . $this->_staff_id . "\" />
<div style=\"float: left; margin-right: 20px;\">
<div class=\"box\">
        <h2 class=\"bw_head\">" . _("Staff Member") . "</h2>

<div class=\"float-left\">
<span class=\"record_label\">" . _("First Name ") . "</span><br />
<input type=\"text\" name=\"fname\" id=\"fname\" size=\"30\" class=\"required_field\" value=\"" . $this->_fname . "\" />
</div>
<div class=\"float-left\">
<span class=\"record_label\">" . _("Last Name ") . "</span><br />
<input type=\"text\" name=\"lname\" id=\"lname\" size=\"30\" class=\"required_field\" value=\"" . $this->_lname . "\" />
</div>
<br class=\"clear-both\"/><br />
<span class=\"record_label\">" . _("Title") . "</span><br />
<input type=\"text\" name=\"title\" id=\"title\" size=\"50\" class=\"required_field\" value=\"" . $this->_title . "\" />
<br /><br />
<div class=\"float-left\">
<span class=\"record_label\">" . _("Department") . "</span><br />
{$this->_departments}
</div>
<div style=\"float: left;margin-left: 10px;\">
<span class=\"record_label\">" . _("Show First in Dept List?") . "</span><br />
<input type=\"text\" name=\"staff_sort\" id=\"staff_sort\" size=\"2\" class=\"required_field\" value=\"" . $this->_staff_sort . "\" />
</div>
<br class=\"clear-both\" /><br />
<span class=\"record_label\">" . _("Telephone") . "</span><br />
$tel_line
<br /><br />
<span class=\"record_label\">" . _("Email (This is the username for logging in to SubjectsPlus)") . "</span><br />
<input type=\"text\" name=\"email\" id=\"email\" size=\"40\" class=\"required_field\" value=\"" . $this->_email . "\" />
<br /><br />
<div class=\"float-left\">
<span class=\"record_label\">" . _("User Type") . "</span><br />
{$this->_user_types}
</div>
<div style=\"float: left;margin-left: 20px;\">
<span class=\"record_label\">" . _("Active User?") . "</span><br />
{$this->_active_or_not}
</div>
<br /><br />
</div>
        <div class=\"box no_overflow\">
<h2 class=\"bw_head\">" . _("Photo") . "</h2>

$headshot
</div>
        <div class=\"box no_overflow\">
<h2 class=\"bw_head\">" . _("Biographical Details") . "</h2>

<p>" . _("Please only include professional details.") . "</p><br />";

        self::outputBioForm();

        echo "
<br />
</div>
</div>
<!-- right hand column -->
<div style=\"float: left; max-width: 400px;\">
        <div class=\"box\">
<h2 class=\"bw_head\">" . _("Permissions") . "</h2>

";

// Get our permission tags, or ptags

        $current_ptags = explode("|", $this->_ptags);
        foreach ($all_ptags as $value) {
            if (in_array($value, $current_ptags)) {
                echo "<span class=\"ctag-on\">$value</span>";
            } else {
                echo " <span class=\"ctag-off\">$value</span>";
            }
        }

        echo "<input type=\"hidden\" name=\"ptags\" value=\"$this->_ptags\" /><br class=\"clear-both\" /><p style=\"font-size: smaller\">Select which parts of SubjectsPlus this user may access.
                <br /><strong>records</strong> allows access to both the Record and Guide tabs.
                <br /><strong>eresource_mgr</strong> allows the user to see all the information about a Record (and delete it), and quickly see all guides.
                <br /><strong>admin</strong> allows access to the overall admin of the site.
                <br /><strong>NOFUN</strong> means user can't modify other peoples' guides, or view records</p>
</div>
        	<div class=\"box\">
	<h2 class=\"bw_head\">" . _("Password") . "</h2>";

        if ($this->_staff_id != "") {
            echo "<p  ><a href=\"../includes/set_password.php?staff_id=" . $this->_staff_id . "\" id=\"reset_password\">" . _("The password is hidden.  Reset?") . "</a></p>";
        } else {
            echo "<input type=\"password\" name=\"password\" size=\"20\" class=\"required_field\" /><br />
		<p style=\"font-size: smaller\">The password is stored as a hash in the database, but unless you have SSL travels clear text across the internet.</p>";
        }

        echo "
	</div>
        <div id=\"record_buttons\" class=\"box\">
	<h2 class=\"bw_head\">" . _("Save") . "</h2>
	
		<input type=\"submit\" name=\"submit_record\" class=\"button save_button\" value=\"" . _("Save Record Now") . "\" />";
        // if it's not a new record, and we're authorized, show delete button
        if ($this->_staff_id != "") {
            echo "<input type=\"submit\" name=\"delete_record\" class=\"delete_button\" value=\"" . _("Delete Forever!") . "\" />";
        }
        // get edit history
        $last_mod = _("Last modified: ") . lastModded("staff", $this->_staff_id);
        echo "<div id=\"last_edited\">$last_mod</div>

</div>
</form>";
    }

    public function outputPasswordForm() {
        $box = "<div class=\"box no_overflow\">
		<p>" . _("Enter the new password.  Make it a good one!") . "</p>
		<br />
		<form name=\"update_password\" method=\"post\" action=\"../includes/set_password.php\" />
		<input type=\"hidden\" name=\"action\" value=\"password\" />
		<input type=\"hidden\" name=\"staff_id\" value=\"$this->_staff_id\" />
		<p><input type=\"password\" size=\"20\" name=\"password\" value=\"\" />
		<input class=\"button\"  type=\"submit\" name=\"Submit\" value=\"Update Password!\" /></p>
		</div>";

        return $box;
    }

    public function deleteRecord() {

        // make sure they're allowed to delete
        if ($_SESSION["admin"] != "1") {
            return FALSE;
        }

        // Delete the records from staff table
        $q = "DELETE staff FROM staff WHERE staff.staff_id = '" . $this->_staff_id . "'";

        $delete_result = $db->exec($q);

        $this->_debug = "<p class=\"debug\">Delete from staff table(s) query: $q";

        if ($delete_result != 0) {

            // /////////////////////
            // Alter chchchanges table
            // table, flag, item_id, title, staff_id
            ////////////////////

            $updateChangeTable = changeMe("staff", "delete", $this->_staff_id, $this->_title, $_SESSION['staff_id']);

            $this->_message = _("Thy will be done.  Note that any subject guides associated with this user are now orphans.");
            return false;
        } else {
            // message
            $this->_message = _("There was a problem with your delete.");
            return FALSE;
        }
    }

    public function insertRecord() {

        ////////////////
        // hash password
        ////////////////
        
        $db = new Querier;
        
        $this->_password = md5($this->_password);

        ////////////////
        // Insert staff
        ////////////////

        $qInsertStaff = "INSERT INTO staff (fname, lname, title, tel, department_id, staff_sort, email, user_type_id, password, ptags, active, bio) VALUES (
		'" . $db->quote(scrubData($this->_fname)) . "',
		'" . $db->quote(scrubData($this->_lname)) . "',
		'" . $db->quote(scrubData($this->_title)) . "',
		'" . $db->quote(scrubData($this->_tel)) . "',
		'" . $db->quote(scrubData($this->_department_id, "integer")) . "',
		'" . $db->quote(scrubData($this->_staff_sort, "integer")) . "',
		'" . $db->quote(scrubData($this->_email, "email")) . "',
		'" . $db->quote(scrubData($this->_user_type_id, "integer")) . "',
		'" . $db->quote(scrubData($this->_password)) . "',
		'" . $db->quote(scrubData($this->_ptags)) . "',
                '" . $db->quote(scrubData($this->_active, "integer")) . "',
                '" . $db->quote(scrubData($this->_bio, "richtext")) . "'
		)";

        $rInsertStaff = $db->query($qInsertStaff);

        $this->_debug .= "<p class=\"debug\">Insert query: $qInsertStaff</p>";

        if (!$rInsertStaff) {
            echo blunDer("We have a problem with the insert staff query: $qInsertStaff");
        }

        $this->_staff_id = $db->last_id();

        // create folder

        if ($this->_staff_id) {
            $user_folder = explode("@", $this->_email);
            $path = "../../assets/users/_" . $user_folder[0];
            mkdir($path);

            // And copy over the generic headshot image and headshot_large.jpg
            $nufile = $path . "/headshot.jpg";
            $copier = copy("../../assets/images/headshot.jpg", $nufile);
            $copier = copy("../../assets/images/headshot.jpg", $path . "/headshot_large.jpg");
        }

        // /////////////////////
        // Alter chchchanges table
        // table, flag, item_id, title, staff_id
        ////////////////////

        $updateChangeTable = changeMe("staff", "insert", $this->_staff_id, $this->_email, $_SESSION['staff_id']);

        // message
        $this->_message = _("Thy Will Be Done.  Added.");
    }

    public function updateRecord() {

        /////////////////////
        // update staff table
        // NOTE:  we don't update the password here; it's updated separately
        /////////////////////

        $qUpStaff = "UPDATE staff SET
        fname = " . $db->quote(scrubData($this->_fname)) . ",
        lname = " . $db->quote(scrubData($this->_lname)) . ",
        title = " . $db->quote(scrubData($this->_title)) . ",
        tel = " . $db->quote(scrubData($this->_tel)) . ",
        department_id = " . $db->quote(scrubData($this->_department_id, 'integer')) . ",
        staff_sort = " . $db->quote(scrubData($this->_staff_sort, 'integer')) . ",
        email = " . $db->quote(scrubData($this->_email, 'email')) . ",
        user_type_id = " . $db->quote(scrubData($this->_user_type_id, 'integer')) . ",
        ptags = " . $db->quote(scrubData($this->_ptags)) . ",
        active = " . $db->quote(scrubData($this->_active, 'integer')) . ",
        bio = " . $db->quote(scrubData($this->_bio, 'richtext')) . "
        WHERE staff_id = " . scrubData($this->_staff_id, 'integer');

        $rUpStaff = $db->exec($qUpStaff);

        // /////////////////////
        // Alter chchchanges table
        // table, flag, item_id, title, staff_id
        ////////////////////

        $updateChangeTable = changeMe("staff", "update", $this->_staff_id, $this->_email, $_SESSION['staff_id']);

        // message
        $this->_message = _("Thy Will Be Done.  Updated.");
    }

    public function updatePassword($new_pass) {

        $q = "UPDATE staff SET password = md5('" . $db->quote(scrubData($new_pass)) . "') WHERE staff_id = " . $this->_staff_id;

        $this->_debug = "<p class=\"debug\">Password Update query: $q</p>";

        $r = $db->query($q);

        if ($r) {
            $updateChangeTable = changeMe("staff", "update", $this->_staff_id, "password update", $_SESSION['staff_id']);

            return TRUE;
        }
    }

    public function updateBio($new_bio) {

        $q = "UPDATE staff SET bio = '" . $db->quote(scrubData($new_bio, "richtext")) . "' WHERE staff_id = " . $this->_staff_id;

        $this->_debug = "<p class=\"debug\">Bio Update query: $q</p>";

        $r = $db->query($q);

        if ($r) {
            $updateChangeTable = changeMe("staff", "update", $this->_staff_id, "bio update", $_SESSION['staff_id']);
            return TRUE;
        }
    }

    public function getHeadshot($email, $pic_size = "medium") {

        global $AssetPath;

        $name_id = explode("@", $email);
        $lib_image = "_" . $name_id[0];
        $this->_headshot_loc = $AssetPath . "users/$lib_image/headshot.jpg";

        if ($email != "") {
            $headshot = "<img id=\"headshot\" src=\"" . $this->_headshot_loc . "\" alt=\"$this->_fullname\" title=\"$this->_fullname\"";
        } else {
            $headshot = "<img id=\"headshot\" src=\"$AssetPath" . "images/headshot.jpg\" alt=\"No picture\" title=\"No picture\"";
        }

        switch ($pic_size) {
            case "small":
                $headshot .= " width=\"50\"";
                break;
            case "medium":
                $headshot .= " width=\"70\"";
                break;
        }

        $headshot .= " class=\"staff_photo\" align=\"left\" />";
        return $headshot;
    }

    function outputBioForm() {

        global $wysiwyg_desc;
        global $CKPath;
        global $CKBasePath;

        if ($wysiwyg_desc == 1) {
            include($CKPath);
            global $BaseURL;

            $oCKeditor = new CKEditor($CKBasePath);
            $oCKeditor->timestamp = time();
            $config['toolbar'] = 'Basic'; // Default shows a much larger set of toolbar options
            $config['height'] = '300';
            $config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

            echo $oCKeditor->editor('bio', $this->_bio, $config);
            echo "<br />";
        } else {
            echo "<textarea name=\"answer\" rows=\"4\" cols=\"70\">" . stripslashes($this->_answer) . "</textarea>";
        }
    }

    function getMessage() {
        return $this->_message;
    }

    function getFullName() {
        return $this->_fullname;
    }

    function getHeadshotLoc() {
        return $this->_headshot_loc;
    }

    function getRecordId() {
        return $this->_staff_id;
    }

    function deBug() {
        print $this->_debug;
    }

}

?>