<?php

/**
 *   @file sp_Staff
 *   @brief manage staff
 *
 *   @author agdarby, rgilmour
 *   @date Jan 2011

 */
class sp_Staff {

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

  public function __construct($staff_id="", $flag="", $full_record = FALSE) {

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

        break;
      case "delete":
        // kind of redundant, but just set up to delete appropriate tables?
        // title_id only needed?
        $this->_staff_id = $staff_id;

        break;
      case "forgot":
      	$this->_email = $_POST['email'];

      	/////////////
      	// Get staff table info
      	/////////////
      	$querier = new sp_Querier();
      	$q1 = "select staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, password, ptags, active, bio from staff where email = '" . $this->_email . "'";
      	$staffArray = $querier->getResult($q1);

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
      	}
      	break;
      default:
        $this->_staff_id = $staff_id;

        /////////////
        // Get staff table info
        // Don't call full record for regular log in
        /////////////
        $querier = new sp_Querier();
        if ($full_record == TRUE) {
        $q1 = "SELECT staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, password, ptags, active, bio
            , position_number, job_classification, room_number, supervisor_id, emergency_contact_name, emergency_contact_relation, emergency_contact_phone,
            street_address, city, state, zip, home_phone, cell_phone, fax, intercom, lat_long
            FROM staff WHERE staff_id = " . $this->_staff_id;
        } else {
        $q1 = "SELECT staff_id, lname, fname, title, tel, department_id, staff_sort, email, ip, user_type_id, ptags, active, bio
            FROM staff WHERE staff_id = " . $this->_staff_id;
        }

        $staffArray = $querier->getResult($q1);

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

    $querierDept = new sp_Querier();
    $qDept = "select department_id, name from department order by name";
    $deptArray = $querierDept->getResult($qDept);

    // create department dropdown
    $deptMe = new sp_Dropdown("department_id", $deptArray, $this->_department_id);
    $this->_departments = $deptMe->display();

    ///////////////
    // User Types
    ///////////////

    $querierUserType = new sp_Querier();
    $qUserType = "select user_type_id, user_type from user_type order by user_type_id";
    $userTypeArray = $querierUserType->getResult($qUserType);

    // create type dropdown
    $typeMe = new sp_Dropdown("user_type_id", $userTypeArray, $this->_user_type_id);
    $this->_user_types = $typeMe->display();

    ///////////////
    // Supervisor
    ///////////////

    $querierSupervisor = new sp_Querier();
    $qSupervisor = "select staff_id, CONCAT( fname, ' ', lname ) AS fullname FROM staff WHERE ptags LIKE '%supervisor%' AND active = '1' ORDER BY lname";
    $supervisorArray = $querierSupervisor->getResult($qSupervisor);

    // create type dropdown
    $superviseMe = new sp_Dropdown("supervisor_id", $supervisorArray, $this->_supervisor_id, '', '* External Supervisor');
    $this->_supervisors = $superviseMe->display();
    ///////////////
    // Active User?
    ///////////////

    $activeArray = array(
        '0' => array('0', 'No'),
        '1' => array('1', 'Yes')
    );

    // create type dropdown
    $activateMe = new sp_Dropdown("active", $activeArray, $this->_active);
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

    // let's stick the address together for fun
    $full_address = $this->_street_address . " " . $this->_city . " " . $this->_state . " " . $this->_zip;

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
<h2 class=\"bw_head\">" . _("Staff Member") . "</h2>
<div class=\"box\">
<div style=\"float: left;\">
<span class=\"record_label\">" . _("First Name ") . "</span><br />
<input type=\"text\" name=\"fname\" id=\"fname\" size=\"30\" class=\"required_field\" value=\"" . $this->_fname . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Last Name ") . "</span><br />
<input type=\"text\" name=\"lname\" id=\"lname\" size=\"30\" class=\"required_field\" value=\"" . $this->_lname . "\" />
</div>
<br style=\"clear: both;\"/><br />";

