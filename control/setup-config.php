<?php
/**
 *   @file control/setup-config.php
 *   @brief create the config file
 *   @description This file will help a user walk through basic options, and
 * 	 write the config file
 *
 *   @author dgonzalez
 *   @date Jan 2013
 *   @todo
 */

use SubjectsPlus\Control\Config;

include("includes/functions.php");
include("includes/autoloader.php");

//create an instance of the sp_config class
$lobjConfig = new Config();

//declare variable that stores configuration path
$lstrConfigFilePath = 'includes/config.php';
$lstrConfigBaseFilePath = 'includes/config-default.php';

//begin HTML document
$AssetURL = getAssetURL();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
       <style type="text/css" media="all">@import "<?php print $AssetURL; ?>css/admin/admin_styles.css";></style>
       <script type="text/javascript" src="<?php print $AssetURL ?>js/jquery.livequery.min.js"></script>
       <script type="text/javascript" src="<?php print $AssetURL ?>js/jquery.hoverIntent.js"></script>
       <script type="text/javascript" src="<?php print $AssetURL ?>js/shared.js"></script>
       <script type="text/javascript" src="<?php print $AssetURL ?>js/jquery.colorbox-min.js"></script>
       <title>Setup Configuration Page</title>
   </head>

   <body>
<?php

//error if no base file
if( !$lobjConfig->setConfigPath( $lstrConfigBaseFilePath ) )
{
	$lstrTitle = _( "Configuration File Error!" );
	$lstrMessage =  _( "Error! No Base file to use!" );

	$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage );
}

