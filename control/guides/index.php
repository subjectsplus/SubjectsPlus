<?php

/**
 *   @file index.php
 *   @brief Splash page for subject guide creation
 *
 *   @author adarby
 *   @date mar 2011
 */
$subcat = "guides";
$page_title = "Modify Guides in SubjectsPlus";

include("../includes/config.php");
include("../includes/header.php");

$gear_alt = "Edit Guide Metadata";
$eye_alt = "View Guide on Public Site";
$linkie_alt = "Check Guide Links";

try {
  $dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
  echo $e;
}

$subs_option_boxes = getSubBoxes("guide.php?subject_id=", "", 1);

$dropdown_intro_text = _("Please check with the guide's owner before modifying");

$all_guides = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" size=\"1\" onChange=\"window.location=this.options[selectedIndex].value\">
<option value=\"\">" . _("-- Choose Guide --") . "</option>
$subs_option_boxes
</select>
</form>";

// Get all subjects associated with the person

$my_subs_query = "SELECT subject.subject_id, subject, subject.active, shortform, type
FROM `subject`, staff_subject, staff
WHERE staff.staff_id = staff_subject.staff_id
AND staff_subject.subject_id = subject.subject_id
AND staff.staff_id = '$_SESSION[staff_id]'
ORDER BY subject";

$my_subs_result = MYSQL_QUERY($my_subs_query);
$num_rows = mysql_num_rows($my_subs_result);

if ($num_rows > 0) {

  $myguides = "";
  $row_count = 0;
  $colour1 = "#fff";
  $colour2 = "#F6E3E7";
  $colour3 = "highlight";

  while ($myrow1 = mysql_fetch_array($my_subs_result)) {
    $mysubs_id = $myrow1["0"];
    $mysubs_name = stripslashes($myrow1["1"]);
    $active = $myrow1["2"];

    $row_colour = ($row_count % 2) ? $colour1 : $colour2;


    $myguides .= "<div style=\"background-color:$row_colour ; padding: 2px;\" class=\"striper\"> &nbsp;&nbsp;
        <a class=\"showmedium-reloader\" href=\"../guides/metadata.php?subject_id=$mysubs_id&amp;wintype=pop\"><img src=\"$IconPath/emblem-system.png\"   alt=\"$gear_alt\" title=\"$gear_alt\" border=\"0\" /></a> &nbsp;&nbsp;
        <a target=\"_blank\" href=\"../../subjects/guide.php?subject=$myrow1[3]\"><img src=\"$IconPath/eye.png\" alt=\"$eye_alt\" border=\"0\" /></a> &nbsp;&nbsp;
        <a class=\"showmedium\" href=\"../guides/link_checker.php?subject_id=$mysubs_id&amp;wintype=pop\"><img src=\"$IconPath/linkcheck.png\" alt=\"$linkie_alt\" border=\"0\" /></a> &nbsp;&nbsp; <a href=\"guide.php?subject_id=$mysubs_id\">$mysubs_name</a>";
    if ($active != "1") {
      $myguides .= " <span style=\"color: #666;\">" . _("unpublished") . "</span>";
    }
    $myguides .= " <span style=\"color: #666; font-size: 10px;\">$myrow1[4]</span> </div>";
    $row_count++;
  }
} else {
  $myguides = "<p>" . _("You don't have any guides yet.  Why not create one?") . "</p>";
}

print "<br /><div class=\"box\">
<div class=\"edit-your-guides\"><h2>" . _("Edit Your Guides") . "</h2>

$myguides
</div>
</div>";

// Don't allow the NOFUN person to go to other guides
if (!isset($_SESSION["NOFUN"])) {
  print "
        <div class=\"box\">
    <div class=\"all-guides\"><h2 class=\"bw_head\">" . _("All Guides") . "</h2>

    <p>$dropdown_intro_text</p>
    <div class=\"all-guides-dropdown\" class=\"dropdown_list\">$all_guides</div>
    </div>
    </div>";
}
?>
  <div class="box">
<div class="create"><h2 class="bw_head">Create</h2>

    <ol>
      <li>Make sure the guide doesn't already exist!</li>
      <li><a href="metadata.php">Create new guide</a></li>
    </ol>
  </div>
</div>

  <div class="box">
<div class="tips"><h2 class="bw_head">Tips</h2>

<p> <img src="<?php echo $IconPath; ?>/emblem-system.png"  alt="<?php echo $gear_alt; ?>" title="<?php echo $gear_alt; ?>"/> = Edit Guide Metadata </p>
    <p><img src="<?php echo $IconPath; ?>/eye.png"  alt="<?php echo $eye_alt; ?>" title="<?php echo $eye_alt; ?>"/> = View Guide on Public Site</p>
    <p><img src="<?php echo $IconPath; ?>/linkcheck.png"  alt="<?php echo $linkie_alt; ?>" title="<?php echo $linkie_alt; ?>"/> = Check Guide Links</p>
    <p>Need to delete a guide?  Use the gear icon, and use the Delete button</p>
  </div>
</div>

<?php
include("../includes/footer.php");
?>
