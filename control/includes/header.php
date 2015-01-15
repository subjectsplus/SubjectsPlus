<?php
/**
 *   @file header.php
 *   @brief Include file, called by virtually everything.
 *   @description includes config and functions files, language file if not English; checks authorization
 *   to view calling page; builds header portion of page
 *
 *   @author adarby
 *   @date mar 2011
 */

require_once("autoloader.php");

require_once("functions.php");

use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\BuildNav;
use SubjectsPlus\Control\Querier;

//added in order to redirect to proper page if config file doesn't exist or if only placeholder
if( !file_exists( dirname(__FILE__) . '/config.php' ) || filesize( dirname(__FILE__) . '/config.php' ) < 10 )
{
	$lstrURL = getControlURL();

	if( !file_exists( dirname(__FILE__) . '/config-default.php' ) )
	{
		header("location:{$lstrURL}includes/configErrorPage.php?error=nobasefile");
		exit;
	}

	header("location:{$lstrURL}includes/configErrorPage.php?error=nofile");
	exit;
}

require_once(dirname(__FILE__) . "/config.php");

$db = new Querier;

// start our session
session_start();

//print_r($_SESSION);
//added in order to redirect to proper page if cannot connect to database. Only check if $tryDB variable doesn't exists and says no

    /*
if( !isset($tryDB) || $tryDB != 'no')
{
	try {
		@$dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
	} catch (Exception $e) {
		$lstrURL = getControlURL();

		if ( strstr( $e->getMessage() , 'Could not choose database.' ) )
		{
			header("location:{$lstrURL}includes/configErrorPage.php?error=database");
		}else
		{
			header("location:{$lstrURL}includes/configErrorPage.php?error=connection");
		}
		exit();
	}
}
*/
//added in order to redirect to proper page if SubjectsPlus is not installed. Only check if $installCheck variable doesn't exists and says no
if( !isset( $installCheck ) || $installCheck != 'no' )
{
	$isInstalled = isInstalled();

	if( !$isInstalled )
	{
		$lstrURL = getControlURL();

		header("location:{$lstrURL}install.php");
		exit;
	}
}

//added in order to redirect to proper page if SubjectsPlus is not updated to 2.0. Only check if $updateCheck variable doesn't exists and says no
if( !isset( $updateCheck ) || $updateCheck != 'no' )
{
	$isUpdated = isUpdated();

	if( !$isUpdated )
	{
		$lstrURL = getControlURL();

		header("location:{$lstrURL}update.php");
		exit;
	}
}

//added in order to redirect to proper page if user has not logged in. Only check if $sessionCheck variable doesn't exists and says no
if( !isset($sessionCheck) || $sessionCheck != 'no' )
{
	$sessionCheck = checkSession();

	if ($sessionCheck == "failure" ) {

		// set a cookie for the page they wanted to visit
		session_regenerate_id();
        if(!isset($_SESSION))
        {
		session_start();
        }
		$_SESSION['desired_page'] = $_SERVER["REQUEST_URI"];
		// Send to login page for pword
		$login_page = $CpanelPath . "login.php";
		header("location:$login_page");
		exit();
	}

	// Now, check if they have access to the records or admin modules.
	if( !isset($subcat) ) $subcat = '';

	if ($subcat == "admin" && $_SESSION["admin"] != 1) {
		header("location:$CpanelPath");
	}

	if ($subcat == "guides" && $_SESSION["records"] != 1) {
		header("location:$CpanelPath");
	}

	if ($subcat == "records" && $_SESSION["records"] != 1) {
		header("location:$CpanelPath");
	}

	if ($subcat == "records" && isset($_SESSION["NOFUN"])) {
		header("location:$CpanelPath");
	}

	if (isset($header) && $header == "noshow") {
		// used when we want authentication, but don't need to show the header stuff
		return;
		exit;
	}
}

// a translation or two. . .
$required_text = _("Required");
$rusure = _("Are you sure?");
$textyes = _("Yes");
$textno = _("No");

header("Content-Type: text/html; charset=utf-8");

// You may need to comment out the next two lines if you don't have mbstring enabled in PHP
mb_language('uni');
mb_internal_encoding('UTF-8');
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php print $page_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="<?php echo getControlURL(); ?>includes/css.php" type="text/css" media="all" />
    <?php
    // this is for custom css, set by user and stored in database


    if (isset($_SESSION['css'])) {
      print "<link id=\"css_choice\" href=\"$AssetPath" . "css/theme/" . $_SESSION['css'] . ".css\" rel=\"stylesheet\" type=\"text/css\"></link>";
    }



    ?>

<script src="<?php echo getControlURL(); ?>includes/js.php" type="text/javascript"></script>
  </head>
  <body id="controlpage">

    <?php
    // for those times when you  need the CSS and jQuery, but no header . . .
    if (isset($no_header) && $no_header == "yes") {
      return;
    }
    ?>

<header id="header">
    <div class="pure-g">
        <div class="pure-u-1">
    		<div class="wrapper-full">

        <?php
        // Our Nav is built here:
        $b_box = new BuildNav();
        $b_box->displayNav();
        ?>

    		</div> <!-- end nav wrapper -->
		</div>
	</div>
</header>
      <?php
      // This is used in control/records to link to the public site -- you probably don't need to change
      if (isset($_COOKIE["sub_shortform"])) {
        if ($mod_rewrite == 1) {
          $path_to_subject = $PublicPath . $_COOKIE["sub_shortform"];
        } else {
          $path_to_subject = $PublicPath . "guide.php?subject=" . $_COOKIE["sub_shortform"];
        }
      }

      // This is for the guides page, where everything isn't wrapped in a maincontent div
      if (isset($tertiary_nav) && $tertiary_nav == "yes") {

      } else {
       print "<div id=\"maincontent\">";


      }
