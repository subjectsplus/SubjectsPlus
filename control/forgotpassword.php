<?php

error_reporting(1);

require_once("includes/config.php");
require_once("includes/functions.php");

try {
	$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
	echo $e;
}

$lstrForm = '';

if(!isset($_GET['id']))
{
	if(isset($_POST['email']))
	{
		$lobjStaff = new sp_Staff('','forgot');

		if($lobjStaff->getRecordId() == NULL)
		{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("{$lobjStaff->getMessage()}") . "</p>";

			$lstrForm = $lobjStaff->outputEmailForm();
		}else{
			$lstrCode = md5($lobjStaff->getEmail() . $salt);
			$lstrMessage = "Hello {$lobjStaff->getFullName()},\n\nHere is the link to reset your password. {$BaseURL}control/forgotpassword.php?id={$lobjStaff->getRecordID()}&code={$lstrCode}";

			mail($lobjStaff->getEmail(), 'Reset password for SubjectsPlus', $lstrMessage, "From: $administrator_email");

			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br /><strong>" . _("An email has been sent to reset your password.  Please click the link in the email and follow the instructions.") . "</strong></p>";
		}
	}else{
		$lobjStaff = new sp_Staff();

		$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("Please enter your <strong>email</strong> so we can email you a link to reset your password.") . "</p>";

		$lstrForm = $lobjStaff->outputEmailForm();
	}
}else{
	$_SESSION['staff_id'] = $_GET['id'];
	$lobjStaff = new sp_Staff($_GET['id']);

	if(isset($_POST['password']))
	{
		if($lobjStaff->correctPassword($_POST['password']))
		{
			if($_POST['password'] == $_POST['password_confirm'])
			{
				$lobjStaff->updatePassword(trim($_POST['password']));

				$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("Password has been updated.") . "</p>";
				$introtext .= '<br><p align="center"><a href="login.php">Login</a></p>';
			}else{
				$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br /><span style=\"background-color:yellow;\">" . _("Passwords did not match.") . "</span><br />" ._("Please enter your new password.")
					. "<br /><strong>" . _("Password must have at least one letter, one number, and one special character.") . "</strong></p>";

				$lstrForm = $lobjStaff->outputResetPasswordForm();
			}
		}else{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br /><span style=\"background-color:yellow;\">" . _("Password doesn't meet requirements.") . "</span><br />" ._("Please enter your new password.")
					. "<br /><strong>" . _("Password must have at least one letter, one number, and one special character.") . "</strong></p>";

			$lstrForm = $lobjStaff->outputResetPasswordForm();
		}

	}else{

		if($lobjStaff->getEmail() == NULL || md5($lobjStaff->getEmail() . $salt) != $_GET['code'])
		{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("The id and email do not match or the id does not exits.") . "</p>";
		}else{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("Please enter your new password.")
					. "<br /><strong>" . _("Password must have at least one letter, one number, and one special character.") . "</strong></p>";

			$lstrForm = $lobjStaff->outputResetPasswordForm();
		}
	}
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
$lstrForm
<br />
<!--<span class=\"smaller\">Darn it, I forgot my password!</span>-->
</div>";

?>
    </body>
</html>