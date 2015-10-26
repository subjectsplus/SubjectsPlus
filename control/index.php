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
    
include(__DIR__.'/includes/header.php');

$full_name = $_SESSION["fname"] . " " . $_SESSION["lname"];

$recent_activity = seeRecentChanges($_SESSION["staff_id"]);

$user = new Staff($_SESSION["staff_id"]);

$headshot = $user->getHeadshot($_SESSION["email"], "medium");

//////////////
//Permissions
//////////////
$mod_bio = "";
$mod_photo = "";
$view_contact_info = "";

if ($_SESSION['user_type_id'] == '1') {
  // allow user to update their own bio?
  if (isset($user_bio_update) && $user_bio_update == TRUE) {
    $mod_bio = "<p class=\"star-class\"><a href=\"includes/set_bio.php?staff_id=" . $_SESSION['staff_id'] . "\" class=\"showsmall\">Update Your Biographical Details</a></p>";
  }
  // allow user to update their own photo?
 if (isset($user_photo_update) && $user_photo_update == TRUE) {
    $mod_photo = "<p class=\"star-class\"><a href=\"includes/set_picture.php?staff_id=" . $_SESSION['staff_id'] . "\" id=\"load_photo\">Update Headshot</a></p>";

  }
}

// UM Only :  Now, export our contact information
if (isset($_SESSION["admin"]) || isset($_SESSION["supervisor"])) {
  $view_contact_info = "<p class=\"star-class\"><a href=\"admin/contacts.php\">" . _("View/Export Staff Contact Info") . "</a></p>";
}

?>
<div class="pure-g">
<div class="pure-u-1-3">  
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php printf(_("Hello %s"), $full_name); ?></div>
      <div class="titlebar_options"></div>
    </div>
    <div class="topimage"></div>
    <div class="pluslet_body">

        <div class="pure-g" style="padding-top:0;">
            <div class="pure-u-1 pure-u-lg-1-4 pure-u-xl-1-5 index_staff_photo">
                <?php print $headshot; ?>
            </div>
            <div class="pure-u-1 pure-u-lg-3-4 pure-u-xl-4-5">
                <p class="star-class"><a href="includes/set_password.php?staff_id=<?php print $_SESSION["staff_id"]; ?>" id="reset_password">Reset Password </a></p>
                <?php
                print $mod_bio;
                print $mod_photo;
                print $view_contact_info;
                ?>
            </div>
        </div>
         
    </div>
  </div>
  <?php 
  $our_faves = "<ul>
  <li><a href=\"records/record.php\">" . _("Create New Record") . "</a></li>
  <li><a href=\"guides/metadata.php\">" . _("Create New Guide") . "</a></li>
  <li><a href=\"faq/faq.php\">" . _("Create New FAQ") . "</a></li>
  </ul>";

  print makePluslet (_("Favourites"), $our_faves); ?>
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Background Options"); ?></div>
      <div class="titlebar_options"></div>
    </div>
    <div class="topimage"></div>
    <div class="pluslet_body">
      <span id="bg_feedback" class="feedback"></span>
      <ul>
      <?php
      foreach ($all_bgs as $value) {
        print "<li><a id=\"css-$value\" href=\"index.php\">" . ucfirst($value) . "</a></li>";
      }
      ?>
      </ul>
    </div>
  </div>

</div>

<div class="pure-u-1-3">  

</div>

<div class="pure-u-1-3">  
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Recent Activity"); ?></div>
      <div class="titlebar_options"></div>
    </div>
    <div class="topimage"></div>
    <div class="pluslet_body">
      <p><?php print _("You have recently added or edited:"); ?></p>
      <?php echo $recent_activity ?>
    </div>
  </div>
</div>
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
  	 var new_css = "<?php print $AssetPath; ?>css/theme/" + css_class[1] + ".css";

  	 $("#css_choice" ).attr("href", new_css);
  	 $("#bg_feedback").load("includes/config_bits.php", {type: 'set_css', css_file: css_class[1]});
  	 return false;

    });

  });
</script>