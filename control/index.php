<?php
/**
 *   @file index.php
 *   @brief Splash page for admin (after successful login)
 *
 *   @author adarby
 *   @date May 2011
 */
use SubjectsPlus\Control\Staff;

$page_title = "SubjectsPlus";
$subcat = "home";
    
include(dirname(__DIR__).'/includes/header.php');

$full_name = $_SESSION["fname"] . " " . $_SESSION["lname"];

$recent_activity = seeRecentChanges($_SESSION["staff_id"]);

$user = new Staff($_SESSION["staff_id"]);

$headshot = $user->getHeadshot($_SESSION["email"], "medium");
?>

<div class="index-content">
  <div class="box no_overflow">
    <div class="greeting">
      <p>
	<?php
	echo $headshot;
	printf(_("Hello %s"), $full_name);
	?>

      </p>
    </div>
    <div class="control-options">
      <p><img src="<?php echo $IconPath; ?>/required.png"  class="bullet" alt="bullet"/></i> <a href="includes/set_password.php?staff_id="<?php echo $_SESSION['staff_id']; ?> id="reset_password">Reset Password </a></p>

      <?php
      if ($_SESSION['user_type_id'] == '1') {
	// allow user to update their own bio?
				       if (isset($user_bio_update) && $user_bio_update == TRUE) {
	  print "<p><img src=\"$IconPath/required.png\"  class=\"bullet\" alt=\"bullet\" /></i> <a href=\"includes/set_bio.php?staff_id=" . $_SESSION['staff_id'] . "\" class=\"showsmall\">Update Your Biographical Details</a></p>";
	}
	// allow user to update their own photo?
																				   if (isset($user_photo_update) && $user_photo_update == TRUE) {
	  print "<p><img src=\"$IconPath/required.png\"  class=\"bullet\" alt=\"bullet\" /></i> <a href=\"includes/set_picture.php?staff_id=" . $_SESSION['staff_id'] . "\" id=\"load_photo\">Update Headshot</a></p>";

	}
      }

      // UM Only :  Now, export our contact information
      if (isset($_SESSION["admin"]) || isset($_SESSION["supervisor"])) {
	print "<p><img src=\"$IconPath/required.png\"  class=\"bullet\" alt=\"bullet\" /></i> <a href=\"admin/contacts.php\">View/Export Staff Contact Info</a></p>";
      }
      ?>

    </div>

  </div>
  <div class="recent-activity">
 <div class="box no_overflow">
    <h2>Recent Activity</h2>

      <p>You have recently added or edited:</p>
      <?php echo $recent_activity ?>
    </div>
  </div>


  <div class="background-options">
  <div class="box no_overflow">
    <h2>Background Options</h2>


      <span id="bg_feedback" class="feedback"></span>
      <?php
      foreach ($all_bgs as $value) {
	print "<p><img src=\"$IconPath/required.png\" class=\"bullet\"  /></i><a id=\"css-$value\" href=\"\">" . ucfirst($value) . "</a></p>";
      }
      ?>

    </div>
    <?php
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
    <a id="add"></a>
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


       $('a[id*=css-]').on('click', function(){

	 var css_class = $(this).attr("id").split("-");
	 var new_css = "<?php print $AssetPath; ?>css/" + css_class[1] + ".css";

	 $("#css_choice" ).attr("href", new_css);
	 $("#bg_feedback").load("includes/config_bits.php", {type: 'set_css', css_file: css_class[1]});
	 return false;

       });

     });
    </script>