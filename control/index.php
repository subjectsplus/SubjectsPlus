<?php
/**
 *   @file index.php
 *   @brief Splash page for admin (after successful login)
 *
 *   @author adarby
 *   @date May 2011
 */
$page_title = "SubjectsPlus";
$subcat = "home";

include("includes/header.php");

$full_name = $_SESSION["fname"] . " " . $_SESSION["lname"];

$recent_activity = seeRecentChanges($_SESSION["staff_id"]);

$user = new sp_Staff($_SESSION["staff_id"]);

$headshot = $user->getHeadshot($_SESSION["email"], "medium");
print "
<br />
<div style=\"clear: both;float: left; margin-right: 1em; width: 600px\">
    <div class=\"box no_overflow\">
            <div style=\"float: left; margin-right: 1em;\">
        <p>$headshot ";
printf(_("Hello %s"), $full_name);
print "</p>
    </div>
        <div style=\"float: left;\">
                <p><img src=\"$IconPath/required.png\" alt=\"bullet\" /> <a href=\"includes/set_password.php?staff_id=" . $_SESSION['staff_id'] . "\" id=\"reset_password\">" . _("Reset Password") . "</a></p>
";
if ($_SESSION['user_type_id'] == '1') {
  // allow user to update their own bio?
  if (isset($user_bio_update) && $user_bio_update == TRUE) {
      print "<p><img src=\"$IconPath/required.png\" alt=\"bullet\" /> <a href=\"includes/set_bio.php?staff_id=" . $_SESSION['staff_id'] . "\" class=\"showsmall\">" . _("Update Your Biographical Details") . "</a></p>";
  }
  // allow user to update their own photo?
  if (isset($user_photo_update) && $user_photo_update == TRUE) {
      print "<p><img src=\"$IconPath/required.png\" alt=\"bullet\" /> <a href=\"includes/set_picture.php?staff_id=" . $_SESSION['staff_id'] . "\" id=\"load_photo\">" . _("Update Headshot") . "</a></p>";

  }
}

// UM Only :  Now, export our contact information
if (isset($_SESSION["admin"]) || isset($_SESSION["supervisor"])) {
  print "<p><img src=\"$IconPath/required.png\" alt=\"bullet\" /> <a href=\"admin/contacts.php\" id=\"\">" . _("View/Export Staff Contact Info") . "</a></p>";
}

print "</div>
    </div>
    <h2>" . _("Recent Activity") . "</h2>
    <div class=\"box no_overflow\">
    <p>" . _("You have recently added or edited:") . "</p>
    $recent_activity
    </div>
</div>
<div style=\"float: left; min-width: 300px\">
    <h2>" . _("Background Options") . "</h2>
    <div class=\"box no_overflow\">
        <span id=\"bg_feedback\" class=\"feedback\"></span>";

foreach ($all_bgs as $value) {
  print "<p><img src=\"$IconPath/required.png\" alt=\"bullet\" /> <a id=\"css-$value\" href=\"\">" . ucfirst($value) . "</a></p>";
}
print "</div>
";

//first time pop up after installation
if( isset($_SESSION['firstInstall']) && $_SESSION['firstInstall'] == 1 )
{
	?>
	<a id="add" style="display:none;"></a>
	<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			$('#add').colorbox(
			{
				iframe: true,
				innerWidth:800,
				innerHeight:600,
				open: true,
				href: 'includes/firstTimeInstall.php'
			});
		});
</script>
	<?php
	unset($_SESSION['firstInstall']);
}
//first time pop up after update
if( isset($_SESSION['firstUpdate']) && $_SESSION['firstUpdate'] == 1 )
{
	?>
	<a id="add" style="display:none;"></a>
	<script type="text/javascript">
jQuery(document).ready(function($)
{
	$('#add').colorbox(
	{
		iframe: true,
		innerWidth:800,
		innerHeight:600,
		open: true,
		href: 'includes/firstTimeUpdate.php'
	});
});
</script>
	<?php
	unset($_SESSION['firstUpdate']);
}

include("includes/footer.php");
?>


<script type="text/javascript">

  var headshot_location = "<?php print $user->getHeadshotLoc(); ?>";

  $(document).ready(function(){


    $('a[id*=css-]').live('click', function(){

      var css_class = $(this).attr("id").split("-");
      var new_css = "<?php print $AssetPath; ?>css/" + css_class[1] + ".css";

      $("#css_choice" ).attr("href", new_css);
      $("#bg_feedback").load("includes/config_bits.php", {type: 'set_css', css_file: css_class[1]});
      return false;

    });

  });
</script>