  	//see which"Staff Member" columns and whether "Personal Information" section or "Emergency Contact" section are omitted
  	// added by dgonzalez
  	$isTitleOmitted = in_array( _( "title" ) , $omit_user_columns );
  	$isPositionNumOmitted = in_array( _( "position_number" ) , $omit_user_columns );
  	$isClassificationOmitted = in_array( _( "classification" ) , $omit_user_columns );
  	$isDepartmentOmitted = in_array( _( "department" ) , $omit_user_columns );
  	$isPriorityOmitted = in_array( _( "priority" ) , $omit_user_columns );
  	$isSupervisorOmitted = in_array( _( "supervisor" ) , $omit_user_columns );
  	$isTelephoneOmitted = in_array( _( "tel" ) , $omit_user_columns );
  	$isdFaxOmitted = in_array( _( "fax" ) , $omit_user_columns );
  	$isIntercomOmitted = in_array( _( "intercom" ) , $omit_user_columns );
  	$isRoomNumOmiited = in_array( _( "room_number" ) , $omit_user_columns );
  	$isUserTypeOmitted = in_array( _( "user_type" ) , $omit_user_columns );
  	$isPersonalOmitted = in_array( _( "personal_information" ) , $omit_user_columns );
  	$isEmergencyContactOmitted = in_array( _( "emergency_contact" ) , $omit_user_columns );

