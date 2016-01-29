<?php
namespace SubjectsPlus\Control;
/**
 *   @file  Staff
 *   @brief manage staff
 *
 *   @author agdarby, rgilmour, dgonzalez
 *   @date april 2014

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
  private $_bio;
  private $_message;
  // new for UM
  private $_position_number;
  private $_job_classification;
  private $_room_number;
  private $_supervisor_id;
  private $_emergency_contact_name;
  private $_emergency_contact_relation;
  private $_emergency_contact_phone;
  private $_street_address;
  private $_city;
  private $_state;
  private $_zip;
  private $_home_phone;
  private $_cell_phone;
  private $_fax;
  private $_intercom;
  private $_lat_long;
  private $_fullname;
  private $_debug;
    //new sp4
  private $_social_media;
  private $_extra;

  public $_ok_departments = array();
 

  public function __construct($staff_id="", $flag="", $full_record = FALSE) {
    //var_dump($_POST);

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
        //$this->_department_id = $_POST["department_id"];
        $this->_staff_sort = $_POST["staff_sort"];
        $this->_email = $_POST["email"];
        $this->_user_type_id = $_POST["user_type_id"];

        // we hide the next one if it's an existing staff member
        // because password is set via separate jquery .load
        if (!isset($this->_staff_id) || $this->_staff_id == '') {
          $this->_password = $_POST["password"];
        }

        $this->_ptags = $_POST["ptags"];
        $this->_active = $_POST["active"];
        $this->_bio = $_POST["bio"];

        // new for UM
        $this->_position_number = $_POST["position_number"];
        $this->_job_classification = $_POST["job_classification"];
        $this->_room_number = $_POST["room_number"];
        $this->_supervisor_id = $_POST["supervisor_id"];
        $this->_emergency_contact_name = $_POST["emergency_contact_name"];
        $this->_emergency_contact_relation = $_POST["emergency_contact_relation"];
        $this->_emergency_contact_phone = $_POST["emergency_contact_phone"];
        $this->_street_address = $_POST["street_address"];
        $this->_city = $_POST["city"];
        $this->_state = $_POST["state"];
        $this->_zip = $_POST["zip"];
        $this->_home_phone = $_POST["home_phone"];
        $this->_cell_phone = $_POST["cell_phone"];
        $this->_fax = $_POST["fax"];
        $this->_intercom = $_POST["intercom"];
        $this->_lat_long = $_POST["lat_long"];
        $this->_fullname = isset($_POST["fullname"]) ? $_POST["fullname"] : $_POST["fname"] . " " . $_POST["lname"];

        //new sp4
        $this->_social_media = $this->setSocialMediaDataPost();

        // data stored in staff_department table
        $this->_department_id = $_POST["department_id"]; // array
        $this->_department_count = count($this->_department_id); // # of items in above array

        if(isset($this->_extra)) {
          $this->_extra = $_POST['extra'];
        } else {
          $this->_extra = "";
        }



        break;
      case "delete":
        // kind of redundant, but just set up to delete appropriate tables?

        $this->_staff_id = $staff_id;

        self::getAssociatedDepartments();

        break;
      case "forgot":
        $this->_email = $_POST['email'];

        /////////////
        // Get staff table info
        /////////////
        $querier = new  Querier();
        $q1 = "select staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, password, ptags, active, bio, social_media from staff where email = '" . $this->_email . "'";
        $staffArray = $querier->query($q1);

        $this->_debug .= "<p class=\"debug\">Staff query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($staffArray == FALSE) {
          $this->_message = "There is no active record with that email.";
        } else {
          $this->_staff_id = $staffArray[0]['staff_id'];
          $this->_lname = $staffArray[0]['lname'];
          $this->_fname = $staffArray[0]['fname'];
          $this->_fullname = $this->_fname . " " . $this->_lname;
          $this->_title = $staffArray[0]['title'];
          $this->_tel = $staffArray[0]['tel'];
          $this->_department_id = $staffArray[0]['department_id'];
          $this->_staff_sort = $staffArray[0]['staff_sort'];
          $this->_ip = $staffArray[0]['ip'];
          $this->_user_type_id = $staffArray[0]['user_type_id'];
          $this->_password = $staffArray[0]['password'];
          $this->_ptags = $staffArray[0]['ptags'];
          $this->_active = $staffArray[0]['active'];
          $this->_bio = $staffArray[0]['bio'];
          $this->_social_media = $staffArray[0]['social_media'];
        }
        break;
      default:
        $this->_staff_id = $staff_id;

        /////////////
        // Get staff table info
        // Don't call full record for regular log in
        /////////////
        $db = new Querier;
        if ($full_record == TRUE) {
          $q1 = "SELECT staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, password, ptags, active, bio
            , position_number, job_classification, room_number, supervisor_id, emergency_contact_name, emergency_contact_relation, emergency_contact_phone,
            street_address, city, state, zip, home_phone, cell_phone, fax, intercom, lat_long, social_media, extra
            FROM staff WHERE staff_id = " . $this->_staff_id;
        } else {
          $q1 = "SELECT staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, ptags, active, bio, social_media
            FROM staff WHERE staff_id = " . $this->_staff_id;
        }

        $staffArray = $db->query($q1);

        // $this->_debug .= "<p class=\"debug\">Staff query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($staffArray == FALSE) {
          $this->_message = "There is no active record with that ID.  Why not create a new one?";
        } else {
          $this->_lname = $staffArray[0]['lname'];
          $this->_fname = $staffArray[0]['fname'];
          $this->_fullname = $this->_fname . " " . $this->_lname;
          $this->_title = $staffArray[0]['title'];
          $this->_tel = $staffArray[0]['tel'];
          //$this->_department_id = $staffArray[0]['department_id'];
          $this->_staff_sort = $staffArray[0]['staff_sort'];
          $this->_email = $staffArray[0]['email'];
          $this->_ip = $staffArray[0]['ip'];
          $this->_user_type_id = $staffArray[0]['user_type_id'];
          $this->_active = $staffArray[0]['active'];
          $this->_ptags = $staffArray[0]['ptags'];
          $this->_bio = $staffArray[0]['bio'];

          if ($full_record == TRUE) {
            $this->_password = $staffArray[0]['password'];

            //New for UM
            $this->_position_number = $staffArray[0]['position_number'];
            $this->_job_classification = $staffArray[0]['job_classification'];
            $this->_room_number = $staffArray[0]['room_number'];
            $this->_supervisor_id = $staffArray[0]['supervisor_id'];
            $this->_emergency_contact_name = $staffArray[0]['emergency_contact_name'];
            $this->_emergency_contact_relation = $staffArray[0]['emergency_contact_relation'];
            $this->_emergency_contact_phone = $staffArray[0]['emergency_contact_phone'];
            $this->_street_address = $staffArray[0]['street_address'];
            $this->_city = $staffArray[0]['city'];
            $this->_state = $staffArray[0]['state'];
            $this->_zip = $staffArray[0]['zip'];
            $this->_home_phone = $staffArray[0]['home_phone'];
            $this->_cell_phone = $staffArray[0]['cell_phone'];
            $this->_fax = $staffArray[0]['fax'];
            $this->_intercom = $staffArray[0]['intercom'];
            $this->_lat_long = $staffArray[0]['lat_long'];

            //new for sp4

            $this->_social_media = $staffArray[0]['social_media'];
            $this->_extra = $staffArray[0]['extra'];

            ///////////////////
            // Query Departments table
            // used to get our set of departments associated
            // ////////////////

            self::getAssociatedDepartments();
            $this->_department_id = $this->_ok_departments;


          }
        }

        break;
    }
  }


  public function outputForm($wintype="") {

    global $wysiwyg_desc;
    global $CKPath;
    global $CKBasePath;
    global $IconPath;
    global $all_ptags;
    global $tel_prefix;
    global $omit_user_columns;
    global $require_user_columns;

    ///////////////
    // Departments
    ///////////////

    $querierDept = new  Querier();
    $qDept = "select department_id, name from department order by name";
    $deptArray = $querierDept->query($qDept);

    // create department dropdown
    $deptMe = new  Dropdown("department_id[]", $deptArray, $this->_department_id, "","","","multi");
    $this->_departments = $deptMe->display();

    ///////////////
    // User Types
    ///////////////

    $querierUserType = new  Querier();
    $qUserType = "select user_type_id, user_type from user_type order by user_type_id";
    $userTypeArray = $querierUserType->query($qUserType);

    // create type dropdown
    $typeMe = new  Dropdown("user_type_id", $userTypeArray, $this->_user_type_id);
    $this->_user_types = $typeMe->display();

    ///////////////
    // Supervisor
    ///////////////

    $querierSupervisor = new  Querier();
    $qSupervisor = "select staff_id, CONCAT( fname, ' ', lname ) AS fullname FROM staff WHERE ptags LIKE '%supervisor%' AND active = '1' ORDER BY lname";
    $supervisorArray = $querierSupervisor->query($qSupervisor);

    // create type dropdown
    $superviseMe = new  Dropdown("supervisor_id", $supervisorArray, $this->_supervisor_id, '', '* External Supervisor');
    $this->_supervisors = $superviseMe->display();
    ///////////////
    // Active User?
    ///////////////

    $activeArray = array(
        '0' => array('0', 'No'),
        '1' => array('1', 'Yes')
    );

    // create type dropdown
    $activateMe = new  Dropdown("active", $activeArray, $this->_active);
    $this->_active_or_not = $activateMe->display();

    //////////////
    // Telephone setup
    /////////////

    if ($tel_prefix != "") {
      $tel_line = "<input type=\"text\" readonly=\"readonly\" size=\"4\" value=\"$tel_prefix\" name=\"unedit_tel_prefix\" /><input type=\"text\" name=\"tel\" id=\"tel\" size=\"10\" class=\"";
      if ( in_array( _( 'tel' ) , $require_user_columns ) ) $tel_line .= 'required_field';
      $tel_line .= "\" value=\"" . $this->_tel . "\" />";
    } else {
      $tel_line = "<input type=\"text\" name=\"tel\" id=\"tel\" size=\"15\" class=\"";
      if ( in_array( _( 'tel' ) , $require_user_columns ) ) $tel_line .= 'required_field';
      $tel_line .= "\" value=\"" . $this->_tel . "\" />";
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


    //////////////////
    // Social Media //
    //////////////////
    //$socialMedia  = self::outputSocialMediaForm();

    /////////////
    // Start the form
    /////////////

    $action = htmlentities($_SERVER['PHP_SELF']) . "?staff_id=" . $this->_staff_id;

    if ($wintype != "") {
      $action .= "&wintype=pop";
    }

    // set up
    print "<div class=\"pure-g\">";


    //see which"Staff Member" columns and whether "Personal Information" section or "Emergency Contact" section are omitted
    // added by dgonzalez
    $isFnameOmitted = in_array( _( "fname" ) , $omit_user_columns );
    $isLnameOmitted = in_array( _( "lname" ) , $omit_user_columns );
    $isTitleOmitted = in_array( _( "title" ) , $omit_user_columns );
    $isPositionNumOmitted = in_array( _( "position_number" ) , $omit_user_columns );
    $isClassificationOmitted = in_array( _( "classification" ) , $omit_user_columns );
    $isDepartmentOmitted = in_array( _( "department" ) , $omit_user_columns );
    $isPriorityOmitted = in_array( _( "priority" ) , $omit_user_columns );
    $isSupervisorOmitted = in_array( _( "supervisor" ) , $omit_user_columns );
    $isTelephoneOmitted = in_array( _( "tel" ) , $omit_user_columns );
    $isdFaxOmitted = in_array( _( "fax" ) , $omit_user_columns );
    $isIntercomOmitted = in_array( _( "intercom" ) , $omit_user_columns );
    $isUserTypeOmitted = in_array( _( "user_type" ) , $omit_user_columns );
    $isRoomNumOmitted = in_array( _( "room_number" ) , $omit_user_columns );
    $isPersonalOmitted = in_array( _( "personal_information" ) , $omit_user_columns );
    $isEmergencyContactOmitted = in_array( _( "emergency_contact" ) , $omit_user_columns );

    // start form
    print "<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" accept-charset=\"UTF-8\" class=\"pure-form pure-form-stacked\">
<input type=\"hidden\" name=\"staff_id\" value=\"" . $this->_staff_id . "\" />
<div class=\"pure-u-1-3\">
<div class=\"pluslet no_overflow\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">" . _("Staff Member") . "</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">";

    //based on omitted columns write out html
    // added by dgonzalez
    if ( $isFnameOmitted )
    {
      echo "<input type=\"hidden\" name=\"fname\" id=\"fname\" value=\"" . $this->_fname . "\" />";
    } else {

      print "<div style=\"float: left; margin-right: 1em;\"><label for=\"fname\">" . _("First Name") . "</label>
    	<input type=\"text\" name=\"fname\" id=\"fname\" class=\"pure-input-1\" value=\"" . $this->_fname . "\" /></div>";

    }

    if ( $isLnameOmitted )
    {
      echo "<input type=\"hidden\" name=\"lname\" id=\"lname\" value=\"" . $this->_lname . "\" /><br style=\"clear:both;\"/>";
    } else {

      print "<div style=\"float: left;\"><label for=\"lname\">" . _("Last Name") . "</label>
	    <input type=\"text\" name=\"lname\" id=\"lname\" class=\"pure-input-1\" value=\"" . $this->_lname . "\" /></div>
	    <br style=\"clear:both;\"/>";

    }

    if ( $isTitleOmitted )
    {
      echo "<input type=\"hidden\" name=\"title\" id=\"title\" value=\"" . $this->_title . "\" />";
    } else {

      print "<div style=\"float: left; margin-right: 1em;\"><label for=\"title\">" . _("Position Title") . "</label>
    <input type=\"text\" name=\"title\" id=\"title\" class=\"pure-input-1";
      if ( in_array( _( 'title' ) , $require_user_columns ) ) echo 'required_field';
      print "\" value=\"" . $this->_title . "\" /></div>";

    }

    if ( $isPositionNumOmitted )
    {
      echo "<input type=\"hidden\" name=\"position_number\" id=\"position_number\" value=\"" . $this->_position_number . "\" />";
    }else
    {

      print "<div style=\"float: left;\"><label for=\"position_number\">" . _("Position #") . "</label>
    <input type=\"text\" name=\"position_number\" id=\"position_number\" class=\"pure-input-1-4";
      if ( in_array( _( 'position_number' ) , $require_user_columns ) ) echo 'required_field';
      print "\" value=\"" . $this->_position_number . "\" /></div>";
    }

    if ( !( $isTitleOmitted && $isPositionNumOmitted) )
    {
      echo "<br class=\"clear-both\"/><br />";
    }

    if ( $isClassificationOmitted )
    {
      echo "<input type=\"hidden\" name=\"job_classification\" id=\"job_classification\" value=\"" . $this->_job_classification . "\" />";
    } else
    {
      print "<label for=\"job_classification\">" . _("Job Classification") . "</label>
    <input type=\"text\" name=\"job_classification\" id=\"job_classification\" class=\"pure-input-2-3";
      if ( in_array( _( 'job_classification' ) , $require_user_columns ) ) echo 'required_field';

      print "\" value=\"" . $this->_job_classification . "\" />";
    }

    if ( $isDepartmentOmitted )
    {
      echo "<input type=\"hidden\" name=\"department_id\" id=\"department_id\" value=\"\" />";
    }else
    {
      echo "
<div style=\"float: left; margin-right: 1em;\"><label for=\"department_id\">" . _("Department") . "</label>
{$this->_departments}
</div>";
    }

    if ( $isPriorityOmitted )
    {
      echo "<input type=\"hidden\" name=\"staff_sort\" id=\"staff_sort\" value=\"" . $this->_staff_sort . "\" />";
    }else
    {
      echo "<div style=\"float: left;\"><label for=\"staff_sort\">" . _("Display Priority") . "</label>

    <input type=\"text\" name=\"staff_sort\" id=\"staff_sort\" class=\"pure-input-1-4";
      if ( in_array( _( 'priority' ) , $require_user_columns ) ) echo 'required_field';
      print "\" value=\"" . $this->_staff_sort . "\" /></div>";
    }

    if ( !( $isDepartmentOmitted && $isPriorityOmitted ) )
    {
      echo "<br class=\"clear-both\" /><br />";
    }

    if ( $isSupervisorOmitted )
    {
      echo "<input type=\"hidden\" id=\"supervisor_id\" name=\"supervisor_id\" value=\"\" />";
    }else
    {
      echo "
<label for=\"supervisor\">" . _("Supervisor") . "</label>
{$this->_supervisors}
";
    }

    if ( $isTelephoneOmitted )
    {
      echo "<input id=\"tel\" type=\"hidden\" value=\"" . $this->_tel . "\" name=\"tel\">";
    } else {

      print "<div style=\"float: left; margin-right: 1em;\"><label for=\"tel\">" . _("Telephone") . "</label>
    $tel_line
    </div>";

    }

    if ( $isdFaxOmitted )
    {
      echo "<input type=\"hidden\" name=\"fax\" id=\"fax\" value=\"" . $this->_fax . "\" />";
    }else
    {

      print "<div style=\"float: left;margin-right: 1em;\"><label for=\"fax\">" . _("FAX") . "</label>
    <input type=\"text\" name=\"fax\" id=\"fax\" class=\"pure-input-1";
      if ( in_array( _( 'fax' ) , $require_user_columns ) ) echo 'required_field';
      print "\" value=\"" . $this->_fax . "\" /></div>";

    }

    if ( $isIntercomOmitted )
    {
      echo "<input type=\"hidden\" name=\"intercom\" id=\"intercom\" value=\"" . $this->_intercom . "\" />";
    }else
    {

      print "<div style=\"float: left; margin-right: 1em;\"><label for=\"intercom\">" . _("Intercom") . "</label>
    <input type=\"text\" name=\"intercom\" id=\"intercom\" class=\"pure-input-1-4";
      if ( in_array( _( 'priority' ) , $require_user_columns ) ) echo 'required_field';
      print "\" value=\"" . $this->_intercom . "\" /></div>";

    }

    if ( $isRoomNumOmitted )
    {
      echo "<input type=\"hidden\" name=\"room_number\" id=\"room_number\" value=\"" . $this->_room_number . "\" />";
    } else {

      print "<div style=\"float: left;\"><label for=\"room_number\">" . _("Room #") . "</label>
    <input type=\"text\" name=\"room_number\" id=\"room_number\" class=\"pure-input-1-3";
      if ( in_array( _( 'priority' ) , $require_user_columns ) ) echo 'required_field';
      print "\" value=\"" . $this->_room_number . "\" /></div>";
    }

    if ( !( $isTelephoneOmitted && $isdFaxOmitted && $isIntercomOmitted && $isRoomNumOmitted ) )
    {
      echo "<br class=\"clear-both\"/><br />";
    }

    print "<label for=\"email\">" . _("Email (This is the username for logging in to SubjectsPlus)") . "</label>
    <input type=\"text\" name=\"email\" id=\"email\" class=\"pure-input-1 required_field\" value=\"" . $this->_email . "\" />";

    if ( $isUserTypeOmitted )
    {
      echo "<input type=\"hidden\" name=\"user_type_id\" id=\"user_type_id\" value=\"1\" />";
    }else
    {
      echo "<div style=\"float: left; margin-right: 1em;\"><label for=\"user_type\">" . _("User Type") . "</label>
	      {$this->_user_types}
	      </div>";
    }

    echo "
    <div style=\"float: left; margin-right: 1em;\"><label for=\"active\">" . _("Active User?") . "</label>
    {$this->_active_or_not}
    </div>
    <br style=\"clear: both;\" /><br /></div></div>";

    if ( $isPersonalOmitted )
    {
      echo "<input type=\"hidden\" name=\"street_address\" id=\"street_address\" value=\"" . $this->_street_address . "\" />\n";
      echo "<input type=\"hidden\" name=\"city\" id=\"city\" value=\"" . $this->_city . "\" />\n";
      echo "<input type=\"hidden\" name=\"state\" id=\"state\" value=\"" . $this->_state . "\" />\n";
      echo "<input type=\"hidden\" name=\"zip\" id=\"zip\" value=\"" . $this->_zip . "\" />\n";
      echo "<input type=\"hidden\" name=\"home_phone\" id=\"home_phone\" value=\"" . $this->_home_phone . "\" />\n";
      echo "<input type=\"hidden\" name=\"cell_phone\" id=\"cell_phone\" value=\"" . $this->_cell_phone . "\" />\n";
      echo "<input type=\"hidden\" name=\"lat_long\" id=\"lat_long\"value=\"" . $this->_lat_long . "\" />\n";

    }else
    {

      self::outputPersonalInfoForm();

      self::outputLatLongForm();

    }

    if ( $isEmergencyContactOmitted )
    {
      echo "<input type=\"hidden\" name=\"emergency_contact_name\" id=\"emergency_contact_name\" value=\"" . $this->_emergency_contact_name . "\" />";
      echo "<input type=\"hidden\" name=\"emergency_contact_relation\" id=\"emergency_contact_relation\" value=\"" . $this->_emergency_contact_relation . "\" />\n";
      echo "<input type=\"hidden\" name=\"emergency_contact_phone\" id=\"emergency_contact_phone\" value=\"" . $this->_emergency_contact_phone . "\" />\n";

    } else {

      self::outputEmergencyInfoForm();

    }

    echo "</div><div class=\"pure-u-1-3\">";

    makePluslet(_("Photo"), $headshot, "no_overflow");


    $socialMediaForm = self::outputSocialMediaForm();
    makePluslet(_("Social Media"), $socialMediaForm, "no_overflow");



    print "<div class=\"pluslet\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">" . _("Staff Member") . "</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">
<p>" . _("Please only include professional details.") . "</p><br />";

    self::outputBioForm();

    print "</div>"; // end pluslet body
    print "</div>"; // end pluslet

    print "</div>"; // end pure 1-3

    print "<div class=\"pure-u-1-3\">";


// Get our permission tags, or ptags

    $current_ptags = explode("|", $this->_ptags);
    $our_ptags = "";

    foreach ($all_ptags as $value) {
      if (in_array($value, $current_ptags)) {
        $our_ptags .= " <span class=\"ctag-on\">$value</span> ";
      } else {
        $our_ptags .= " <span class=\"ctag-off\">$value</span> ";
      }
    }

    $our_ptags .= "<input type=\"hidden\" name=\"ptags\" value=\"$this->_ptags\" /><br class=\"clear-both\" /><p style=\"font-size: smaller\">";
    $our_ptags .= _("Select which parts of SubjectsPlus this user may access.
                <br /><strong>records</strong> allows access to both the Record and Guide tabs.
                <br /><strong>eresource_mgr</strong> allows the user to see all the information about a Record (and delete it), and quickly see all guides.
                <br /><strong>admin</strong> allows access to the overall admin of the site.
                <br /><strong>librarian</strong> means user shows up in lists of librarians.
                <br /><strong>supervisor</strong> means user shows up in list of supervisors
                <br /><strong>view_map</strong> lets user see the map of where everyone lives.  Probably only for muckymucks.");

    makePluslet("Permissions", $our_ptags, "no_overflow");

    ///////////////
    // Password
    ///////////////

    if ($this->_staff_id != "") {
      $our_password = "<p  ><a href=\"../includes/set_password.php?staff_id=" . $this->_staff_id . "\" id=\"reset_password\">" . _("The password is hidden.  Reset?") . "</a></p>
        ";
    } else {
      $our_password = "<input type=\"password\" name=\"password\" size=\"20\" class=\"required_field\" /><br />
        <p style=\"font-size: smaller\">Pasword must have a special character, a letter, a number, and at least 6 characters.</p>
		<p style=\"font-size: smaller\">The password is stored as a hash in the database, but unless you have SSL travels clear text across the internet.</p>";
    }

    makePluslet("Password", $our_password, "no_overflow");

    /////////////////
    // Save/Delete Buttons
    /////////////////

    $our_buttons = "<input type=\"submit\" name=\"submit_record\" class=\"pure-button pure-button-primary\" value=\"" . _("Save Record Now") . "\" />";
    // if it's not a new record, and we're authorized, show delete button
    if ($this->_staff_id != "") {
      $our_buttons .= " <input style=\"margin-left: 1em;\" type=\"submit\" name=\"delete_record\" class=\"pure-button delete_button pure-button-warning\" value=\"" . _("Delete Forever!") . "\" />";
    }

    // get edit history
    $last_mod = _("Last modified: ") . lastModded("staff", $this->_staff_id);
    $our_buttons .= "<div id=\"last_edited\">$last_mod</div>";

    makePluslet("Save Changes", $our_buttons, "no_overflow");

    print "</div></div>
</form>";
  }


  public function outputPersonalInfoForm() {

    global $require_user_columns;

    // set up required fields based on fields in config
    if ( in_array( _( 'address' ) , $require_user_columns ) ) { $street_address_required = "required_field"; } else {$street_address_required = "";}
    if ( in_array( _( 'city' ) , $require_user_columns ) ) { $city_required = "required_field"; } else {$city_required = "";}
    if ( in_array( _( 'state' ) , $require_user_columns ) ) { $state_required = "required_field"; } else {$state_required = "";}
    if ( in_array( _( 'zip' ) , $require_user_columns ) ) { $zip_required = "required_field"; } else {$zip_required = "";}
    if ( in_array( _( 'home_phone' ) , $require_user_columns ) ) { $home_phone_required = "required_field"; } else {$home_phone_required = "";}
    if ( in_array( _( 'cell_phone' ) , $require_user_columns ) ) { $cell_phone_required = "required_field"; } else {$cell_phone_required = "";}

    $personal_info = "
    <label for=\"record_title\">" . _("Street Address") . "</label>
    <input type=\"text\" name=\"street_address\" id=\"street_address\" class=\"pure-input-1 $street_address_required\" value=\"" . $this->_street_address . "\" />
    <br class=\"clear-both\" />
    <div style=\"float: left; margin-right: 1em;\"><label for=\"city\">" . _("City") . "</label>
    <input type=\"text\" name=\"city\" id=\"city\" class=\"pure-input-1 $city_required\" value=\"" . $this->_city . "\" /></div>
    <div style=\"float: left; margin-right: 1em;\"><label for=\"state\">" . _("State") . "</label>
    <input type=\"text\" name=\"state\" id=\"state\" class=\"pure-input-1 $state_required\" value=\"" . $this->_state . "\" /></div>
    <div style=\"float: left;\"><label for=\"zip\">" . _("Zip") . "</label>
    <input type=\"text\" name=\"zip\" id=\"zip\" class=\"pure-input-1 $zip_required\" value=\"" . $this->_zip . "\" /></div>
    <br />
    <div style=\"float: left; margin-right: 1em;\"><label for=\"state\">" . _("Home Phone") . "</label>
    <input type=\"text\" name=\"home_phone\" id=\"home_phone\" class=\"pure-input-1 $home_phone_required\" value=\"" . $this->_home_phone . "\" /></div>
    <div style=\"float: left;\"><label for=\"cell_phone\">" . _("Cell Phone") . "</label>
    <input type=\"text\" name=\"cell_phone\" id=\"cell_phone\" class=\"pure-input-1 $cell_phone_required\" value=\"" . $this->_cell_phone . "\" /></div>
    ";

    makePluslet ($this->_fullname, $personal_info, "no_overflow");
  }

  public function outputEmergencyInfoForm() {

    global $require_user_columns;

    // set up required fields based on fields in config
    if ( in_array( _( 'home_phone' ) , $require_user_columns ) ) { $home_phone_required = "required_field"; } else {$home_phone_required = "";}
    if ( in_array( _( 'cell_phone' ) , $require_user_columns ) ) { $cell_phone_required = "required_field"; } else {$cell_phone_required = "";}
    if ( in_array( _( 'emergency_contact' ) , $require_user_columns ) ) { $emergency_contact_required = "required_field"; } else {$emergency_contact_required = "";}

    $emergency_info = "
    <div style=\"float: left; margin-right: 1em;\"><label for=\"city\">" . _("Emergency Contact") . "</label>
    <input type=\"text\" name=\"emergency_contact_name\" id=\"emergency_contact_name\" class=\"pure-input-1 $emergency_contact_required\" value=\"" . $this->_emergency_contact_name . "\" /></div>
    <div style=\"float: left; margin-right: 1em;\"><label for=\"state\">" . _("Relationship") . "</label>
    <input type=\"text\" name=\"emergency_contact_relation\" id=\"emergency_contact_relation\" class=\"pure-input-1 $emergency_contact_required\" value=\"" . $this->_emergency_contact_relation . "\" /></div>
    <div style=\"float: left;\"><label for=\"zip\">" . _("Phone") . "</label>
    <input type=\"text\" name=\"emergency_contact_phone\" id=\"emergency_contact_phone\" class=\"pure-input-1 $emergency_contact_required\" value=\"" . $this->_emergency_contact_phone . "\" /></div>
    <br />
    ";

    makePluslet (_("Emergency Contact"), $emergency_info, "no_overflow");
  }

  public function outputLatLongForm() {

    global $require_user_columns;

    // let's stick the address together for fun
    $full_address = $this->_street_address . " " . $this->_city . " " . $this->_state . " " . $this->_zip;

    // set up required fields based on fields in config
    if ( in_array( _( 'lat_long' ) , $require_user_columns ) ) { $lat_long_required = "required_field"; } else {$lat_long_required = "";}

    $lat_long = "
    <div style=\"float: left; margin-right: 1em;\"><label for=\"city\">" . _("Latitude/Longitude") . "</label>
    <input type=\"text\" name=\"lat_long\" id=\"lat_long\" class=\"pure-input-1 $lat_long_required\" value=\"" . $this->_lat_long . "\" /></div>

    <div style=\"float: left; margin-right: 1em;\"><label for=\"city\">" . _("Get Coordinates") . "</label>
    <span class=\"lookup_button\" value=\"$full_address\">look up now</span></div>
    <br />
    ";

    makePluslet (_("Add to Map"), $lat_long, "no_overflow");

  }

  public function outputSelfEditForm() {
    // This is just the information that a user can edit about themselves
    // agd april 2014

    global $require_user_columns;
    global $omit_user_columns;

    $isPersonalOmitted = in_array( _( "personal_information" ) , $omit_user_columns );
    $isEmergencyContactOmitted = in_array( _( "emergency_contact" ) , $omit_user_columns );

    $action = htmlentities($_SERVER['PHP_SELF']) . "?staff_id=" . $this->_staff_id;

    // set up
    print "<div class=\"pure-g\">";
    // start form
    print "<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" accept-charset=\"UTF-8\" class=\"pure-form pure-form-stacked\">
    <input type=\"hidden\" name=\"staff_id\" value=\"" . $this->_staff_id . "\" />
    <div class=\"pure-u-1-3\">
    ";

    // Only display the update personal info if this isn't turned off
    if (!$isPersonalOmitted) {
      self::outputPersonalInfoForm();
    } else {
      echo "<input type=\"hidden\" name=\"street_address\" id=\"street_address\" value=\"" . $this->_street_address . "\" />\n";
      echo "<input type=\"hidden\" name=\"city\" id=\"city\" value=\"" . $this->_city . "\" />\n";
      echo "<input type=\"hidden\" name=\"state\" id=\"state\" value=\"" . $this->_state . "\" />\n";
      echo "<input type=\"hidden\" name=\"zip\" id=\"zip\" value=\"" . $this->_zip . "\" />\n";
      echo "<input type=\"hidden\" name=\"home_phone\" id=\"home_phone\" value=\"" . $this->_home_phone . "\" />\n";
      echo "<input type=\"hidden\" name=\"cell_phone\" id=\"cell_phone\" value=\"" . $this->_cell_phone . "\" />\n";
      echo "<input type=\"hidden\" name=\"lat_long\" id=\"lat_long\"value=\"" . $this->_lat_long . "\" />\n";
    }

    // Only display the emergency info if it isn't turned off
    if (!$isEmergencyContactOmitted) {
      self::outputEmergencyInfoForm();
    } else {
      echo "<input type=\"hidden\" name=\"emergency_contact_name\" id=\"emergency_contact_name\" value=\"" . $this->_emergency_contact_name . "\" />";
      echo "<input type=\"hidden\" name=\"emergency_contact_relation\" id=\"emergency_contact_relation\" value=\"" . $this->_emergency_contact_relation . "\" />\n";
      echo "<input type=\"hidden\" name=\"emergency_contact_phone\" id=\"emergency_contact_phone\" value=\"" . $this->_emergency_contact_phone . "\" />\n";
    }


    print "</div>"; // close pure-1-3

    print "<div class=\"pure-u-1-3\">";



    print "<div class=\"pluslet\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">" . _("Biographical Details") . "</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">
    <p>" . _("Please only include professional details.") . "</p><br />";

    self::outputBioForm();

    echo "</div></div>"; // end pluslet_body, end pluslet

    print "<div class=\"pluslet\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">" . _("Social Media Accounts") . "</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">";

    echo self::outputSocialMediaForm();

    echo "</div></div>"; // end pluslet_body, end pluslet




    print "</div>"; // close pure-1-3

    print "<div class=\"pure-u-1-3\">";

    $password_update = "<p><a href=\"../includes/set_password.php?staff_id=" . $this->_staff_id . "\" id=\"reset_password\">" . _("The password is hidden.  Reset?") . "</a></p>";

    makePluslet(_("Change Password?"), $password_update, "no_overflow");

    $saver = "<input type=\"submit\" name=\"submit_record\" class=\"pure-button pure-button-primary\" value=\"" . _("Update Now!") . "\" />";

    makePluslet (_("Save"), $saver, "no_overflow");

    print "</div>"; // close pure-1-3

    // now let's add all our missing/hidden data

    print "<input type=\"hidden\" name=\"staff_id\" value=\"" . $this->_staff_id . "\" />";
    print "<input type=\"hidden\" name=\"lname\" value=\"" . $this->_lname . "\" />";
    print "<input type=\"hidden\" name=\"fname\" value=\"" . $this->_fname . "\" />";
    print "<input type=\"hidden\" name=\"title\" value=\"" . $this->_title . "\" />";
    print "<input type=\"hidden\" name=\"tel\" value=\"" . $this->_tel . "\" />";
    print "<input type=\"hidden\" name=\"department_id\" value=\"" . $this->_department_id . "\" />";
    //print "<input type=\"hidden\" value=\"{$this->_department_id}\" name=\"department_id[]\" />";
    print "<input type=\"hidden\" name=\"staff_sort\" value=\"" . $this->_staff_sort . "\" />";
    print "<input type=\"hidden\" name=\"email\" value=\"" . $this->_email . "\" />";
    print "<input type=\"hidden\" name=\"user_type_id\" value=\"" . $this->_user_type_id . "\" />";
    print "<input type=\"hidden\" name=\"ptags\" value=\"" . $this->_ptags . "\" />";
    print "<input type=\"hidden\" name=\"active\" value=\"" . $this->_active . "\" />";
    print "<input type=\"hidden\" name=\"position_number\" value=\"" . $this->_position_number . "\" />";
    print "<input type=\"hidden\" name=\"job_classification\" value=\"" . $this->_job_classification . "\" />";
    print "<input type=\"hidden\" name=\"room_number\" value=\"" . $this->_room_number . "\" />";
    print "<input type=\"hidden\" name=\"supervisor_id\" value=\"" . $this->_supervisor_id . "\" />";
    print "<input type=\"hidden\" name=\"fax\" value=\"" . $this->_fax . "\" />";
    print "<input type=\"hidden\" name=\"intercom\" value=\"" . $this->_intercom . "\" />";
    print "<input type=\"hidden\" name=\"lat_long\" value=\"" . $this->_lat_long . "\" />";
    print "<input type=\"hidden\" name=\"fullname\" value=\"" . $this->_fullname . "\" />";


    print "</form>"; // close form

    print "</div>"; // close pure
  }

  public function outputPasswordForm() {
    $box = "<div class=\"box no_overflow\">
		<p>" . _("Enter the new password.  Make it a good one!") . "</p>
		<br />
		<form name=\"update_password\" method=\"post\" action=\"../includes/set_password.php\" />
		<input type=\"hidden\" name=\"action\" value=\"password\" />
		<input type=\"hidden\" name=\"staff_id\" value=\"$this->_staff_id\" />
		<p><input type=\"password\" size=\"20\" name=\"password\" value=\"\" />
		<input type=\"submit\" name=\"Submit\" value=\"Update Password!\" /></p>
		</div>";

    return $box;
  }

  public function outputEmailForm()
  {
    $lstrBox = "<div align=\"center\">\n
			<form action=\"forgotpassword.php\" method=\"post\" style=\"font-size: 1em;\">\n
			<table cellpadding=\"7\" cellspacing=\"0\" border=\"0\" class=\"striped_data\">\n
			<tr>\n
			<td valign=\"top\" class=\"odd\"><strong>" . _("Email") . "</strong></td>\n
			<td valign=\"top\" class=\"odd\" align=\"left\"><input name=\"email\" type=\"text\" size=\"20\" /></td>\n
			</tr>\n
			<tr>\n
			<td valign=\"top\" class=\"even\" colspan=\"2\"><div align=\"center\"><input type=\"submit\" value=\"Send Email\" /></div></td>\n
			</table>\n
			</form>\n
			</div>\n
			</div>\n";

    return $lstrBox;
  }

  public function outputResetPasswordForm() {
    $box = "<div align=\"center\" style=\"width: 310px\">\n
  			<form action=\"{$_SERVER['REQUEST_URI']}\" method=\"post\" style=\"font-size: 1em;\">\n
  			<table cellpadding=\"7\" cellspacing=\"0\" border=\"0\" class=\"striped_data\">\n
  			<tr>\n
  			<td valign=\"top\" class=\"odd\"><strong>" . _("New Password") . "</strong></td>\n
  			<td valign=\"top\" class=\"odd\" align=\"left\"><input name=\"password\" type=\"password\" size=\"20\" /></td>\n
  			</tr>\n
  			<tr>\n
  			<td valign=\"top\" class=\"odd\"><strong>" . _("Re-Enter Password") . "</strong></td>\n
  			<td valign=\"top\" class=\"odd\" align=\"left\"><input name=\"password_confirm\" type=\"password\" size=\"20\" /></td>\n
  			</tr>\n
  			<tr>\n
  			<td valign=\"top\" class=\"even\" colspan=\"2\"><div align=\"center\"><input type=\"submit\" value=\"Update Password\" /></div></td>\n
  			</table>\n
  			</form>\n
			</div>\n
			</div>\n";

    return $box;
  }

  public function deleteRecord() {

    // make sure they're allowed to delete
    if ($_SESSION["admin"] != "1") {
      return FALSE;
    }

    $db = new Querier;

    // Delete the records from staff table
    $q = "DELETE staff FROM staff WHERE staff.staff_id = '" . $this->_staff_id . "'";

    $delete_result = $db->exec($q);

    $this->_debug = "<p class=\"debug\">Delete from staff table(s) query: $q";

    
    if (isset($delete_result)) {
      //delete department associations
      $q2 = "DELETE FROM staff_department sd WHERE sd.staff_id = '" . $this->_staff_id . "'";
      $delete_result2 = $db->exec($q2);
       
      $this->_debug .= "<p>Del query 2: $q2";
    
    } else {
      // message
      $this->_message = _("There was a problem with your delete (stage 1 of 2).");
      return FALSE;
    }

    if (isset($delete_result2)) {

      // /////////////////////
      // Alter chchchanges table
      // table, flag, item_id, title, staff_id
      ////////////////////

      $updateChangeTable = changeMe("staff", "delete", $this->_staff_id, $this->_title, $_SESSION['staff_id']);

      $this->_message = _("Thy will be done.  Note that any subject guides associated with this user are now orphans.  Pity the orphans.");
      return false;
    } else {
      // message
      $this->_message = _("There was a problem with your delete.");
      return FALSE;
    }
  }

  public function insertRecord() {

    $db = new Querier;

    ////////////////
    // check and hash password
    ////////////////

    if( $this->correctPassword($this->_password) )
    {
      $this->_password = md5($this->_password);
    }else
    {
      $this->_message = _("Pasword must have a special character, a letter, a number, and at least 6 characters. Insert was not executed.");

      return;

    }

    ////////////////
    // check whether email is unique
    ///////////////
    if( !$this->isEmailUnique( "insert" ) )
    {
      $this->_message = _("Email is not unique. Insert was not executed.");

      return;
    }



    $qInsertStaff = "INSERT INTO staff (fname, lname, title, tel, department_id, staff_sort, email, user_type_id, password, ptags, active, bio,
      position_number, job_classification, room_number, supervisor_id, emergency_contact_name,
      emergency_contact_relation, emergency_contact_phone, street_address, city, state, zip, home_phone, cell_phone, fax, intercom, lat_long, social_media) VALUES ( "

        . $db->quote(scrubData($this->_fname)) . ","
        . $db->quote(scrubData($this->_lname)) . ","
        . $db->quote(scrubData($this->_title)) . ","
        . $db->quote(scrubData($this->_tel)) . ","
        . $db->quote($this->_department_id[0]) . ","
        . $db->quote(scrubData($this->_staff_sort, "integer")) . ","
        . $db->quote(scrubData($this->_email, "email")) . ","
        . $db->quote(scrubData($this->_user_type_id, "integer")) . ","
        . $db->quote(scrubData($this->_password)) . ","
        . $db->quote(scrubData($this->_ptags)) . ","
        . $db->quote(scrubData($this->_active, "integer")) . ","
        . $db->quote(scrubData($this->_bio, "richtext")) . ","
        . $db->quote(scrubData($this->_position_number)) . ","
        . $db->quote(scrubData($this->_job_classification)) . ","
        . $db->quote(scrubData($this->_room_number)) . ","
        . $db->quote($this->_supervisor_id) . ","
        . $db->quote(scrubData($this->_emergency_contact_name)) . ","
        . $db->quote(scrubData($this->_emergency_contact_relation)) . ","
        . $db->quote(scrubData($this->_emergency_contact_phone)) . ","
        . $db->quote(scrubData($this->_street_address)) . ","
        . $db->quote(scrubData($this->_city)) . ","
        . $db->quote(scrubData($this->_state)) . ","
        . $db->quote(scrubData($this->_zip)) . ","
        . $db->quote(scrubData($this->_home_phone)) . ","
        . $db->quote(scrubData($this->_cell_phone)) . ","
        . $db->quote(scrubData($this->_fax)) . ","
        . $db->quote(scrubData($this->_intercom)) . ","
        . $db->quote(scrubData($this->_lat_long)) . ","
        . $db->quote(scrubData($this->_social_media)) . ")";



    $rInsertStaff = $db->exec($qInsertStaff);

    $this->_debug .= "<p class=\"debug\">Insert query: $qInsertStaff</p>";


    $this->_staff_id = $db->last_id();

    /////////////////////
    // insert into staff_department
    ////////////////////

    self::modifySD();

    // create folder

    if ($this->_staff_id) {
      $user_folder = explode("@", $this->_email);
      $path = "../../assets/users/_" . $user_folder[0];




      if(!@mkdir($path)) {
        //  $mkdirErrorArray = error_get_last();
        // throw new Exception('cant create directory ' .$mkdirErrorArray['message'], 1);

        // message
        $this->_message = _("Couldn't create directory in /assets/users/. Please check this folder's permissions. ");
        return;

      }  else  {
        // And copy over the generic headshot image and headshot_large image
        $nufile = $path . "/headshot.jpg";
        $copier = copy("../../assets/images/headshot.jpg", $nufile);
        $copier = copy("../../assets/images/headshot.jpg", $path . "/headshot_large.jpg");


        // message
        $this->_message = _("Thy Will Be Done.  Added.");
      }
    }

    ///////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    //$updateChangeTable = changeMe("staff", "insert", $this->_staff_id, $this->_email, $_SESSION['staff_id']);


  }

  public function updateRecord($type="full") {

    $db = new Querier;

    ////////////////
    // check whether email is unique
    ///////////////
    if( !$this->isEmailUnique( "update" ) )
    {
      // message
      $this->_message = _("Email is not unique. Update was not executed.");
      return;
    }

    ////////////////
    // alter values that are blank that need to be saved as NULL values
    ////////////////

    if($this->_department_id == '')
    {
      $department_id = "NULL";
    }else
    {
      //$department_id = $db->quote(scrubData($this->_department_id, "integer"));
      $department_id = $this->_department_id;

    }


    if($this->_supervisor_id == '')
    {
      $supervisor_id = "NULL";
    }else
    {
      $supervisor_id = $db->quote(scrubData($this->_supervisor_id, "integer"));
    }

    if($this->_user_type_id == '')
    {
      $user_type_id = "NULL";
    }else
    {
      $user_type_id = $db->quote(scrubData($this->_user_type_id, "integer"));
    }

    /////////////////////
    // update staff table -- Full
    // NOTE:  we don't update the password here; it's updated separately
    /////////////////////

    // oct 2015 removed department_id from this update, since it now has a table in v4 agd

    $qUpStaff = "UPDATE staff SET
	  fname = " . $db->quote(scrubData($this->_fname)) . "," .
        "lname = " . $db->quote(scrubData($this->_lname)) . "," .
        "title = " . $db->quote(scrubData($this->_title)) . "," .
        "tel = " . $db->quote(scrubData($this->_tel)) . "," .
        "staff_sort = " . $db->quote(scrubData($this->_staff_sort, 'integer')) . "," .
        "email = " . $db->quote(scrubData($this->_email, 'email')) . "," .
        "user_type_id = " . $db->quote(scrubData($this->_user_type_id, 'integer')) . "," .
        "ptags = " . $db->quote(scrubData($this->_ptags)) . "," .
        "active = " . $db->quote(scrubData($this->_active, 'integer')) . "," .
        "bio = " . $db->quote(scrubData($this->_bio, 'richtext')) . "," .
        "position_number = " . $db->quote(scrubData($this->_position_number)) . "," .
        "job_classification = " . $db->quote(scrubData($this->_job_classification)) . "," .
        "room_number = " . $db->quote(scrubData($this->_room_number)) . "," .
        "supervisor_id = " . $supervisor_id . "," .
        "emergency_contact_name = " . $db->quote(scrubData($this->_emergency_contact_name)) . "," .
        "emergency_contact_relation = " . $db->quote(scrubData($this->_emergency_contact_relation)) . "," .
        "emergency_contact_phone = " . $db->quote(scrubData($this->_emergency_contact_phone)) . "," .
        "street_address = " . $db->quote(scrubData($this->_street_address)) . "," .
        "city = " . $db->quote(scrubData($this->_city)) . "," .
        "state = " . $db->quote(scrubData($this->_state)) . "," .
        "zip = " . $db->quote(scrubData($this->_zip)) . "," .
        "home_phone = " . $db->quote(scrubData($this->_home_phone)) . "," .
        "cell_phone = " . $db->quote(scrubData($this->_cell_phone)) . "," .
        "fax = " . $db->quote(scrubData($this->_fax)) . "," .
        "intercom = " . $db->quote(scrubData($this->_intercom)) . "," .

        "extra = " . $db->quote(scrubData($this->_extra)) . "," .
        "social_media = " . $db->quote(scrubData($this->_social_media)) . "," .
        "lat_long = " . $db->quote(scrubData($this->_lat_long)) .
        " WHERE staff_id = " . scrubData($this->_staff_id, 'integer');

    //echo $qUpStaff;
    $rUpStaff = $db->exec($qUpStaff);

    if ($type == "full") {

      /////////////////////
      // clear staff_department
      /////////////////////

      $qClearSD = "DELETE FROM staff_department WHERE staff_id = " . $this->_staff_id;

      $rClearSD = $db->exec($qClearSD);

      $this->_debug .= "<p>2. clear staff_department: $qClearSD</p>";

        /////////////////////
        // insert into staff_department
        // but only for full record
        ////////////////////

        self::modifySD();
    }

    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("staff", "update", $this->_staff_id, $this->_email, $_SESSION['staff_id']);

    // message
    $this->_message = _("Thy Will Be Done.  Updated.");
  }

  public function updatePassword($new_pass) {

    $db = new Querier;

    $q = "UPDATE staff SET password = md5( " . $db->quote(scrubData($new_pass)) . " ) WHERE staff_id = " . $this->_staff_id;

    $this->_debug = "<p class=\"debug\">Password Update query: $q</p>";

    $r = $db->exec($q);

    if ($r) {
      $updateChangeTable = changeMe("staff", "update", $this->_staff_id, "password update", $_SESSION['staff_id']);

      return TRUE;
    }
  }

  public function updateBio($new_bio) {

    $db = new Querier;

    $q = "UPDATE staff SET bio = " . $db->quote(scrubData($new_bio, "richtext")) . " WHERE staff_id = " . $this->_staff_id;

    $this->_debug = "<p class=\"debug\">Bio Update query: $q</p>";

    $r = $db->exec($q);
    // now our detailed version
    $q2 = "UPDATE staff SET bio = " . $db->quote(scrubData($new_bio, "richtext")) . " WHERE staff_id = " . $this->_staff_id;

    $this->_debug .= "<p class=\"debug\">Bio Update query: $q2</p>";

    $r2 = $db->query($q2);

    if ($r) {
      $updateChangeTable = changeMe("staff", "update", $this->_staff_id, "bio update", $_SESSION['staff_id']);
      return TRUE;
    }
  }

  public function getHeadshot($email, $pic_size="medium") {

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
      $config['toolbar'] = 'Basic';// Default shows a much larger set of toolbar options
      $config['height'] = '300';
      $config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

      echo $oCKeditor->editor('bio', $this->_bio, $config);
      echo "<br />";
    } else {
      echo "<textarea name=\"answer\" rows=\"6\" cols=\"70\">" . stripslashes($this->_answer) . "</textarea>";
    }
  }

  function outputSocialMediaForm() {
    $socialMediaForm = "";

    $extra = $this->getSocialMediaDataArray();

    $objSM = new SocialMedia();
    $smAccounts = $objSM->toArray();

    foreach($smAccounts as $account):
      $accountName = strtolower($account['name']);

      $socialMediaForm .= "<label for='social-{$accountName}'>{$account['name']}</label>";
      $socialMediaForm .= "<input type='text' name='social-{$accountName}' value='{$extra[$accountName]}' />";
    endforeach;

    return $socialMediaForm;
  }


  protected function getSocialMediaDataArray() {

    $querier = new  Querier();
    $q1 = "select social_media from staff where staff_id = '" . $this->_staff_id . "'";
    $staffArray = $querier->query($q1);

    $extra = array();

    if($staffArray != null){
      $json = html_entity_decode($staffArray[0]['social_media']);
      $extra = json_decode($json, true);
    } else {
      $extra['facebook'] = "";
      $extra['twitter'] = "";
      $extra['pinterest'] = "";
      $extra['instagram'] = "";
    }

    return $extra;
  }

  protected function setSocialMediaDataPost() {

    $data = array();
    foreach($_POST as $key => $value):
      $account_key = explode('-', $key);

      if(!empty($account_key[1])) {
        $post_value_name = 'social-'.$account_key[1];
        $data[$account_key[1]] = $_POST[$post_value_name] ;
      }
    endforeach;

    $data = json_encode($data);
    
    return $data;
  }

    public function getAssociatedDepartments()
    {

        $db = new Querier();
        $q2 = "SELECT d.department_id FROM department d, staff_department sd 
        WHERE d.department_id = sd.department_id AND sd.staff_id = " . $this->_staff_id;

        $this->_departmenters = $db->query($q2);

        foreach ($this->_departmenters as $value) {
            $this->_ok_departments[] = $value[0];
        }

        $this->_debug .= "<p>Department query: $q2";
    }

    function modifySD()
    {

        $de_duped = array_unique($this->_department_id);

        foreach ($de_duped as $value) {
            if (is_numeric($value)) {
                $db = new Querier;
                $qUpSD = "INSERT INTO staff_department (staff_id, department_id) VALUES (
        " . scrubData($this->_staff_id, 'integer') . ",
        " . scrubData($value, 'integer') . ")";
                $db = new Querier;
                $rUpSD = $db->exec($qUpSD);

                $this->_debug .= "<p>3. (insert staff_department loop) : $qUpSD</p>";

            }
        }
    }

  public function getMessage() {
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

  function getEmail()
  {
    return $this->_email;
  }

  function deBug() {
    echo $this->_debug;
  }

  function correctPassword($lstrPassword)
  {
    if( strlen( $lstrPassword ) < 6 ) return false;

    $lstrExpression = '/^.*(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*]).*$/';

    $lintMatch = preg_match($lstrExpression, $lstrPassword);

    if($lintMatch > 0)
    {
      return true;
    }

    return false;
  }

  function getCoordinates() {

  }

  function isEmailUnique($lstrType = "")

  {
    $db = new Querier;
    switch (strtolower( $lstrType ))
    {
      case "insert":
        $lstrQuery = "SELECT email FROM staff WHERE email = " . $db->quote(scrubData($this->_email, "email"));
        break;
      case "update":
        $lstrQuery = "SELECT email FROM staff WHERE email = " . $db->quote(scrubData($this->_email, "email")) . "AND staff_id <> " . scrubData($this->_staff_id, "integer");
        break;
      default:
        return false;
    }
    $lrscSQL = $db->query($lstrQuery);
    $lintNumberOfRows = count($lrscSQL);
    if( $lintNumberOfRows > 0 ) return false;
    return true;
  }
}
