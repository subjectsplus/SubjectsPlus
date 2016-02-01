<?php

/**
 *   @file index.php
 *   @brief Splash page for subject guide creation
 *
 *   @author adarby
 *   @date mar 2011
 */


use SubjectsPlus\Control\Querier;


$subcat = "guides";
$page_title = "Modify Guides in SubjectsPlus";

include("../includes/config.php");
include("../includes/header.php");

$db = new Querier;


$gear_alt = _("Edit Guide Metadata");
$eye_alt = _("View Guide on Public Site");
$linkie_alt = _("Check Guide Links");
$view_alt = _("View Guide on Public Site");
    
try {
  } catch (Exception $e) {
  echo $e;
}

$subs_option_boxes = getSubBoxes("guide.php?subject_id=", "", 1);

$dropdown_intro_text = _("Please check with the guide's owner before modifying");

$all_guides = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" id=\"guides\" size=\"1\" onChange=\"window.location=this.options[selectedIndex].value\">
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

$my_subs_result = $db->query($my_subs_query);
$num_rows = count($my_subs_result);

if ($num_rows > 0) {

  $myguides = "";
  $row_count = 0;
  $colour1 = "#fff";
  $colour2 = "#F6E3E7";
  $colour3 = "highlight";

  foreach ($my_subs_result as $myrow1) {
    $mysubs_id = $myrow1[0];
    $mysubs_name = stripslashes($myrow1[1]);
    $active = $myrow1[2];

    $row_colour = ($row_count % 2) ? $colour1 : $colour2;


    $myguides .= "<div style=\"background-color:$row_colour ; padding: 2px;\" class=\"striper\"> &nbsp;&nbsp;
        <a class=\"showmedium-reloader\" href=\"../guides/metadata.php?subject_id=$mysubs_id&amp;wintype=pop\"><i class=\"fa fa-gear fa-lg\" alt=\"$gear_alt\" title=\"$gear_alt\"></i></a> &nbsp;&nbsp;
        <a target=\"_blank\" href=\"../../subjects/guide.php?subject=$myrow1[3]\"><i class=\"fa fa-eye fa-lg\" alt=\"$view_alt\" title=\"$view_alt\"></i></a> &nbsp;&nbsp;
        <a class=\"showmedium\" href=\"../guides/link_checker.php?subject_id=$mysubs_id&amp;wintype=pop\"><i class=\"fa fa-link fa-lg\" alt=\"$linkie_alt\" title=\"$linkie_alt\"></i></a> &nbsp;&nbsp; <a href=\"guide.php?subject_id=$mysubs_id\">$mysubs_name</a>";
    if ($active != "1") {
      $myguides .= " <span style=\"color: #666;\">" . _("unpublished") . "</span>";
    }
    $myguides .= " <span style=\"color: #666; font-size: 10px;\">$myrow1[4]</span> </div>";
    $row_count++;
  }
} else {
  $myguides = "<p>" . _("You don't have any guides yet.  Why not create one?") . "</p>";
}
?>

<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>
<style>
.select2-container {
width: 65%;
margin-right: 3%;
}
</style>

<script>
$(document).ready(function() {

$('#guides').select2();

});
</script>

<div class="pure-g">
  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Edit Your Guides"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <p><?php print $myguides; ?></p>
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("All Guides"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <p><?php print $dropdown_intro_text; ?></p>
        <br />
        <div class="all-guides-dropdown dropdown_list"><?php print $all_guides; ?></div>
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">

    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Create"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <ol>
          <li><?php print _("Make sure the guide doesn't already exist!"); ?></li>
          <li><a href="metadata.php"><?php print _("Create new guide"); ?></a></li>
        </ol>
      </div>
    </div>

    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Import LibGuides"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
       <p>You can use this feature to import LibGuides</p>
       <a href="./lgimporter/lg_importer_user_selection.php"><?php print _("Import LibGuides"); ?></a>
        
      </div>
	  </div>

   <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Tips"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
        <p><i class="fa fa-gear fa-lg"  alt="<?php echo $gear_alt; ?>" title="<?php echo $gear_alt; ?>"></i> <?php print _("Edit Guide Metadata"); ?> </p>
        <p><i class="fa fa-eye fa-lg"  alt="<?php echo $eye_alt; ?>" title="<?php echo $eye_alt; ?>"></i> <?php print _("View Guide on Public Site"); ?></p>
        <p><i class="fa fa-link fa-lg"  alt="<?php echo $linkie_alt; ?>" title="<?php echo $linkie_alt; ?>"></i> <?php print _("Check Guide Links"); ?></p>
        <p><?php echo _("Need to delete a guide?  Use the gear icon, and use the Delete button."); ?></p>
      </div>
    </div>



 
  </div>
</div>



<?php
include("../includes/footer.php");
?>