<?php
/**
 *   @file set_bio.php
 *   @brief update biographical information
 *
 *   @author adarby
 *   @date Oct 2012
 *   @todo 
 */


use SubjectsPlus\Control\Staff;
    
$subsubcat = "";
$subcat = "";
$feedback = "";
$page_title = "Set Biography";

$no_header = "yes";

include("../includes/header.php");

// Connect to database
try {
  } catch (Exception $e) {
  echo $e;
}

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
$staff_name = $record->getFullName();

// See if a password has been submitted
if (isset($_POST['add_bio'])) {

  $p_result = $record->updateBio($_POST["bio"]);

  if ($p_result) {
    $feedback = "<div class=\"feedback\">" . _("Bio updated.  Close window to continue.") . "</div><br />";
  } else {
    $feedback = "<div class=\"feedback\">" . _("There was a problem.  Contact the admin.") . "</div><br />";
  }

/// Create our record again
  $record = new Staff($staff_id);
  $staff_name = $record->getFullName();
} else {
  $feedback = "";
}

print "$feedback
<div id=\"maincontent\">
<h2 class=\"bw_head\">" . _("Update Biography for ") . " $staff_name</h2>
<form id=\"bio_form\" action=\"\" method=\"post\">
<input type=\"hidden\" name=\"staff_id\" value=\"" . $_REQUEST["staff_id"] . "\" />
<div class=\"box no_overflow\">
<p>" . _("Please only include professional details.") . "</p><br />";

// Create our box now
$record->outputBioForm();

print "</div>
    <div class=\"box no_overflow\">
    <button class=\"button\" id=\"add_bio\" name=\"add_bio\">" . _("Update Bio") . "</button>
    </div>
    </form>
    </div>";

include("../includes/footer.php");
//$record->deBug();
?>