<?php
/**
 * @file index.php
 * @brief Splash page for admin (after successful login)
 *
 * @author adarby
 * @date May 2011
 */

use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\Stats;
use SubjectsPlus\Control\Querier;

$page_title = "SubjectsPlus";
$subcat     = "home";

include( __DIR__ . '/includes/header.php' );


$full_name = $_SESSION["fname"] . " " . $_SESSION["lname"];


$recent_activity = seeRecentChanges( $_SESSION["staff_id"] );



$user = new Staff( $_SESSION["staff_id"] );

$headshot = $user->getHeadshot( $_SESSION["email"], "medium" );

//////////////
//Permissions
//////////////
$mod_bio           = "";
$mod_photo         = "";
$view_contact_info = "";


if ( $_SESSION['user_type_id'] == '1' ) {
	// allow user to update their own bio?
	if ( isset( $user_bio_update ) && $user_bio_update == true ) {
		$mod_bio = "<p class=\"star-class\"><a href=\"includes/set_bio.php?staff_id=" . $_SESSION['staff_id'] . "\" class=\"showsmall\">" . _( "Update Your Biographical Details" ) . "</a></p>";
	}
	// allow user to update their own photo?
	if ( isset( $user_photo_update ) && $user_photo_update == true ) {
		$mod_photo = "<p class=\"star-class\"><a href=\"includes/set_picture.php?staff_id=" . $_SESSION['staff_id'] . "\" id=\"load_photo\">" . _( "Update Headshot" ) . "</a></p>";

	}
}

// UM Only :  Now, export our contact information
if ( isset( $_SESSION["admin"] ) || isset( $_SESSION["supervisor"] ) ) {
	$view_contact_info = "<p class=\"star-class\"><a href=\"admin/contacts.php\">" . _( "View/Export Staff Contact Info" ) . "</a></p>";
}

// Unncessary if someone is using shibboleth authentication

$reset_password = "<p class=\"star-class\"><a href=\"includes/set_password.php?staff_id=" . $_SESSION["staff_id"] . "\" id=\"reset_password\">" . _( "Reset Password" ) . "</a></p>";

if ( isset( $use_shibboleth ) ) {
	// $use_shibboleth var exists, but is it set to true?
	if ( $use_shibboleth == true ) {
		$reset_password = "";
	}
}
?>

