<?php
use SubjectsPlus\Control\Staff;

error_reporting(1);

require_once("includes/config.php");
require_once("includes/functions.php");
require_once("includes/autoloader.php");

try {
	$dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
	echo $e;
}

$lstrForm = '';

if(!isset($_GET['id']))
{
	if(isset($_POST['email']))
	{
		$lobjStaff = new Staff('','forgot');

		if($lobjStaff->getRecordId() == NULL)
		{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("{$lobjStaff->getMessage()}") . "</p>";

			$lstrForm = $lobjStaff->outputEmailForm();
		}else{
			$lobjTodayDate = new DateTime();

			//The code is a hased string composed of the user's email, installation's salt, and today's date MMDDYYYY
			$lstrCode = md5($lobjStaff->getEmail() . $salt . $lobjTodayDate->format('mdY'));
			$lstrMessage = "Hello {$lobjStaff->getFullName()},\n\nHere is the link to reset your password. Link only works for three days. {$BaseURL}control/forgotpassword.php?id={$lobjStaff->getRecordID()}&code={$lstrCode}";

			mail($lobjStaff->getEmail(), 'Reset password for SubjectsPlus', $lstrMessage, "From: $administrator_email");

			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br /><strong>" . _("An email has been sent to reset your password.  Please click the link in the email and follow the instructions.") . "</strong></p>";
		}
	}else{
		$lobjStaff = new Staff();

		$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("Please enter your <strong>email</strong> so we can email you a link to reset your password.") . "</p>";

		$lstrForm = $lobjStaff->outputEmailForm();
	}
}else{
	$_SESSION['staff_id'] = $_GET['id'];
	$lobjStaff = new Staff($_GET['id']);

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
					. "<br /><strong>" . _("Password must have at least one letter, one number, one special character, and be at least 6 characters long.") . "</strong></p>";

				$lstrForm = $lobjStaff->outputResetPasswordForm();
			}
		}else{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br /><span style=\"background-color:yellow;\">" . _("Password doesn't meet requirements.") . "</span><br />" ._("Please enter your new password.")
					. "<br /><strong>" . _("Password must have at least one letter, one number, one special character, and be at least 6 characters long.") . "</strong></p>";

			$lstrForm = $lobjStaff->outputResetPasswordForm();
		}

	}else{
		//create a DateTime object that defaults to today's date
		$lobjTodayDate = new DateTime();
		//clone Today's Date object because without clone, the object will pass by reference
		$lobjTodayMinusOne = clone $lobjTodayDate;
		//subtract a day from the Date Time object
		$lobjTodayMinusOne->sub(new DateInterval('P1D'));
		//clone Today's Date object again
		$lobjTodayMinusTwo = clone $lobjTodayDate;
		//subtract 2 days from the Date Time object
		$lobjTodayMinusTwo->sub(new DateInterval('P2D'));

		//display error if Staff object's email object is null or if the passed code does not equal the hashed code containing
		//the passed user id for today or the passed 2 days
		if($lobjStaff->getEmail() == NULL || (md5($lobjStaff->getEmail() . $salt . $lobjTodayDate->format('mdY')) != $_GET['code']
			&& md5($lobjStaff->getEmail() . $salt . $lobjTodayMinusOne->format('mdY')) != $_GET['code'] && md5($lobjStaff->getEmail() . $salt . $lobjTodayMinusTwo->format('mdY')) != $_GET['code']))
		{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("The id and email do not match or the id does not exits or the link has expired.") . "</p>";
		}else{
			$introtext = "<p align=\"center\" style=\"clear: both;\" class=\"smaller\"><br />" . _("Please enter your new password.")
					. "<br /><strong>" . _("Password must have at least one letter, one number, one special character, and be at least 6 characters long.") . "</strong></p>";

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