<?php
/**
 * @file control/edit-config.php
 * @brief change the config file
 * @description User Interface to change config file
 *
 * @author dgonzalez
 * @date Jan 2013
 * @todo
 */

use SubjectsPlus\Control\Config;
if ( file_exists( 'includes/version.php' ) ) {
	include_once 'includes/version.php';
}

//variables required in header and add header
$subcat = "admin";
$page_title = "Configuration Editing";
$use_jquery = array( "ui_styles" );

include( "includes/header.php" );


//new instance of config class
$lobjConfig = new Config();

//declare variable that stores configuration path
$lstrConfigFilePath = 'includes/config.php';



//set config file path
$lobjConfig->setConfigPath( $lstrConfigFilePath );

//echo out error message if the configuration file is not writable
if ( ! is_writable( $lstrConfigFilePath ) ) {
	?>
    <p><?php echo _( "Sorry, but I can't write to the <code>config.php</code> file. Please change permissions." ); ?></p>
	<?php

} else {
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
		"omit_user_columns" => array(
			_( "Columns To OMIT On User Form" ),
			_( "Tick the fields you would like to OMIT on the user form." ),
			"array",
			"user",
			"ticks",
			array(
				_( 'fname' ),
				_( 'lname' ),
				_( 'title' ),
				_( 'department' ),
				_( 'position_number' ),
				_( 'classification' ),
				_( 'priority' ),
				_( 'supervisor' ),
				_( 'tel' ),
				_( 'fax' ),
				_( 'intercom' ),
				_( 'room_number' ),
				_( 'user_type' ),
				_( 'personal_information' ),
				_( 'emergency_contact' )
			),
			_( "The 'User Form' controls who may access SubjectsPlus and what permissions they have.  Some fields are necessary for SubjectsPlus to work; these may be safely turned off if you don't want to capture or display this information." )
		),

		"require_user_columns" => array(
			_( "Columns To Require On User Form" ),
			_( "Tick the fields you would like to make required on the user form" ),
			"array",
			"user",
			"ticks",
			array(
				_( 'title' ),
				_( 'position_number' ),
				_( 'classification' ),
				_( 'priority' ),
				_( 'tel' ),
				_( 'fax' ),
				_( 'intercom' ),
				_( 'room_number' ),
				_( 'address' ),
				_( 'city' ),
				_( 'state' ),
				_( 'zip' ),
				_( 'home_phone' ),
				_( 'cell_phone' ),
				_( 'lat_long' ),
				_( 'emergency_contact' )
			),
			_( "These fields may be required, i.e., the form cannot be submitted until they are completed." )
		),

		"guide_container_width" => array(
			_( "Guide Container Width" ),
			_( "Assign a width in pixels for your guides. For example, 960px." ),
			"array",
			"guide",
			"small",
			"",
			_( "For example, 960px or 1160px." )
		),

		"google_analytics_ua" => array(
			_( 'Google Analytics UA Code' ),
			_( 'Add your Google Analytics UA Tracking code.' ),
			"string",
			"api",
			"medium",
			"",
			""
		),

		"google_tag_manager" => array(
			_( 'Google Tag Manager Code' ),
			_( 'Add your Google Analytics Tag Manager code.' ),
			"string",
			"api",
			"medium",
			"",
			""
		),

		"default_subject_specialist_heading" => array(
			_( "Default Subject Specialist Pluslet Heading" ),
			_( "A global heading for the Subject Specialist Pluslet" ),
			"string",
			"guide",
			"large",
			"",
			_( "For example Subject Librarians or Liaison Librarians." )
		),

		"headshot_thumb_width" => array(
			_( "Staff Headshot Thumbnail" ),
			_( "Width for staff headshot thumbnail. Note: If you change this value, it WILL affect previously uploaded headshot images." ),
			"string",
			"user",
			"small",
			"",
			_( "For example: 70, 100." )
		),

		"headshot_large_width" => array(
			_( "Staff Headshot Large" ),
			_( "Width for staff headshot large. Note: If you change this value, it WILL affect previously uploaded headshots." ),
			"string",
			"user",
			"small",
			"",
			_( "For example: 225, 300." )
		),

		"guide_types" => array(
			_( "Guide Types" ),
			_( "These are the different ways you may organize your guides." ),
			"array",
			"guide",
			"large",
			"",
			_( "You may add new types at any time.  If you change an existing type, you will need to update all items in subjects table by hand/SQL query." )
		),

		"use_shibboleth" => array(
			_( "Use Shibboleth" ),
			_( "This option controls whether Shibboleth will be used for logging in." ),
			"boolean",
			"auth",
			"small",
			"",
			_( "This checks the staff email with the server variables returned from Shibboleth." )
		),

		"shibboleth_logout" => array(
			_( "Shibboleth Logout URL" ),
			_( "This is the URL that logs the user out of a Shibboleth session." ),
			"string",
			"auth",
			"large",
			"",
			_( "This is a URL that logs the user out of a Shibboleth session." )
		),

		"all_ctags" => array(
			_( "Record Tags" ),
			_( "These are the tags that a given record location can have associated with it." ),
			"array",
			"record",
			"textarea",
			"",
			_( "Record tags are a way of slicing and dicing the total set of records.  If you add a new tag, you will need to add new code to deal with items with this tag.  Adding a tag by itself will do nothing except make that tag show up in some places." )
		),

		"all_vtags" => array(
			_( "Video Tags" ),
			_( "These are the tags that a given video can have associated with it. These are used for the videos module." ),
			"array",
			"video",
			"large",
			"",
			""
		),

		"all_tbtags" => array(
			_( "Talkback Site Tag" ),
			_( "These are the tags associated with TalkBack entries. The default is to email all talkbacks to administrator email. Edit only if you want to change that or add a branch." ),
			"aarray",
			"talkback",
			"large",
			"",
			_( "e.g. To make a main branch sending to admin email and branch1 branch sent to specified email -> 'main=,branch1=example@branch1.edu'" )
		),

		"all_cattags" => array(
			_( "Talkback Topic Tags" ),
			_( "These are the tags that a given talkback can have associated with it." ),
			"array",
			"talkback",
			"textarea",
			"",
			_( "Talkback tags are a way of slicing and dicing the total set of talkbacks.  If you add a new tag, you will need to add new code to deal with items with this tag.  Adding a tag by itself will do nothing except make that tag show up in some places." )
		),

		"talkback_recaptcha_site_key" => array(
			_( "Talkback Google Recaptcha Site Key" ),
			_( "This option contains the Google Recaptcha site key required to protect the Talkback form. Google Recaptcha https://www.google.com/recaptcha/intro/v3.html" ),
			"string",
			"talkback",
			"large",
			"",
			_("The site key goes on the client side form. Google Recaptcha https://www.google.com/recaptcha/intro/v3.html")
		),

		"talkback_recaptcha_secret_key" => array(
			_( "Talkback Google Recaptcha Secret Key" ),
			_( "This option contains the Google Recaptcha secret key required to protect the Talkback form." ),
			"string",
			"talkback",
			"large",
			"",
			_("The secret key goes in the server side function. Google Recaptcha https://www.google.com/recaptcha/intro/v3.html")
		),

		"talkback_slack_webhook_url" => array(
			_( "Slack Webhook URL for Talkback" ),
			_( "This option contains the webhook url from Slack that connects Talkback to Slack." ),
			"string",
			"talkback",
			"large",
			"",
			""
		),

		"talkback_slack_channel" => array(
			_( "Talkback Slack Channel" ),
			_( "This option contains Slack channel name that the Talkback form uses." ),
			"string",
			"talkback",
			"medium",
			"",
			""
		),

		"talkback_slack_emoji" => array(
			_( "Talkback Slack Emoji Code" ),
			_( "This option contains Slack emoji code that the Talkback form uses. It must begin and end with a colon. For example, :thought_balloon:" ),
			"string",
			"talkback",
			"medium",
			"",
			_( "It must begin and end with a colon. For example, :thought_balloon:" )
		),

		"titlebar_styles" => array(
			_( "Titlebar Styles" ),
			_( "These are the styles that can be issued to a pluslet titlebars." ),
			"aarray",
			"appearance",
			"large",
			"",
			_( "This allows titlebars to have custom styles with custom keys for subject guide creator to make." )
		),

		"pluslets_activated" => array(
			_( "Pluslet Activated" ),
			_( "Which Pluslets are activated." ),
			"array",
			"guide",
			"ticks",
			array(
				'Basic',
				'LinkList',
				'PrimoSearch',
				'Heading',
				'Card',
				'HTML5Video',
				'SubjectSpecialist',
				'BookList',
				_( '4' ),
				'Feed',
				'SocialMedia',
				'Chat',
				'QP',
				'WorldCat',
				'Catalog',
				'ArticlesPlus',
				'GoogleBooks',
				'GoogleScholar',
				'GoogleSearch',
				'Related',
				'TOC',
				_( '2' ),
				_( '1' ),
				'GuideSearch',
				'GuideSelect',
				'NewDBs',
				'NewGuides',
				'CollectionList',
				'GuideList',
				'Experts',
				'IIIWebPac',
				_( '3' ),
				_( '5' )
			),
			_( "" )
		),

		"use_disciplines" => array(
			_( "Use SerSol Provided Disciplines" ),
			_( "Include Serials Solutions disciplines integration." ),
			"boolean",
			"api",
			"small",
			"",
			_( "If you wish to include your SP guides in Serials Solutions' results--i.e., you have Summon--you need to use their disciplines." )
		),

		"api_enabled" => array(
			_( "Enable the API service (your api key is '" ) . "$api_key')",
			_( "If turned off, the API will return empty results" ),
			"boolean",
			"api",
			"small",
			"",
			_( "Go to " ) . "<a href=\"../api/\" target=\"_blank\">" . _( "this page" ) . "</a>" . _( " for an explanation of how the API works." )
		),

		"user_bio_update" => array( _( "Enable Users to Edit Bio" ), "", "boolean", "user", "small", "", "" ),

		"user_photo_update" => array(
			_( "Enable Users to Edit Headshot Photo" ),
			"",
			"boolean",
			"user",
			"small",
			"",
			""
		),

		"target_blank" => array(
			_( "Have database links show in new tab" ),
			_( "Affects display on databases.php and within subject guides" ),
			"boolean",
			"record",
			"small",
			"",
			""
		),

		"guide_headers" => array(
			_( "Header Switcher" ),
			_( "Have more than one header choice for a guide" ),
			"array",
			"appearance",
			"large",
			"",
			_( "Enter a comma separated list of headers.  The header name you put here will correspond to a header file on the server.  E.g., 'chc' would point to subjects/includes/header_chc.php" )
		),

		"subjects_theme" => array(
			_( "Use a theme" ),
			_( "Use a child theme to override the default theme.  Enter the directory name that you've created under subjects/themes/" ),
			"string",
			"appearance",
			"small",
			"",
			""
		),

		"css_override" => array(
			_( "Use your own CSS" ),
			_( "Not to be comfused with 'Use a Theme.'  This will point at your own CSS instead of the default." ),
			"string",
			"appearance",
			"small",
			"",
			""
		),

		"guide_index_page" => array(
			_( "Use a Guide as your index page" ),
			_( "If you want one of your guides to be the splash page at /subjects/ , enter the short form" ),
			"string",
			"appearance",
			"medium",
			"",
			""
		),

		"collection_thumbnail" => array(
			_( "Collection default image" ),
			_( "Set the image that shows by default on a collection page.  You must put this image in /assets/images/guide_thumbs/" ),
			"string",
			"appearance",
			"medium",
			"",
			""
		),

		"mod_rewrite" => array(
			_( "Use URL rewrites" ),
			_( "Make links prettier." ),
			"boolean",
			"core-tech",
			"small",
			"",
			""
		),

		"BaseURL" => array(
			_( "Base URL of your SubjectsPlus Installation" ),
			_( "e.g. 'http://www.yoursite.edu/library/sp/.' Make sure to include the trailing slash! <strong>If changed, you will need to log back in.</strong>" ),
			"string",
			"core-tech",
			"large",
			"",
			""
		),

		"resource_name" => array(
			_( "Name of this Resource" ),
			_( "e.g. SubjectsPlus, Research Guides" ),
			"string",
			"core-metadata",
			"medium",
			"",
			""
		),

		"institution_name" => array(
			_( "Institution Name" ),
			_( "Name of your college/university/institution" ),
			"string",
			"core-metadata",
			"medium",
			"",
			""
		),

        "institution_code" => array(
            _( "Institution Code" ),
            _( "Institution code to create customizations" ),
            "string",
            "core-metadata",
            "medium",
            "",
            ""
        ),

		"administrator" => array(
			_( "Name of Library Administrator" ),
			_( "Name for SubjectsPlus administrator" ),
			"string",
			"core-metadata",
			"medium",
			"",
			_( "This will appear in the footer of SP pages." )
		),

		"administrator_email" => array(
			_( "Library Administrator Email Address" ),
			_( "Email address for SubjectsPlus administrator" ),
			"string",
			"core-metadata",
			"medium",
			"",
			_( "This will appear in footer, and also be used as default email for TalkBack submissions." )
		),

		"email_key" => array(
			_( "Email Key" ),
			_( "Ending of campus email addresses, including @ sign" ),
			"string",
			"core-metadata",
			"medium",
			"",
			_( "This allows for simpler login." )
		),

		"tel_prefix" => array(
			_( "Telephone Prefix" ),
			_( "Prefix to prepend to telephone number for staff. Usually area code." ),
			"string",
			"core-metadata",
			"small",
			"",
			_( "Allows you to put short (non-prefixed) version of phone number on pages where space is limited." )
		),

		"hname" => array(
			_( "MySQL Hostname" ),
			_( "This is the ip or url to your MySQL database." ),
			"string",
			"mysql",
			"medium",
			"",
			_( "host help?" )
		),

		"db_port" => array(
			_( "MySQL Port" ),
			_( "This is the port your MySQL database uses." ),
			"string",
			"mysql",
			"medium",
			"",
			_( "" )
		),

		"uname" => array(
			_( "MySQL Username" ),
			_( "This is the username to your MySQL database." ),
			"string",
			"mysql",
			"medium",
			"",
			""
		),

		"pword" => array(
			_( "MySQL Password" ),
			_( "This is the password for your MySQL user." ),
			"pword",
			"mysql",
			"medium",
			"",
			""
		),

		"dbName_SPlus" => array(
			_( "MySQL SubjectsPlus Database" ),
			_( "Name of the SubjectsPlus database" ),
			"string",
			"mysql",
			"",
			_( "database help?" )
		),

		"db_cert_path" => array(
			_( "Optional Azure SSL Cert Path" ),
			_( "This is the path that is required when connecting to Azure Database for MySQL." ),
			"string",
			"mysql",
			"large",
			"",
			_( "" )
		),

		"upload_whitelist" => array(
			_( "List of accepted uploads' file extentions" ),
			_( "This option contains the coma-separated list of accepted file extensions for file uploads via CKEditor." ),
			"array",
			"core-tech",
			"large",
			"",
			_( "If a file isn't in this list, it should not be uploaded.  File uploads only occur via the admin backend, using CKEditor, but this is to stop a user from doing something, uh, foolish." )
		),

		"proxyURL" => array(
			_( "Proxy URL" ),
			_( "String which should be prepended if you use a proxy server" ),
			"string",
			"record",
			"large",
			"",
			_( "In the Records tab of SP, if you flag an item as 'restricted,' the proxy string will be prepended." )
		),

		"open_string" => array(
			_( "Open String" ),
			_( "Used to create a link to an item in your Catalog. Your subject search term is sandwiched between these two strings" ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"close_string" => array(
			_( "Close String" ),
			_( "If you don't need to close string, leave blank." ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"open_string_kw" => array(
			_( "Open String Keyword" ),
			_( "As above, for Keyword search." ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"close_string_kw" => array(
			_( "Close String Keyword" ),
			_( "If necessary." ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"open_string_cn" => array(
			_( "Open String Call Number" ),
			_( "As above, for Call Number search." ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"close_string_cn" => array(
			_( "Close String Call Number" ),
			_( "If necessary." ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"open_string_bib" => array(
			_( "Open String Bib" ),
			_( "Used to create a link to an item in your Catalog. Your Bib search term is sandwiched between these two strings" ),
			"string",
			"catalog",
			"medium",
			"",
			""
		),

		"CKBasePath" => array(
			_( "Base Path for CKEditor" ),
			_( "Path to CKEditor files appended to base URL" ),
			"string",
			"core-tech",
			"medium",
			"",
			_( "CKEditor is used to generate the WYSIWYG data entry boxes.  It is bundled with SubjectsPlus under the sp/ root folder.  If you move CKEditor to another location, you will need to change this path." )
		),

		"syndetics_client_code" => array(
			_( "Syndetics Client Code" ),
			_( "This option contains the client code required to use Syndetics" ),
			"string",
			"core-tech",
			"medium",
			"",
			""
		),

		"google_books_api_key" => array(
			_( "Google Books API Key" ),
			_( "This option contains the API key required to use Google Books API key in the Book List Pluslet" ),
			"string",
			"api",
			"large",
			"",
			""
		),

		"primo_action" => array(
			_( "Primo Search Form Action" ),
			_( "This option contains the Primo search form action for the Primo Pluslet. Includes '?' " ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"primo_institution_code" => array(
			_( "Primo Search Form Institution Code" ),
			_( "This option contains the Primo Institution Code for the Primo Pluslet" ),
			"string",
			"primo",
			"medium",
			"",
			""
		),

		"primo_vid" => array(
			_( "Primo Search Form View ID" ),
			_( "This option contains the Primo View ID for the Primo Pluslet" ),
			"string",
			"primo",
			"small",
			"",
			""
		),

		"catalog_primo_action" => array(
			_( "Primo Catalog Search Form Action" ),
			_( "This option contains the Primo search form action for the Primo Catalog Pluslet. Includes '?' " ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"catalog_primo_insitution_code" => array(
			_( "Primo Catalog Search Form Institution Code" ),
			_( "This option contains the Primo Institution Code for the Primo Catalog Pluslet" ),
			"string",
			"primo",
			"medium",
			"",
			""
		),

		"catalog_primo_vid" => array(
			_( "Primo Search Form View ID" ),
			_( "This option contains the Primo View ID for the Primo Pluslet" ),
			"string",
			"primo",
			"small",
			"",
			""
		),

		"articlesplus_primo_action" => array(
			_( "Primo ArticlesPlus Search Form Action" ),
			_( "This option contains the Primo search form action for the Primo ArticlesPlus Pluslet. Includes '?' " ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"articlesplus_primo_insitution_code" => array(
			_( "Primo ArticlesPlus Search Form Institution Code" ),
			_( "This option contains the Primo Institution Code for the Primo ArticlesPlus Pluslet" ),
			"string",
			"primo",
			"medium",
			"",
			""
		),

		"articlesplus_primo_vid" => array(
			_( "Primo ArticlesPlus Search Form View ID" ),
			_( "This option contains the Primo View ID for the Primo ArticlesPlus Pluslet" ),
			"string",
			"primo",
			"small",
			"",
			""
		),

		"booklist_primo_domain" => array(
			_( "Book List Pluslet Primo Domain" ),
			_( "This option contains the Primo domain for the Book List Pluslet" ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"booklist_primo_institution_code" => array(
			_( "Book List Pluslet Primo Institution Code" ),
			_( "This option contains the Primo institution code for the Book List Pluslet" ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"booklist_primo_vid" => array(
			_( "Book List Pluslet Primo View" ),
			_( "This option contains the Primo view for the Book List Pluslet" ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"booklist_primo_api_key" => array(
			_( "Book List Pluslet Primo API Key" ),
			_( "This option contains the Primo API Key for the Book List Pluslet" ),
			"string",
			"primo",
			"large",
			"",
			""
		),

		"stats_enabled" => array(
			_( "Enable Stats" ),
			_( "This option enables guides stats (experimental)" ),
			"boolean",
			"guide",
			"small",
			"",
			""
		)

	);

	//set config options
	$lobjConfig->setConfigOptions( $lobjConfigOptions );

	//if posted form
	if ( isset( $_POST['submit_edit_config'] ) ) {
		//get POST variables based on options array
		$lobjConfig->setNewConfigValues();

		//check if new values are acceptable
		$lstrMessage = $lobjConfig->checkDBConnection();

		if ( $lstrMessage != '' ) {
			//display error message on top of page
			$lobjConfig->displayMessage( _( $lstrMessage ) );

			//display edit HTML form with new values
			$lobjConfig->displayEditConfigForm( 'new' );

		} else {
			//if no error to connect to database, write to config file with new values
			$lobjReturn = $lobjConfig->writeConfigFile();

			//if error did not return
			if ( $lobjReturn ) {
				//if salt changed, log current person out and back in
				if ( $lobjConfig->getChangeSalt() ) {
					$_SESSION['checkit'] = md5( $_SESSION['email'] ) . $lobjReturn[1];
				}

				//display message
				$lobjConfig->displayMessage( _( "Thy will be done." ) );

				//if the base URL of SubjectsPlus changes, log them out and relocate to new BaseURL
				if ( $lobjConfig->isNewBaseURL() ) {
					// Unset all of the session variables.
					$_SESSION = array();

					// Finally, destroy the session.
					session_destroy();

					//echo out javascript to relocate user
					echo "<script type=\"text/javascript\">window.location = '{$lobjConfig->getNewBaseURL()}control/logout.php';</script>";
				}

				//if the mod_rewrite option changed
				if ( $lobjConfig->isNewModRewrite() ) {
					//write the approriate .htaccess file to given path
					$lobjConfig->wrtieModRewriteFile( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'subjects' . DIRECTORY_SEPARATOR . '.htaccess' );
				}

			} else {
				//error message
				$lobjConfig->displayMessage( _( "Something went wrong and could not save configurations." ) );
			}

			//display edit HTML form with new values
			$lobjConfig->displayEditConfigForm( 'new' );
		}
	} else if ( isset( $_POST['clear_cache_btn'] ) ) {
		//display message
		$lobjConfig->displayMessage( _( "Thy will be done." ) );
		$lobjConfig->displayEditConfigForm();

		$prefix = explode( 'control', dirname( __FILE__ ) );
		array_map( 'unlink', glob( "$prefix[0]/assets/cache/*.jpg" ) );
		array_map( 'unlink', glob( "$prefix[0]/assets/cache/*.bookmetadata" ) );
	} else {
		//no message and HTML form with original config values
		$lobjConfig->displayMessage( '' );
		$lobjConfig->displayEditConfigForm();
	}


}

//SubjectsPlus footer
include( "includes/footer.php" );

//extra css to style tooltip feature
//javascript for 'not right?' functionality, array ticks functionality, required
//fields check functionality, and hover tooltip functionality
?>
<style type="text/css">
    textarea {
        resize: none;
    }
</style>
<script type="text/javascript">

    //enable textbox two elements before 'this'
    function enableTextBox(lobjThis) {
        jQuery(lobjThis).parent().prev().prev().attr('disabled', false);
    }

    jQuery(document).ready(function ($) {

    	$( "#tabs" ).tabs();
    	
        ///////////////
        /* ctags     */
        ///////////////

        $("span[class*=ctag-]").livequery('click', function () {

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
            $(this).parent().find(".ctag-on[name=\"" + lstrName + '"]').each(function (i) {
                var this_ctag = $(this).text();
                all_tags = all_tags + this_ctag + ",";

            });
            // strip off final pipe (,)
            all_tags = all_tags.replace(/[,]$/, "");
            // set new value to hidden form field

            $('input[name="' + lstrName + '"]').val(all_tags);
        });

        ////////////////
        // Check Submit
        // When the form has been submitted, check required fields
        ////////////////

        $("#config_form").submit(function () {

            // If a required field is empty, set zonk to 1, and change the bg colour
            // of the offending field
            var alerter = 0;

            $("div.required_field").children('input').each(function () {
                // get contents of string, trim off whitespace
                var our_contents = $(this).val();
                var our_contents = jQuery.trim(our_contents);

                if (our_contents == '' && $(this).attr('name') != 'pword') {
                    $(this).attr("style", "background-color:#FFDFDF");
                    alerter = 1;
                } else {
                    $(this).attr("style", "background-color:none");
                }

                return alerter;

            });

            // Popup warning if required fields not complete
            if (alerter == 1) {
                alert("<?php print _( "You must complete all required form fields." ); ?>");
                return false;
            } else {
                $('input').attr('disabled', false);
            }
        });
    });
</script>
