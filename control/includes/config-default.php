<?php
// Report all PHP errors--helpful for debugging; turn off in production
//ini_set('display_errors',1);
//error_reporting(E_ALL);

// In case this isn't set at server level.  Otherwise, PHP throws a warning.
// List of settings here:  http://www.php.net/manual/en/timezones.php
// date_default_timezone_set('America/New_York');

// These are the options which will show up when someone creates a new guide.
// If you change the name of one of your guide types after you've added content,
// you will need to update the type field of the subject table so that it matches
// the new naming.  Adding a new type shouldn't require any changes.
$guide_types = array( "Subject", "Course", "Topic");

// These are the tags that a given record location can have associated with it.  They
// will be used in a pipe-delimited list in the ctags field of the location table.
// In versions > 1.0.x, these will fill a drop down of format types
$all_ctags = array( "full_text", "openurl", "images", "video", "audio", "Mobile_Enabled", "Database_Trial", "New_Databases");

// These are used for the videos module.  Basically, list places you store videos
// If you add types, you'll have to modify the files to deal with these types
// as only Vimeo and YouTube API integration is in place
// vtags are quick and dirty way of creating collections; see $all_ctags etc. above
$video_storage_types = array("Vimeo", "YouTube", "Local");
$all_vtags = array( "collections", "instruction", "events", "exhibit", "services");

// These are the tags that denote permissions for a staff member.  They
// will be used in a pipe-delimited list in the ptags field of the staff table.
$all_ptags = array("talkback", "faq", "records", "eresource_mgr", "videos", "admin", "librarian", "supervisor", "view_map");

// These are the tags associated with TalkBack entries.  Used in a pipe-delimited
// list in the tbtags field of the talkback table. They can be
// used to sort our route talkback submissions.  The array key is the library name,
// the key is the email address to which notification should be sent.  No value = send to admin.
$all_tbtags = array( "main" => "");

// These are the tags associated with TalkBack entries.  Used in a pipe-delimited
// list in the cattags field of the talkback table. They can be
// used to tag talkbacks with categories.
$all_cattags = array( "Noise", "Resources", "Computers", "Facilities", "Services");

// To add a new background option (for the admin), create a new css file (e.g.
// sp/assets/css/green.css ) and then add "green" to this array.  Boom!
$all_bgs = array("basic", "flocking", "metamorphosis", "nasa");

// This array contains the list of columns that should be omitted in the user forms
// First Name, Last Name, Email Address, Ptags, and Password cannot be omitted
// Available options: 'title', 'department', 'position_number', 'classification', 'priority', 'supervisor', 'tel',
// 'fax', 'intercom', 'room_number', 'user_type', 'personal_information' (entire Personal Information section),
// 'emergency_contact' (entire Emergency Contact Information section)
$omit_user_columns = array( );

// This array contains the list of columns that should be required in the user forms
// First Name, Last Name, Email Address, and Password are always required!
// Available options: 'title', 'position_number', 'classification', 'priority', 'tel',
// 'fax', 'intercom', 'room_number', 'address', 'city', 'state', 'zip', 'home_phone', 'cell_phone',
// 'lat_long', 'emergency_contact' (all three emergency contact information)
$require_user_columns = array( "title", "classification", "tel", "address", "city", "state", "zip", "emergency_contact");

//used to declare which pluslets are activated in new box drop-down

$pluslets_activated = array("Basic", "Heading", "Card", "HTML5Video", "SubjectSpecialist", "4", "Feed", "SocialMedia", "WorldCat", "Catalog", "ArticlesPlus", "GoogleBooks", "GoogleScholar", "GoogleSearch",  "Related", "TOC", "2", "1", "GuideSearch", "GuideSelect", "NewDBs", "NewGuides", "CollectionList", "GuideList", "Experts");


// These are used to allow the admin to set different 'themes' for the pluslet titlebar
// which the guide author may choose from

$titlebar_styles = array( "Regular Heading" => "ts-umcream", "Alternative Heading" => "ts-umgreen");


//This sets the max width of the guide container
$guide_container_width = array( "1160px");

// SerSol provided disciplines; you may use for SerSol purposes, or add your own
// In order to activate disciplines (which could function as parents to subjects/guides/topics)
// You need to set this to TRUE
$use_disciplines = FALSE;

// allow users to update their own bios and/or photos via the control home page
// set to TRUE or FALSE
$user_bio_update = TRUE;
$user_photo_update = TRUE;