//error if Config already exists and is not a file placeholder
if( file_exists( $lstrConfigFilePath ) && filesize( $lstrConfigFilePath ) > 10 )
{
	$lstrTitle = _( "Configuration File Error!" );
	$lstrMessage =  _( "The file 'config.php' already exists. If you need to reset any of the configuration items in this file, you must be an admin and go to the Admin Nav and click 'Config Site'." );

	$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage );
}else
{
	//echo out error message if the configuration file exists and cannot be writable or the directory cannot be written to
	if( ( file_exists( $lstrConfigFilePath ) && !is_writable( $lstrConfigFilePath ) ) || !is_writable( dirname( $lstrConfigFilePath ) ) )
	{
		$lstrTitle = _( "Configuration File Error!" );
		$lstrMessage =  _( "Sorry, but I can't write to the <code>config.php</code> file. Please change permissions." );

		$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage );
	}else
	{
		//display steps page if requested by GET variable
		if( isset( $_GET['steps'] ) )
		{
			$lobjConfig->displaySteps();
		}else
		{
			//log only header
			displayLogoOnlyHeader();

			/* this is a declaration of an array that contains all the options in the
			 *  configuration that will be presented to the user in the HTML form to be
			 *  changed and saved. The way this array is declared to work with all the
			 *  functions that use it is as follows: [0] User form label [1] Notes to display
			 *  below input label [2] Type of declaration in config file [3] where to display
			 *  this input on HTML form (left or right box) [4] input specification (for string
			 *  type either small, medium, large or array type can be in form of ticks and boolean
			 *  always is a select box [5] extra data : e.g. holds options for ticks and will only be used if
			 *  array and ticks are specified or if additional data needed [6] tooltip that will display when
			 *  hovering over '?' icon and if blank, no icon will appear
			*/
			$lobjConfigOptions = array(
						"omit_user_columns" => array( _( "Columns To OMIT On User Form" ), _( "Tick the fields you would like to OMIT on the user form." ), "array", "right", "ticks", array( _( 'title' ),
						_( 'department' ), _( 'position_number' ), _( 'classification' ), _( 'priority' ), _( 'supervisor' ), _( 'tel' ), _( 'fax' ), _( 'intercom' ), _( 'room_number' ), _( 'user_type' ),
						_( 'personal_information' ), _( 'emergency_contact' ) ), _( "The 'User Form' controls who may access SubjectsPlus and what permissions they have.  Some fields are necessary for SubjectsPlus to work; these may be safely turned off if you don't want to capture or display this information." ) ),

						"require_user_columns" => array( _( "Columns To Require On User Form" ), _( "Tick the fields you would like to make required on the user form" ), "array", "right", "ticks",
						array( _( 'title' ), _( 'position_number' ), _( 'classification' ), _( 'priority' ), _( 'tel' ), _( 'fax' ), _( 'intercom' ), _( 'room_number' ), _( 'address' ), _( 'city' ),
						_( 'state' ), _( 'zip' ), _( 'home_phone' ), _( 'cell_phone' ), _( 'lat_long' ), _( 'emergency_contact' ) ), _( "These fields may be required, i.e., the form cannot be submitted until they are completed." ) ),

						"guide_types" => array( _( "Guide Types" ), _( "These are the different ways you may organize your guides." ), "array", "right", "large", "", _("You may add new types at any time.  If you change an existing type, you will need to update all items in subjects table by hand/SQL query.") ),

						"all_ctags" => array( _( "Record Tags" ), _( "These are the tags that a given record location can have associated with it." ), "array", "right", "textarea", "", _("Record tags are a way of slicing and dicing the total set of records.  If you add a new tag, you will need to add new code to deal with items with this tag.  Adding a tag by itself will do nothing except make that tag show up in some places.") ),

						"all_vtags" => array( _( "Video Tags" ), _( "These are the tags that a given video can have associated with it. These are used for the videos module." ), "array", "right", "large", "", "" ),

						"all_tbtags" => array( _( "Talkback" ), _( "These are the tags associated with TalkBack entries. The default is to email all talkbacks to administrator email. Edit only if you want to change that or add a branch." ),
						"aarray", "right", "large", "", _( "e.g. To make a main branch sending to admin email and branch1 branch sent to specified email -> 'main=,branch1=example@branch1.edu'" ) ),

						"use_disciplines" => array( _( "Use SerSol Provided Disciplines" ), _( "Include Serials Solutions disciplines integration." ), "boolean", "right", "small", "" ,
						_( "If you wish to include your SP guides in Serials Solutions' results--i.e., you have Summon--you need to use their disciplines." ) ),

						"BaseURL" => array( _( "Base URL of your SubjectsPlus Installation" ), _( "e.g. 'http://www.yoursite.edu/library/sp/.' Make sure to include the trailing slash! <strong>If changed, you will need to log back in.</strong>" ), "string", "left", "large", "" ,"" ),

						"resource_name" => array( _( "Name of this Resource" ), _( "e.g. SubjectsPlus, Research Guides" ), "string", "left-bottom", "medium", "" ,"" ),

						"institution_name" => array( _( "Institution Name" ), _( "Name of your college/university/institution" ), "string", "left-bottom", "medium", "" ,"" ),

						"administrator" => array( _( "Name of Library Administrator" ), _( "Name for SubjectsPlus administrator" ), "string", "left-bottom", "medium", "" , _("This will appear in the footer of SP pages.") ),

						"administrator_email" => array( _( "Library Administrator Email Address" ), _( "Email address for SubjectsPlus administrator" ), "string", "left-bottom", "medium", "" , _("This will appear in footer, and also be used as default email for TalkBack submissions.") ),

						"email_key" => array( _( "Email Key" ), _( "Ending of campus email addresses, including @ sign" ), "string", "left-bottom", "medium", "" ,_( "This allows for simpler login." ) ),

						"tel_prefix" => array( _( "Telephone Prefix" ), _( "Prefix to prepend to telephone number for staff. Usually area code." ), "string", "left-bottom", "small", "" , _( "Allows you to put short (non-prefixed) version of phone number on pages where space is limited." ) ),

						"hname" => array( _( "MySQL Hostname" ), _( "This is the ip or url to your MySQL database." ), "string", "left", "medium", "" , _( "host help?" ) ),

						"uname" => array( _( "MySQL Username" ), _( "This is the username to your MySQL database." ), "string", "left", "medium", "" ,"" ),

						"pword" => array( _( "MySQL Password" ), _( "This is the password for your MySQL user." ), "string", "left", "medium", "" ,"" ),


						"db_port" => array( _( "MySQL Port" ), _( "This is the port your MySQL server uses. (Default: 3306)." ), "string", "left", "medium", "" ,"" ),


						"dbName_SPlus" => array( _( "MySQL SubjectsPlus Database" ), _( "Name of the SubjectsPlus database" ), "string", "left", "" , _( "database help?" ) ),

						"upload_whitelist" => array( _( "List of accepted uploads' file extentions" ), _( "This option contains the coma-separated list of accepted file extensions for file uploads via CKEditor." ), "array", "right", "large", "" , _("If a file isn't in this list, it should not be uploaded.  File uploads only occur via the admin backend, using CKEditor, but this is to stop a user from doing something, uh, foolish.") ),

						"proxyURL" => array( _( "Proxy URL" ), _( "String which should be prepended if you use a proxy server" ), "string", "right", "large", "" , _( "In the Records tab of SP, if you flag an item as 'restricted,' the proxy string will be prepended." ) ),

						"CKBasePath" => array( _( "Base Path for CKEditor" ), _( "Path to CKEditor files appended to base URL" ), "string", "left", "medium", "" , _( "CKEditor is used to generate the WYSIWYG data entry boxes.  It is bundled with SubjectsPlus under the sp/ root folder.  If you move CKEditor to another location, you will need to change this path." ) )
						 );

			//set config options
			$lobjConfig->setConfigOptions( $lobjConfigOptions );

			//if posted form
			if( isset( $_POST['submit_setup_db_config'] ) )
			{
				//get POST variables based on options array
				$lobjConfig->setNewConfigValues();

				//check whether new values are acceptable
				$lstrMessage = $lobjConfig->checkDBConnection( );

				if( $lstrMessage != '' )
				{
					//display error message on top of page
					$lobjConfig->displayMessage( _( $lstrMessage ) );

					//display edit HTML form
					$lobjConfig->displaySetupDBConfigForm( 'new' );

				}else
				{
					//if no error to connect to database, set path to new config
					//file and do not set the values again and then write the config
					//file
					$lobjConfig->setConfigPath( $lstrConfigFilePath, FALSE );
					$lobjConfig->setChangeSalt( TRUE );
					$lobjConfig->setChangeAPIKey( TRUE );
					$lobjReturn = $lobjConfig->writeConfigFile();

					//if no error display complete. Otherwise, display error
					if( $lobjReturn )
					{
						$lobjConfig->displayMessage( _( "Configuration Setup Complete." ) );

						if( !isInstalled() )
						{
							?> <div class="install-pluslet">Please make sure you are not currently logged in on any installation of SubjectsPlus on current server.<br /><a href="install.php">Run the install!</a></div> <?php
						}
						else
						{
							?> <div class="install-pluslet"><a href="update.php">Update SubjectsPlus!</a></div> <?php
						}
					}else
					{
						//error message
						$lobjConfig->displayMessage( _( "Something went wrong and could not save configurations." ) );

						//display edit HTML form
						$lobjConfig->displaySetupDBConfigForm( 'new' );
					}
				}
			}else
			{
				//no message and HTML form with original config values
				$lobjConfig->displayMessage( '' );
				$lobjConfig->displaySetupDBConfigForm( );
			}
		}
	}
}

