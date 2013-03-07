<?php

session_start();
/**
 *   @file login.php
 *   @brief Where you login--or not.
 *
 *   @author adarby, dgonzalez
 *   @date last update, fall 2012.  Forgot password functionality added.
 */

//expected postvars
$postvar_thecount = "";
$postvar_username = "";
$postvar_password = "";

if(isset($_POST['thecount']) && isset($_POST['username']) && isset($_POST['password']))
{
	$postvar_thecount = $_POST['thecount'];
	$postvar_username = $_POST['username'];
	$postvar_password = $_POST['password'];
}
$debugger = "no";

include("includes/functions.php");

//added in order to redirect to proper page if config file doesn't exist
if( !file_exists( "includes/config.php" ) || filesize( "includes/config.php" ) < 10 )
{
	$lstrURL = getControlURL();

	if( !file_exists( "includes/config-default.php" ) )
	{
		header("location:{$lstrURL}includes/configErrorPage.php?error=nobasefile");
		exit;
	}

	header("location:{$lstrURL}includes/configErrorPage.php?error=nofile");
	exit;
}

require_once("includes/config.php");

//added in order to redirect to proper page if we cannot connect to mySQL database
if( !isset($tryDB) || $tryDB != 'no')
{
	global $uname;
	global $pword;
	global $dbName_SPlus;
	global $hname;

	try {
		@$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
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

//added in order to redirect to control home if already logged in. Only check if $sessionCheck variable doesn't exists and says no
$sessionCheck = checkSession();

if ($sessionCheck != "failure" )
{
	global $CpanelPath;

	header("location:$CpanelPath");
	exit;
}

// If they have tried too many times, send them away
// Could add some sort of log of this failed attempt . . .

if ($postvar_thecount > 3) {
    header("location:$PublicPath");
}

$postvar_username = scrubData($postvar_username);
$postvar_password = scrubData($postvar_password);

// Start the counting after the first $_POST submission

if ($postvar_username == "") {
    $postvar_thecount = 1;
} else {
    $postvar_thecount++;
}

$success = "no";

$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("Please enter your <strong>login and password</strong> to proceed.") . "</p>";

$login_form = "<div align=\"center\">\n
<form action=\"login.php\" method=\"post\" style=\"font-size: 1em;\">\n
<input type=\"hidden\" name=\"thecount\" value=\"" . $postvar_thecount . "\" />
<table cellpadding=\"7\" cellspacing=\"0\" border=\"0\" class=\"striped_data\">\n
<tr>\n
<td valign=\"top\" class=\"odd\"><strong>" . _("Login") . "</strong></td>\n
<td valign=\"top\" class=\"odd\" align=\"left\"><input name=\"username\" type=\"text\" value=\"$postvar_username\" size=\"20\" /></td>\n
</tr>\n
<tr>\n
<td valign=\"top\" class=\"even\"><strong>" . _("Password") . "</strong></td>\n
<td valign=\"top\" class=\"even\" align=\"left\"><input name=\"password\" type=\"password\" value=\"$postvar_password\" size=\"20\" /></td>\n
</tr>\n
<tr>\n
<td valign=\"top\" class=\"odd\" colspan=\"2\"><div align=\"center\"><input type=\"submit\" value=\"login\" /></div></td>\n
</tr>\n
<tr>\n
<td valign=\"top\" class=\"even\" colspan=\"2\"><div align=\"right\"><a href=\"forgotpassword.php\">" . _("Forgot Password") ."</a></div></td>\n
</tr>\n
</table>\n
</form>\n
</div>\n";

if (($postvar_username != "") AND (isset($postvar_password))) {

    // just to try to be nice, try appending the defined (in config.php) campus ending (@yourcollege.edu)
    // if the login lacks one
    $pos = strpos($postvar_username, "@");
    if ($pos === false) {
        $postvar_username = $postvar_username . $email_key;
    }

// try this against the db
// Note that passwords are hashed in the database

    $emailAdd = $postvar_username;
    $password = md5($postvar_password);

    $checker = isCool($emailAdd, $password);

    if ($checker == "success") {
        if (isset($_SESSION["desired_page"])) {
            $loc = $_SESSION["desired_page"];
            header("location:$loc");
            exit();
        } else {
            header("location:index.php");
            exit();
        }
    } else {
        // Bad credentials, try again.  Only 3 tries before you get bumped
        $introtext = "<p align=\"center\"><strong>" . _("Please check your login and password and try again.  (Note that login attempts are logged.)") . "</strong></p><br />";
        $success = "no";
    }

    //print $checker;
    //print "<pre>";
    //print_r($_SESSION);
} else {

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css" media="all">@import "<?php print $AssetPath; ?>css/admin_styles.css";</style>
        <title>Login</title>
    </head>

    <body>
        <div id="header">
            <div style="width: 100%; text-align: left;"><img src="<?php print $AssetPath; ?>images/admin/logo_small.png"  border="0" class="logo" width="136" height="28" /></div>
        </div>
<?php
print "<div class=\"box\" style=\"width: 300px; margin: 2em auto;\">
$introtext
<br />
$login_form
<br />
</div>";

?>
    </body>
</html>