/* The base URL of your SubjectsPlus installation, e.g.,
   http://www.yoursite.edu/subsplus/
   or
   http://www.yoursite.edu/library/sp/
   Make sure to include the trailing slash!
*/
$BaseURL = "http://127.0.0.1/sp/";

/* Name of this resource, i.e., SubjectsPlus */
$resource_name = "SubjectsPlus";

/* Name of your college/university/institution */
$institution_name = "";

/* Name/email address for SubjectsPlus administrator */
$administrator = "Library Webmaster";
$administrator_email = "";

/* The email server you will use for mailing results in TalkBack
   e.g. mail.yoursite.edu or www.yoursite.edu
   The email function MIGHT still work without a value in here */
$email_server = "";

/* Ending of campus email addresses, including @ sign; used in
   staff listings page
   e.g., @yourcampus.edu */
$email_key = "@sp.edu";

/* For the staff page display.
   i.e., we store the last four digits, say, "1234", so we will
   prefix with "(123) 456-"; the short allows a shorter
   option for some circumstances, i.e., leave off the area code.*/
$tel_prefix = "";
$tel_prefix_short = "";

///Shibboleth
// Use Shibboleth for logging in
// (Assumes that you have a staff table with email addresses)
//
$use_shibboleth = FALSE;
$shibboleth_logout = "";
//////////////////////
// MySQL
//////////////////////

/* MySQL hostname.  Usually "localhost", but could be, say,
   mysql.yourhost.edu */
$hname = "127.0.0.1";

/* MySQL username and password */
$uname = "";
$pword = "";

$db_port = "3306";

/* Name of the SubjectsPlus database, i.e., subsplus.  May have a
   prefix on a shared host, i.e., yoursite_subsplus. */
$dbName_SPlus = "subjectsplus";

//////////////////////
// Open database links with a target=blank
$target_blank = TRUE;
//////////////////////

/////////////////////
// Use an override theme
// this should correspond to a folder under subjects/theme/
// and in this folder you put the override files
$subjects_theme = "";
/////////////////////

/////////////////////
// Use override CSS
// for light theming of site via CSS without renaming files
$css_override = "";
/////////////////////

//////////////////////
// Header switcher
// This allows SP to mimic the style of different sites,
// e.g., for a differently-branded branch library
//////////////////////

$guide_headers = array( 'default' );

//////////////////////
// Mmm . . . delicious
//////////////////////

// This is used to create a display page of del.icio.us feeds
// This is the username for your library's feeds; used by subjects/delish_feed.php
$DefaultDelishFolder = "";

//////////////////////
// OK types for file uploads
// Include any file types you want to allow admin users to upload through Guide tab
// i.e., via the CKeditor button
//////////////////////

$upload_whitelist = array( "jpg", "png", "gif", "jpeg", "doc", "docx", "xls", "txt", "pdf", "ppt", "pptx");

//////////////////////
// More Paths
// You shouldn't need to modify these
/////////////////////

/* $CpanelPath leads to the control folder
   i.e., http://www.yourlibrary.edu/subsplus/control/
   Make sure you include the trailing slash! */
$CpanelPath = $BaseURL . "control/";

/* $PublicPath leads to the subjects folder
   i.e., http://www.yourlibrary.edu/subsplus/subjects/
   Make sure you include the trailing slash! */
$PublicPath = $BaseURL . "subjects/";

/* $FAQPath leads to the public view of your FAQs.
   You don't have to modify this.  The FAQ module is optional. */
$FAQPath = $PublicPath . "faq.php";

/*  $TalkBackPath leads to the Talk Back or suggestion box page
   You don't have to modify this.  The TalkBack module is optional  */
$TalkBackPath = $PublicPath . "talkback.php";

/*  $VideoPath leads to the video page
   You don't have to modify this.  The Video module is optional */
$VideoPath = $PublicPath . "video.php";

/* $DBPath leads to the A-Z list of databases.
   You shouldn't have to modify this */
$DBPath = $PublicPath . "databases.php";

/* Path to the public delish page
   You shouldn't have to modify this */
$DelishPath = $PublicPath . "delish_feed.php";

/* Path to the asset folder
   You shouldn't have to modify this */
$AssetPath = $BaseURL . "assets/";

/* Path to the user folder
   You shouldn't have to modify this */
$UserPath = $AssetPath . "users";

/* Path to the Icon folder
   You shouldn't have to modify this */
