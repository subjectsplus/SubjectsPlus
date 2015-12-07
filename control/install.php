<?php
/**
 *   @file control/install.php
 *   @brief help installing SubjectsPlus
 *   @description This file will help a user walk through basic steps to setup
 * 	 SubjectsPlus
 *
 *   @author dgonzalez
 *   @date Jan 2013
 *   @todo
 */

use SubjectsPlus\Control\Config;
use SubjectsPlus\Control\Installer;
use SubjectsPlus\Control\Querier;

//set varirables needed in header
$subcat = "install";
$page_title = "Installation";
$sessionCheck = 'no';
$no_header = "yes";
$installCheck = 'no';
$updateCheck = 'no';

include("includes/header.php");

//logo only header
displayLogoOnlyHeader();

//find what step we are in
$lintStep = ( isset( $_GET['step'] ) ? (int) $_GET['step'] : 0 );

//if installed already, display message and discontinue
if( isInstalled() )
{
	?>
	<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
<div class="box required_field">
    <h2 class="bw_head"><?php echo _( "Already Installed" ); ?></h2>

			<p><?php echo _( 'There appears to already be an installation of SubjectsPlus. To reinstall please clear old database tables first.' ) ?></p>
			<p><a href="login.php"><?php echo _( 'Log In' ) ?></a></p>
		</div>
	</div>
	<?php
}else
{
	//options for configurations
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
	  
	  	"db_port" => array( _( "MySQL Port" ), _( "This is the port your MySQL server uses. (Default: 3306)" ), "string", "left", "medium", "" ,"" ),

		"dbName_SPlus" => array( _( "MySQL SubjectsPlus Database" ), _( "Name of the SubjectsPlus database" ), "string", "left", "" , _( "database help?" ) ),

		"upload_whitelist" => array( _( "List of accepted uploads' file extentions" ), _( "This option contains the coma-separated list of accepted file extensions for file uploads via CKEditor." ), "array", "right", "large", "" , _("If a file isn't in this list, it should not be uploaded.  File uploads only occur via the admin backend, using CKEditor, but this is to stop a user from doing something, uh, foolish.") ),

		"proxyURL" => array( _( "Proxy URL" ), _( "String which should be prepended if you use a proxy server" ), "string", "right", "large", "" , _( "In the Records tab of SP, if you flag an item as 'restricted,' the proxy string will be prepended." ) ),

		"CKBasePath" => array( _( "Base Path for CKEditor" ), _( "Path to CKEditor files appended to base URL" ), "string", "left", "medium", "" , _( "CKEditor is used to generate the WYSIWYG data entry boxes.  It is bundled with SubjectsPlus under the sp/ root folder.  If you move CKEditor to another location, you will need to change this path." ) )
		);

	//new instance of config amd set path and options
	$lobjConfig = new Config();
	$lobjConfig->setConfigPath('includes/config.php');
	$lobjConfig->setConfigOptions( $lobjConfigOptions );

	//depending on step, display content
	switch( $lintStep )
	{
		case 0:
			//first setup config with site configurations
			$lobjConfig->displaySetupSiteConfigForm();
			break;
		case 1:
			//on POST and second step, write configuration and install
			if( isset( $_POST['submit_setup_site_config'] ) )
			{
				$lobjConfig->setNewConfigValues();
				if( !$lobjConfig->writeConfigFile() )
				{
					//error message
					$lobjConfig->displayMessage( _( "Something went wrong and could not save configurations." ) );
				}else
				{
					//include again if config variables have changed
					include_once('includes/config.php');

		                        
					//new installer instance and install and on success show complete page
					$lobjInstaller = new Installer();
					if( $lobjInstaller->install( ) )
					{
		                              
		        $administrator_email = $_POST['administrator_email'];

		        $db = new Querier; 
		        $db->exec("UPDATE staff SET staff.email=". $db->quote($administrator_email) . " WHERE staff.staff_id = 1");

		        // create folder for this user (if it's not admin)
						$user_folder = explode("@",$administrator_email);

						if ($user_folder[0] != "admin") {
	      			$path = "../assets/users/_" . $user_folder[0];	

	           	if(!@mkdir($path)) {
		  					//  $mkdirErrorArray = error_get_last();
		   					// throw new Exception('cant create directory ' .$mkdirErrorArray['message'], 1);

		    				//print _("Couldn't create directory in /assets/users/. Please check this folder's permissions. ");
		     				return;

	    				}  else  {                                                                                                                    
					      // And copy over the generic headshot image and headshot_large image
					      $nufile = $path . "/headshot.jpg";
					      $copier = copy("../assets/images/headshot.jpg", $nufile);
					      $copier = copy("../assets/images/headshot.jpg", $path . "/headshot_large.jpg");

					      // message
					      //print _("Thy Will Be Done.  Added.");
	    				}  

						}
						

						$lobjInstaller->displayInstallationCompletePage();
						$_SESSION['firstInstall'] = 1;
					}
				}
			}
			break;
	}
}

include_once("includes/footer.php");
