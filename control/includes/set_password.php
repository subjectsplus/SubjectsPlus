<?php

/**
 *   @file set_password.php
 *   @brief update staff picture
 *
 *   @author adarby
 *   @date Sep 17, 2009
 *   @todo file uploader so staff can add their own pictures.
 */
    

use SubjectsPlus\Control\Staff;
    
$subsubcat = "";
$subcat = "";
$feedback = "";
$page_title = "Set Password";

$no_header = "yes";

include("../includes/header.php");

// Connect to database


// Make sure they have permission to change a password
// needs to be either theirs, or they're an admin

if ($_REQUEST["staff_id"] != $_SESSION["staff_id"] && $_SESSION["admin"] != "1") {

    echo "<p>" . _("You are not authorized to view this.") . "</p>";
    exit;
}

if (is_numeric($_REQUEST["staff_id"])) {
    $staff_id = $_REQUEST["staff_id"];
} else {
    print _("Perhaps you have come here by a funny path?");
    exit;
}

/// Create our record
$record = new Staff($staff_id);
// Generate form box
$password_box = $record->outputPasswordForm();

$staff_name = $record->getFullName();

// See if a password has been submitted
if (isset($_POST['action']) && ($_POST['action'] == 'password')) {
    if ($_POST["password"] != "") {
    	if( $record->correctPassword( $_POST[ 'password' ] ) )
    	{
	    	$pass_result = $record->updatePassword($_POST["password"]);
	        if ($pass_result == TRUE) {
	            $feedback = "<div class=\"box\">" . _("Password updated.  Close this box to continue.") . "</div>";
	            $password_box = "";
	        } else {
	            $feedback = "<div class=\"box\">" . _("There was a problem.  Contact the admin.") . "</div>";
	        }
    	}else{
    		$feedback = "<div class=\"box\">" . _("Password must have at least one letter, one number, one special character, and be at least 6 characters long.") . "</div>";
    	}
    } else {
        $feedback = "<div class=\"box\">" . _("You cannot leave the password box blank.  Close this window if you don't want to change the password.") . "</div>";
    }
}

print "<div id=\"maincontent\">
<h2 class=\"bw_head\">" . _("Update Password for ") . " $staff_name</h2>";
print $feedback;
print $password_box;
print "</div>";

include("../includes/footer.php");

?>