  	//based on omitted columns write out html
  	// added by dgonzalez
  	if ( $isTitleOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"title\" id=\"title\" value=\"" . $this->_title . "\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Position Title") . "</span><br />
<input type=\"text\" name=\"title\" id=\"title\" size=\"50\" class=\"";
  		if ( in_array( _( 'title' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_title . "\" />
</div>";
  	}

  	if ( $isPositionNumOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"position_number\" id=\"position_number\" value=\"" . $this->_position_number . "\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Postion #") . "</span><br />
<input type=\"text\" name=\"position_number\" id=\"position_number\" size=\"5\" class=\"";
  		if ( in_array( _( 'position_number' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_position_number . "\" />
</div>";
  	}

  	if ( !( $isTitleOmitted && $isPositionNumOmitted) )
  	{
  		echo "<br style=\"clear: both;\"/><br />";
  	}

  	if ( $isClassificationOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"job_classification\" id=\"job_classification\" value=\"" . $this->_job_classification . "\" />";
  	}else
  	{
  		echo "
<span class=\"record_label\">" . _("Classification") . "</span><br />
<input type=\"text\" name=\"job_classification\" id=\"job_classification\" size=\"40\" class=\"";
  		if ( in_array( _( 'classification' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_job_classification . "\" /><br /><br />";
  	}

  	if ( $isDepartmentOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"department_id\" id=\"department_id\" value=\"\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Department") . "</span><br />
{$this->_departments}
</div>";
  	}

  	if ( $isPriorityOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"staff_sort\" id=\"staff_sort\" value=\"" . $this->_staff_sort . "\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;margin-left: 10px;\">
<span class=\"record_label\">" . _("Priority") . "</span><br />
<input type=\"text\" name=\"staff_sort\" id=\"staff_sort\" size=\"2\" class=\"";
  		if ( in_array( _( 'priority' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_staff_sort . "\" />
</div>";
  	}

  	if ( !( $isDepartmentOmitted && $isPriorityOmitted ) )
  	{
  		echo "<br style=\"clear: both;\" /><br />";
  	}

  	if ( $isSupervisorOmitted )
  	{
  		echo "<input type=\"hidden\" id=\"supervisor_id\" name=\"supervisor_id\" value=\"\" />";
  	}else
  	{
  		echo "
<span class=\"record_label\">" . _("Supervisor") . "</span><br />
{$this->_supervisors}
<br /><br />";
  	}

  	if ( $isTelephoneOmitted )
  	{
  		echo "<input id=\"tel\" type=\"hidden\" value=\"" . $this->_tel . "\" name=\"tel\">";
  	}else{
  		echo "
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Telephone") . "</span><br />
$tel_line
</div>";
  	}

  	if ( $isdFaxOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"fax\" id=\"fax\" value=\"" . $this->_fax . "\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;margin-left: 20px;\">
<span class=\"record_label\">" . _("FAX") . "</span><br />
<input type=\"text\" name=\"fax\" id=\"fax\" size=\"15\" class=\"";
  		if ( in_array( _( 'fax' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_fax . "\" />
</div>";
  	}

  	if ( $isIntercomOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"intercom\" id=\"intercom\" value=\"" . $this->_intercom . "\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;margin-left: 20px;\">
<span class=\"record_label\">" . _("Intercom") . "</span><br />
<input type=\"text\" name=\"intercom\" id=\"intercom\" class=\"";
  		if ( in_array( _( 'intercom' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" size=\"5\" value=\"" . $this->_intercom . "\" />
</div>";
  	}

  	if ( $isRoomNumOmiited )
  	{
  		echo "<input type=\"hidden\" name=\"room_number\" id=\"room_number\" value=\"" . $this->_room_number . "\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;margin-left: 20px;\">
<span class=\"record_label\">" . _("Room #") . "</span><br />
<input type=\"text\" name=\"room_number\" id=\"room_number\" class=\"";
  		if ( in_array( _( 'room_number' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" size=\"5\" value=\"" . $this->_room_number . "\" />
</div>";
  	}

  	if ( !( $isTelephoneOmitted && $isdFaxOmitted && $isIntercomOmitted && $isRoomNumOmiited ) )
  	{
  		echo "<br style=\"clear: both;\"/><br />";
  	}

  	echo "
<span class=\"record_label\">" . _("Email (This is the username for logging in to SubjectsPlus)") . "</span><br />
<input type=\"text\" name=\"email\" id=\"email\" size=\"40\" class=\"required_field\" value=\"" . $this->_email . "\" />
<br /><br />";

  	if ( $isUserTypeOmitted )
  	{
  		echo "<input type=\"hidden\" name=\"user_type_id\" id=\"user_type_id\" value=\"\" />";
  	}else
  	{
  		echo "
<div style=\"float: left;\">
<span class=\"record_label\">" . _("User Type") . "</span><br />
{$this->_user_types}
</div>";
  	}
  		echo "
<div style=\"float: left;margin-left: 20px;\">
<span class=\"record_label\">" . _("Active User?") . "</span><br />
{$this->_active_or_not}
</div><br /><br /></div>";

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
  		echo "
<h2 class=\"bw_head\">" . _("Personal Information") . "</h2>
<div class=\"box no_overflow\">
<span class=\"record_label\">" . _("Street Address") . "</span><br />
<input type=\"text\" name=\"street_address\" id=\"street_address\" size=\"50\" class=\"";
  		if ( in_array( _( 'address' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_street_address . "\" />
<br /><br />
<div style=\"float: left;\">
<span class=\"record_label\">" . _("City") . "</span><br />
<input type=\"text\" name=\"city\" id=\"city\" size=\"20\" class=\"";
  		if ( in_array( _( 'city' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_city . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("State") . "</span><br />
<input type=\"text\" name=\"state\" id=\"state\" size=\"10\" class=\"";
  		if ( in_array( _( 'state' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_state . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Zip") . "</span><br />
<input type=\"text\" name=\"zip\" id=\"zip\" size=\"5\" class=\"";
  		if ( in_array( _( 'zip' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" value=\"" . $this->_zip . "\" />
</div>
<br style=\"clear: both;\"/><br />
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Home Phone") . "</span><br />
<input type=\"text\" name=\"home_phone\" id=\"home_phone\" class=\"";
  		if ( in_array( _( 'home_phone' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" size=\"15\" value=\"" . $this->_home_phone . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Cell Phone") . "</span><br />
<input type=\"text\" name=\"cell_phone\" id=\"cell_phone\" class=\"";
  		if ( in_array( _( 'cell_phone' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" size=\"15\" value=\"" . $this->_cell_phone . "\" />
</div>
<br style=\"clear: both;\"/><br />
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Latitude, Longitude") . "</span><br />
<input type=\"text\" name=\"lat_long\" id=\"lat_long\" class=\"";
  		if ( in_array( _( 'lat_long' ) , $require_user_columns ) ) echo 'required_field';
  		echo "
\" size=\"25\" value=\"" . $this->_lat_long . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Get Coordinates") . "</span><br />
<span class=\"lookup_button\" value=\"$full_address\">look up now</span>
</div>
</div>";
  	}

	if ( $isEmergencyContactOmitted )
	{
		echo "<input type=\"hidden\" name=\"emergency_contact_name\" id=\"emergency_contact_name\" value=\"" . $this->_emergency_contact_name . "\" />";
		echo "<input type=\"hidden\" name=\"emergency_contact_relation\" id=\"emergency_contact_relation\" value=\"" . $this->_emergency_contact_relation . "\" />\n";
		echo "<input type=\"hidden\" name=\"emergency_contact_phone\" id=\"emergency_contact_phone\" value=\"" . $this->_emergency_contact_phone . "\" />\n";

	}else
	{
		echo "
<h2 class=\"bw_head\">" . _("Emergency Contact Information") . "</h2>
<div class=\"box no_overflow\">
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Emergency Contact") . "</span><br />
<input type=\"text\" name=\"emergency_contact_name\" id=\"emergency_contact_name\" size=\"30\" class=\"";
		if ( in_array( _( 'emergency_contact' ) , $require_user_columns ) ) echo 'required_field';
		echo "
\" value=\"" . $this->_emergency_contact_name . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Relationship") . "</span><br />
<input type=\"text\" name=\"emergency_contact_relation\" id=\"emergency_contact_relation\" size=\"15\" class=\"";
		if ( in_array( _( 'emergency_contact' ) , $require_user_columns ) ) echo 'required_field';
		echo "
\" value=\"" . $this->_emergency_contact_relation . "\" />
</div>
<div style=\"float: left;\">
<span class=\"record_label\">" . _("Phone") . "</span><br />
<input type=\"text\" name=\"emergency_contact_phone\" id=\"emergency_contact_phone\" size=\"15\" class=\"";
		if ( in_array( _( 'emergency_contact' ) , $require_user_columns ) ) echo 'required_field';
		echo "
\" value=\"" . $this->_emergency_contact_phone . "\" />
</div>
</div>";
	}

  	echo "
<h2 class=\"bw_head\">" . _("Photo") . "</h2>
<div class=\"box no_overflow\">
$headshot
</div>
<h2 class=\"bw_head\">" . _("Biographical Details") . "</h2>
<div class=\"box no_overflow\">
<p>" . _("Please only include professional details.") . "</p><br />";

    self::outputBioForm();

    echo "
<br />
</div>
</div>
<!-- right hand column -->
<div style=\"float: left; max-width: 400px;\">
<h2 class=\"bw_head\">" . _("Permissions") . "</h2>
<div class=\"box\">
";

// Get our permission tags, or ptags

    $current_ptags = explode("|", $this->_ptags);
    foreach ($all_ptags as $value) {
      if (in_array($value, $current_ptags)) {
        echo " <span class=\"ctag-on\">$value</span> ";
      } else {
        echo " <span class=\"ctag-off\">$value</span> ";
      }
    }

    echo "<input type=\"hidden\" name=\"ptags\" value=\"$this->_ptags\" /><br style=\"clear: both;\" /><p style=\"font-size: smaller\">";
    echo _("Select which parts of SubjectsPlus this user may access.
                <br /><strong>records</strong> allows access to both the Record and Guide tabs.
                <br /><strong>eresource_mgr</strong> allows the user to see all the information about a Record (and delete it), and quickly see all guides.
                <br /><strong>admin</strong> allows access to the overall admin of the site.
                <br /><strong>supervisor</strong> means user shows up in list of _supervisors
                <br /><strong>view_map</strong> lets user see the map of where everyone lives.  Probably only for muckymucks.
                <br /><strong>others</strong> are hopefully self-explanatory");
echo "</div>
	<h2 class=\"bw_head\">" . _("Password") . "</h2>
	<div class=\"box\">";

    if ($this->_staff_id != "") {
      echo "<p style=\"\"><a href=\"../includes/set_password.php?staff_id=" . $this->_staff_id . "\" id=\"reset_password\">" . _("The password is hidden.  Reset?") . "</a></p>
        ";
    } else {
      echo "<input type=\"password\" name=\"password\" size=\"20\" class=\"required_field\" /><br />
		<p style=\"font-size: smaller\">The password is stored as a hash in the database, but unless you have SSL travels clear text across the internet.</p>";
    }

    echo "
	</div>
	<h2 class=\"bw_head\">" . _("Save") . "</h2>
	<div id=\"record_buttons\" class=\"box\">
		<input type=\"submit\" name=\"submit_record\" class=\"save_button\" value=\"" . _("Save Record Now") . "\" />";
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

    // Delete the records from staff table
    $q = "DELETE staff FROM staff WHERE staff.staff_id = '" . $this->_staff_id . "'";

    $delete_result = mysql_query($q);

    $this->_debug = "<p class=\"debug\">Delete from staff table(s) query: $q";

    if (mysql_affected_rows() != 0) {

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

    $this->_password = md5($this->_password);

  	////////////////
  	// alter values that are blank that need to be saved as NULL values
  	////////////////

  	if($this->_department_id == '')
  	{
  		$department_id = "NULL";
  	}else
  	{
  		$department_id = mysql_real_escape_string(scrubData($this->_department_id, "integer"));
  	}

  	if($this->_supervisor_id == '')
  	{
  		$supervisor_id = "NULL";
  	}else
  	{
  		$supervisor_id = mysql_real_escape_string(scrubData($this->_supervisor_id, "integer"));
  	}

  	if($this->_user_type_id == '')
  	{
  		$user_type_id = "NULL";
  	}else
  	{
  		$user_type_id = mysql_real_escape_string(scrubData($this->_user_type_id, "integer"));
  	}

    ////////////////
    // Insert staff > full table
    ////////////////

    $qInsertStaff = "INSERT INTO staff (fname, lname, title, tel, department_id, staff_sort, email, user_type_id, password, ptags, active, bio,
      position_number, job_classification, room_number, supervisor_id, emergency_contact_name,
      emergency_contact_relation, emergency_contact_phone, street_address, city, state, zip, home_phone, cell_phone, fax, intercom, lat_long) VALUES (
		'" . mysql_real_escape_string(scrubData($this->_fname)) . "',
		'" . mysql_real_escape_string(scrubData($this->_lname)) . "',
		'" . mysql_real_escape_string(scrubData($this->_title)) . "',
		'" . mysql_real_escape_string(scrubData($this->_tel)) . "',
		" . $department_id . ",
		'" . mysql_real_escape_string(scrubData($this->_staff_sort, "integer")) . "',
		'" . mysql_real_escape_string(scrubData($this->_email, "email")) . "',
		" . $user_type_id . ",
		'" . mysql_real_escape_string(scrubData($this->_password)) . "',
		'" . mysql_real_escape_string(scrubData($this->_ptags)) . "',
        '" . mysql_real_escape_string(scrubData($this->_active, "integer")) . "',
        '" . mysql_real_escape_string(scrubData($this->_bio, "richtext")) . "',
		'" . mysql_real_escape_string(scrubData($this->_position_number)) . "',
		'" . mysql_real_escape_string(scrubData($this->_job_classification)) . "',
		'" . mysql_real_escape_string(scrubData($this->_room_number)) . "',
		" . $supervisor_id . ",
		'" . mysql_real_escape_string(scrubData($this->_emergency_contact_name)) . "',
		'" . mysql_real_escape_string(scrubData($this->_emergency_contact_relation)) . "',
		'" . mysql_real_escape_string(scrubData($this->_emergency_contact_phone)) . "',
		'" . mysql_real_escape_string(scrubData($this->_street_address)) . "',
		'" . mysql_real_escape_string(scrubData($this->_city)) . "',
		'" . mysql_real_escape_string(scrubData($this->_state)) . "',
		'" . mysql_real_escape_string(scrubData($this->_zip)) . "',
		'" . mysql_real_escape_string(scrubData($this->_home_phone)) . "',
		'" . mysql_real_escape_string(scrubData($this->_cell_phone)) . "',
		'" . mysql_real_escape_string(scrubData($this->_fax)) . "',
		'" . mysql_real_escape_string(scrubData($this->_intercom)) . "',
        '" . mysql_real_escape_string(scrubData($this->_lat_long)) . "'
		)";
    //print $qInsertStaff;
    $rInsertStaff = mysql_query($qInsertStaff);

    $this->_debug .= "<p class=\"debug\">Insert query: $qInsertStaff</p>";

    if (!$rInsertStaff) {
      echo blunDer("We have a problem with the insert staff query: $qInsertStaff");
    }

    $this->_staff_id = mysql_insert_id();

    // create folder

    if ($this->_staff_id) {
      $user_folder = explode("@", $this->_email);
      $path = "../../assets/users/_" . $user_folder[0];
      mkdir($path);

      // And copy over the generic headshot image
      $nufile = $path . "/headshot.jpg";
      $copier = copy("../../assets/images/headshot.jpg", $nufile);
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

  	////////////////
  	// alter values that are blank that need to be saved as NULL values
  	////////////////

  	if($this->_department_id == '')
  	{
  		$department_id = "NULL";
  	}else
  	{
  		$department_id = mysql_real_escape_string(scrubData($this->_department_id, "integer"));
  	}

  	if($this->_supervisor_id == '')
  	{
  		$supervisor_id = "NULL";
  	}else
  	{
  		$supervisor_id = mysql_real_escape_string(scrubData($this->_supervisor_id, "integer"));
  	}

  	if($this->_user_type_id == '')
  	{
  		$user_type_id = "NULL";
  	}else
  	{
  		$user_type_id = mysql_real_escape_string(scrubData($this->_user_type_id, "integer"));
  	}

    /////////////////////
    // update staff table -- Full
    // NOTE:  we don't update the password here; it's updated separately
    /////////////////////

    $qUpStaff = "UPDATE staff SET
	  fname = '" . mysql_real_escape_string(scrubData($this->_fname)) . "',
	  lname = '" . mysql_real_escape_string(scrubData($this->_lname)) . "',
	  title = '" . mysql_real_escape_string(scrubData($this->_title)) . "',
	  tel = '" . mysql_real_escape_string(scrubData($this->_tel)) . "',
	  department_id = " . $department_id . ",
	  staff_sort = '" . mysql_real_escape_string(scrubData($this->_staff_sort, "integer")) . "',
	  email = '" . mysql_real_escape_string(scrubData($this->_email, "email")) . "',
	  user_type_id = " . mysql_real_escape_string(scrubData($this->_user_type_id, "integer")) . ",
	  ptags = '" . mysql_real_escape_string(scrubData($this->_ptags)) . "',
      active = '" . mysql_real_escape_string(scrubData($this->_active, "integer")) . "',
      bio = '" . mysql_real_escape_string(scrubData($this->_bio, "richtext")) . "',
	  position_number = '" . mysql_real_escape_string(scrubData($this->_position_number)) . "',
	  job_classification = '" . mysql_real_escape_string(scrubData($this->_job_classification)) . "',
	  room_number = '" . mysql_real_escape_string(scrubData($this->_room_number)) . "',
	  supervisor_id = " . $supervisor_id . ",
	  emergency_contact_name = '" . mysql_real_escape_string(scrubData($this->_emergency_contact_name)) . "',
	  emergency_contact_relation = '" . mysql_real_escape_string(scrubData($this->_emergency_contact_relation)) . "',
	  emergency_contact_phone = '" . mysql_real_escape_string(scrubData($this->_emergency_contact_phone)) . "',
	  street_address = '" . mysql_real_escape_string(scrubData($this->_street_address)) . "',
	  city = '" . mysql_real_escape_string(scrubData($this->_city)) . "',
      state = '" . mysql_real_escape_string(scrubData($this->_state)) . "',
	  zip = '" . mysql_real_escape_string(scrubData($this->_zip)) . "',
	  home_phone = '" . mysql_real_escape_string(scrubData($this->_home_phone)) . "',
	  cell_phone = '" . mysql_real_escape_string(scrubData($this->_cell_phone)) . "',
      fax = '" . mysql_real_escape_string(scrubData($this->_fax)) . "',
	  intercom = '" . mysql_real_escape_string(scrubData($this->_intercom)) . "',
      lat_long = '" . mysql_real_escape_string(scrubData($this->_lat_long)) . "'
	  WHERE staff_id = " . scrubData($this->_staff_id, "integer");

    $rUpStaff = mysql_query($qUpStaff);

    $this->_debug = "<p class=\"debug\">Update query: $qUpStaff</p>";

    if (!$rUpStaff) {
      print "affected rows = " . mysql_affected_rows();
      echo blunDer("We have a problem with the update staff query: $qUpStaff");
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

    $q = "UPDATE staff SET password = md5('" . mysql_real_escape_string(scrubData($new_pass)) . "') WHERE staff_id = " . $this->_staff_id;

    $this->_debug = "<p class=\"debug\">Password Update query: $q</p>";

    $r = MYSQL_QUERY($q);

    if ($r) {
      $updateChangeTable = changeMe("staff", "update", $this->_staff_id, "password update", $_SESSION['staff_id']);

      return TRUE;
    }
  }

  public function updateBio($new_bio) {

    $q = "UPDATE staff SET bio = '" . mysql_real_escape_string(scrubData($new_bio, "richtext")) . "' WHERE staff_id = " . $this->_staff_id;

    $this->_debug = "<p class=\"debug\">Bio Update query: $q</p>";

    $r = MYSQL_QUERY($q);
    // now our detailed version
    $q2 = "UPDATE staff SET bio = '" . mysql_real_escape_string(scrubData($new_bio, "richtext")) . "' WHERE staff_id = " . $this->_staff_id;

    $this->_debug .= "<p class=\"debug\">Bio Update query: $q2</p>";

    $r2 = MYSQL_QUERY($q2);

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

  function getEmail()
  {
    return $this->_email;
  }

  function deBug() {
    print $this->_debug;
  }

  function correctPassword($lstrPassword)
  {
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

}

?>