<div class="pure-g">
    <div class="pure-u-1-3">
        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php printf( _( "Hello %s" ), $full_name ); ?></div>
                <div class="titlebar_options"></div>
            </div>
            <div class="topimage"></div>
            <div class="pluslet_body">

                <div class="pure-g" style="padding-top:0;">
                    <div class="pure-u-1 pure-u-lg-1-4 pure-u-xl-1-5 index_staff_photo">
						<?php print $headshot; ?>
                    </div>
                    <div class="pure-u-1 pure-u-lg-3-4 pure-u-xl-4-5">
						<?php
						print $reset_password;
						print $mod_bio;
						print $mod_photo;
						print $view_contact_info;
						?>
                    </div>
                </div>
            </div>
        </div>
		<?php
		$records_allowed = false;
		$records_li = "";
		$allguides_allowed = false;
		$all_guides_li = "";
		$faq_allowed = false;
		$faq_li = "";

		if ( $user->userCan( 'records' ) ) {
			$records_allowed = true;
			$records_li = "<li><a href=\"records/record.php\">" . _( "Create New Record" ) . "</a></li>";
		}

		if ( $user->userCan( 'allguides' ) ) {
			$allguides_allowed = true;
			$all_guides_li = "<li><a href=\"guides/metadata.php\">" . _( "Create New Guide" ) . "</a></li>";
		}

		if ( $user->userCan( 'faq' ) ) {
			$faq_allowed = true;
			$faq_li = "<li><a href=\"faq/faq.php\">" . _( "Create New FAQ" ) . "</a></li>";
		}

		$render_favorites = true;
		if ( ! $records_allowed && ! $allguides_allowed && ! $faq_allowed ) {
			$render_favorites = false;
		}

		if ( $render_favorites ){
		$our_faves = "<ul>".$records_li.$all_guides_li.$faq_li."</ul>";

		print makePluslet( _( "Favourites" ), $our_faves );  }?>


        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php print _( "Background Options" ); ?></div>
                <div class="titlebar_options"></div>
            </div>
            <div class="topimage"></div>
            <div class="pluslet_body">
                <span id="bg_feedback" class="feedback"></span>
                <ul>
					<?php
					foreach ( $all_bgs as $value ) {
						print "<li><a id=\"css-$value\" href=\"index.php\">" . ucfirst( $value ) . "</a></li>";
					}
					?>
                </ul>
            </div>
        </div>

    </div>

    <!-- Total External Link Clicks Per Guide -->
	<?php
	global $stats_enabled;

	if ( $stats_enabled ) {
		$db                = new Querier;
		$stats             = new Stats( $db );
		$staff_short_forms = $stats->getStaffShortForms( $_SESSION['staff_id'] );
	}
	?>

	<?php if ( $stats_enabled ): ?>
        <div class="pure-u-2-3" <?php if ( $staff_short_forms == null ) {
			echo "style=\"display:none;\"";
		} ?>>
            <div class="pluslet no_overlflow">
                <div class="titlebar">
                    <div class="titlebar_text">Your Guide Views Last Month</div>
                </div>
                <div class="pluslet_body">
                    <table class="stats-table">
                        <thead>
                        <tr>
                            <td>Guide</td>
                            <td>Number of Views</td>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ( $staff_short_forms as $staff_short_form ) { ?>
                            <tr>
                                <td>
                                    <a href="./guides/guide.php?subject_id=<?php echo $staff_short_form[0]; ?>"><?php echo $staff_short_form['subject']; ?></a>
                                </td>
                                <td>
									<?php $view_count = $stats->getShortFormCount( $staff_short_form['shortform'] );
									echo $view_count[0]['view_count'] ?>
                                </td>
                            </tr>
						<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	<?php endif; ?>
</div>


<div class="pure-u-1-3">
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print _( "Recent Activity" ); ?></div>
            <div class="titlebar_options"></div>
        </div>
        <div class="topimage"></div>
        <div class="pluslet_body">
            <p><?php print _( "You have recently added or edited:" ); ?></p>
			<?php echo $recent_activity ?>
        </div>
    </div>
</div>
</div>

<?php
//first time pop up after installation
if ( isset( $_SESSION['firstInstall'] ) && $_SESSION['firstInstall'] == 1 ) {
	?>
    <a id="add" style="display:none;"></a>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#add').colorbox(
                {
                    iframe: true,
                    innerWidth: 800,
                    innerHeight: 600,
                    open: true,
                    href: 'includes/firstTimeInstall.php'
                });
        });
    </script>
	<?php
	unset( $_SESSION['firstInstall'] );
}
//first time pop up after update
if ( isset( $_SESSION['firstUpdate'] ) && $_SESSION['firstUpdate'] == 1 ) {
	?>
    <a id="add"></a>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#add').colorbox(
                {
                    iframe: true,
                    innerWidth: 800,
                    innerHeight: 600,
                    open: true,
                    href: 'includes/firstTimeUpdate.php'
                });
        });
    </script>
	<?php
	unset( $_SESSION['firstUpdate'] );
}

include( "includes/footer.php" );
?>


<script type="text/javascript">

    var headshot_location = "<?php print $user->getHeadshotLoc(); ?>";

    $(document).ready(function () {

        $('a[id*=css-]').on('click', function () {

            var css_class = $(this).attr("id").split("-");
            var new_css = "<?php print $AssetPath; ?>css/theme/" + css_class[1] + ".css";

            $("#css_choice").attr("href", new_css);
            $("#bg_feedback").load("includes/config_bits.php", {type: 'set_css', css_file: css_class[1]});
            return false;

        });

    });
</script>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script>
    $(document).ready(function () {
        $('.stats-table').DataTable();
    });
</script>