$IconPath = $AssetPath . "images/icons";

/* Paths to the temp folder for uploaded files
   You shouldn't have to modify this */
$guides_temp_path = "uploads/";

/* Default image used on the collections page */
$collection_thumbnail = "books.jpg";
$collection_thumbnail_default = "$AssetPath/images/guide_thumbs/" . $collection_thumbnail;

//////////////////////////////////
// I18N support information here
// uncomment and change language
// for international support
// look for instructions on wiki
// http://www.subjectsplus.com/wiki/index.php?title=Internationalization_1.0
/////////////////////////////////



// Enter desired language here
//$language = 'en_EN';
// This SHOULD compute your path correctly; otherwise, try hard coding:
//example:  $LocalePath = "/home/myname/www/sp/assets/locale";
//$LocalePath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'locale';

//putenv("LC_ALL=$language");
//setlocale(LC_ALL, $language);
//$domain = 'messages';
//bindtextdomain($domain, $LocalePath);
//textdomain($domain);


/////////////////////
// Allows for "pretty" URLs
// using apache's mod_rewrite
// rewrites done via subjects/.htaccess
// set to TRUE or FALSE
////////////////////

$mod_rewrite = FALSE;

//////////////////////////
// Connections to Outside Resources
//////////////////////////

/* $open_string and $close_string are used to create a link to an
   * item in your Catalog.  Your subject search term is sandwiched
   * between these two strings.  E.g., in the Ithaca College Voyager
   * Catalog, it looks like this:
   *
   * $open_string = "http://icarus.ithaca.edu/cgi-bin/Pwebrecon.cgi?DB=local&Search_Arg=";
   * $close_string = "&BOOL1=all+of+these&Search_Code=SUBJ_&HIST=1&HIST=1&SUBMIT=Go%21&CNT=25";
   *
   * $open_string_kw = "http://icarus.ithaca.edu/cgi-bin/Pwebrecon.cgi?Search_Arg=";
   * $close_string_kw = "&Search_Code=GKEY^*&CNT=25&HIST=1";
   *
   * $open_string_cn = "http://icarus.ithaca.edu/cgi-bin/Pwebrecon.cgi?DB=local&SAB1=";
   * $close_string_cn = "&BOOL1=as+a+phrase&FLD1=Keyword+Anywhere+%28GKEY%29&GRP1=AND+with+next+set&SAB2=&BOOL2=all+of+these&FLD2=Keyword+Anywhere+%28GKEY%29&GRP2=AND+with+next+set&SAB3=&BOOL3=all+of+these&FLD3=Keyword+Anywhere+%28GKEY%29&SL=None&CNT=20";
   *
   * $open_string_bib = "http://icarus.ithaca.edu/cgi-bin/Pwebrecon.cgi?BBID=";
   *
   * If your catalog doesn't need a "close_string", leave it blank. */
$open_string = "";
$close_string = "";

// Keyword Search
$open_string_kw = "";
$close_string_kw = "";

// Call Number (DVDs and Reserves only)

$open_string_cn = "";
$close_string_cn = "";

// Bib

$open_string_bib = "";

/* String which should be prepended if you use a proxy server
   e.g. http://ezproxy.yourcampus.edu:2048/login?url=   */
$proxyURL = "";

/////////////////////////
// WYSIWYG Editing
// WYSIWYG editing capabilities for SP
// for more info, see www.ckeditor.com
/////////////////////////


/* CKeditor is bundled with SubjectsPlus.  With any luck,
   the next two variables will just work.  If not, try tweaking to
   match the paths in your own environment.

   If your path is wrong, but $ck_installed is set to 1, some pages will throw
   errors */

$CKBasePath = "/sp/ckeditor/";

$CKPath = $_SERVER["DOCUMENT_ROOT"] . $CKBasePath . "ckeditor.php"; // used to call the editor

/* Set $wysiwyg_desc to "1" if you want a WYSIWYG description field in
   SP::Records.  Requires that above two CK path variables are set correctly. */
$wysiwyg_desc = 1;

/* Used with the authentication script. Auto generated by installer/updater */

$salt = "a5h&vv5$#45";

// Configuration of api usage. Whether to turn the api service on or off, and
// the security key for api use

$api_enabled = FALSE;
$api_key = "a5h&vv5$#45";

define("PATH_TO_SP", $BaseURL);

// Do you want to point to some other guide as the splash page?
// If yes, enter the short form
$guide_index_page = "";

?>