//extra css to style tooltip feature
//javascript for 'not right?' functionality, array ticks functionality, required
//fields check functionality, and hover tooltip functionality
?>
<style type="text/css">
textarea
{
	resize: none;
}

</style>
<script type="text/javascript">

	//enable textbox two elements before 'this'
function enableTextBox( lobjThis )
{
	jQuery( lobjThis ).parent().prev().prev().attr('disabled', false);
}

jQuery(document).ready(function($)
{
	///////////////
	/* ctags     */
	///////////////

	$("span[class*=ctag-]").livequery('click', function() {

		var all_tags = "";

		// change to other class
		if ($(this).attr("class") == "ctag-off") {
			$(this).attr("class", "ctag-on");
		} else {
			$(this).attr("class", "ctag-off");
		}

		//get name of c-tag which represents which tags to read and which input to change
		var lstrName = $(this).attr('name');

		// determine the new selected items
		$(this).parent().find(".ctag-on[name=\"" + lstrName + '"]').each(function(i) {
			var this_ctag = $(this).text();
			all_tags = all_tags + this_ctag + ",";

		});
		// strip off final pipe (,)
		all_tags = all_tags.replace( /[,]$/, "" );
		// set new value to hidden form field

		$('input[name="' + lstrName + '"]').val(all_tags);
	});

	////////////////
	// Check Submit
	// When the form has been submitted, check required fields
	////////////////

	$("#config_form").submit( function () {

		// If a required field is empty, set zonk to 1, and change the bg colour
		// of the offending field
		var alerter = 0;

		$("div.required_field").children('input').each(function() {
			// get contents of string, trim off whitespace

			if($(this).attr('type') == 'hidden') return;

			var our_contents = $(this).val();
			var our_contents  = jQuery.trim(our_contents );

			if (our_contents  == '' && $(this).attr( 'name' ) != 'pword') {
				$(this).attr("style", "background-color:#FFDFDF");
				alerter = 1;
			} else {
				$(this).attr("style", "background-color:none");
			}

			return alerter;

		});

		// Popup warning if required fields not complete
		if (alerter == 1) {
			alert("<?php print _("You must complete all required form fields."); ?>");
			return false;
		}else
		{
			$('input').attr('disabled', false);
		}
	});
});
</script>
</body>
</html>
