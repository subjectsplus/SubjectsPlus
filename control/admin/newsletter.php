<?php
$css = "<style type=\"text/css\" media=\"all\">

body {
	font-family: "Helvetica Neue", "Helvetica", Verdana, sans-serif;
	font-size: .9em;
	line-height: 1.5em;
	color: #000;
	background-color: #F8F5F5;
	margin: 0px 20px;
}

h1 {
	font-size: 1.5em;
}

h2 {
	font-size: 1.2em;
}

a:link {
	color: #4977A4;
}

a:visited {
	color: #4977A4;
}
a:hover {
	color: #4977A4;
	text-decoration: none;
}
#wrap {

	min-width: 900px;
	min-width: 550px;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
	}

html>body #wrap { min-width: 900px; }

#header {
	float: left;
	position: relative;
	background-color: #232323;
	width: 100%;
	height: 60px;
}

#logo {
	float: left;
	position: relative;
}

#title_text {
	float: left;
	position: relative;
	color: #DB5E7D;
	margin-left: 100px;
	margin-top: .2em;
}

.logo {
	margin-top: 10px;
	margin-left: 40px;
	margin-bottom: 25px;
}

#newsletter {
	clear: both;

}

#inshort {
background-color: #e0e0e0;
padding: 2px;
text-align: center;
}

#main-col {
	position: relative;
	float: left;
	margin-top: 1em;
	width: 62%;
	margin-right: 4%;
}

#sidebar {
	position: relative;
	float: left;
	margin-top: 1em;
	width: 30%;
}

.item {
clear: both; padding: 3px 5px;
}

.oddrow {
background-color: #f6e3e7;
}

.evenrow {
background-color: #F8F5F5;
}

#footer {clear:both; font-size: 10px; margin-top: 5px;}

</style>";



/**
 *   @file newsletter-cron.php
 *   @brief provide a summary of SubsPlus activity
 *	 @description Note that you need to add some paths and MySQL credentials "by hand" here
 *
 *   @author adarby
 *   @date Aprtil 2011
 */

// set some paths -- You can copy these from control/includes/config.php

$BaseURL = "";
$CpanelPath = $BaseURL . "control/";
$PublicPath = $BaseURL . "subjects/";
$AssetPath = $BaseURL . "assets/";

$FAQPath = "faq.php";
$TalkBackPath = "talkback.php";

// Enter your email
$administrator_email = "";

// Make sure our server time is correct
putenv("TZ=US/Eastern");

// Get today's date; used in queries below
$today = date("Y-m-d"); // you shouldn't need to change this

//////////////
// DB Credentials
/////////////

$dbName = "sp";
$hname = "localhost";
$uname = "";
$pword = "";

//////////////
// End Editable Portion
// You shouldn't need to change anything below here
//////////////

//////////// Database Call /////////////

function dbCall($dbName){

global $hname;
global $uname;
global $pword;

// make connection to database
MYSQL_CONNECT($hname, $uname, $pword) OR DIE("Unable to connect to database");

@mysql_select_db( "$dbName") or die( "Unable to select database");
}

//////////////////////////////////

dbCall($dbName);

// Get the people who will receive the email

$q = "SELECT email FROM staff WHERE ptags LIKE '%newsletter%'";

$r = MYSQL_QUERY($q);

while($myrow =  mysql_fetch_array($r)) {

	$recipients .= $myrow[0] . ",";
}

$recipients = rtrim($recipients, ",");

//////////////////////////
// Query chchchanges
/////////////////////////

$sq2 = "SELECT ourtable, record_id, record_title, message, date_added, fname, lname FROM chchchanges, staff WHERE chchchanges.staff_id = staff.staff_id AND date_added >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) GROUP BY record_title, message, ourtable ORDER BY record_title ASC, message ASC";

// print $sq2;

$sr2 = MYSQL_QUERY($sq2);

$row_count = 0;
$colour1 = "oddrow";
$colour2 = "evenrow";

$new_record_count = 0;
$update_record_count = 0;
$new_guide_count = 0;
$deleted_guide_count = 0;
$updated_guide_count = 0;
$faq_count = 0;
$talkback_count = 0;

$updated_guider = array();
$updated_recorder = array();

while($myrow2 =  mysql_fetch_array($sr2)) {

$message = $myrow2["3"];

	switch ($myrow2["0"]) {
		case "guide";
		case "subject":
			if ($message == "insert" || $message == "update") {
				$research_guide[] = array("$myrow2[1]", "$message", "$myrow2[2]", "$myrow2[5] $myrow2[6]");
			}

		break;
		case "faq":
			$linkit = $FAQPath . "?edit_id=$myrow[1]";
			if ($message == "insert") {
				$faq[] = array("$myrow2[1]", "$message", "$myrow2[2]", "$myrow2[5] $myrow2[6]", "$linkit");
			}

		break;
		case "talkback":
			if ($message == "insert" || $message == "update") {
				$talkback[] = array("$myrow2[1]", "$message", "$myrow2[2]", "$myrow2[5] $myrow2[6]");
			}
		break;

		case "title":
			if ($message == "insert" || $message == "update") {
				$records[] = array("$myrow2[1]", "$message", "$myrow2[2]", "$myrow2[5] $myrow2[6]");
			}

		break;
	}

}


////////////////////////////////
// Put it all together
////////////////////////////////

print $css;

$results = "
<div id=\"wrap\">
<div id=\"header\">
	<div id=\"logo\"><img src=\"$AssetPath/images/admin/logo.gif\"  border=\"0\" class=\"logo\"/></div>
	<div id=\"title_text\"><h1>Activity for the week ending " . date("F j, Y") . "</h1></div>
</div>
<div id=\"newsletter\">
	<div id=\"inshort\">
		<a href=\"#records\">Records</a>&nbsp;&nbsp;&nbsp;
		<a href=\"#guides\">Research Guides </a>&nbsp;&nbsp;&nbsp;
		<a href=\"#faqs\">FAQs</a>&nbsp;&nbsp;&nbsp;
		<a href=\"#tb\">TalksBack</a>&nbsp;&nbsp;&nbsp;
	</div>
<p><img src=\"$AssetPath/images/icons/required.png\"> = New this week</p>
<div id=\"main-col\">
<a name=\"records\"></a><h2>Records: New & Modified</h2>\n";

$results .= getItems($records);

$results .= "<a name=\"guides\"></a><h2>Research Guides: New & Modified</h2>\n";

$results .= getItems($research_guide);

$results .= "<a name=\"faqs\"></a><h2>FAQs</h2>\n";

$results .= getItems($faq);

$results .= "<a name=\"tb\"></a><h2>TalksBack</h2>\n";

$results .= getItems($talkback);

$results .= "
$reports \n
</div>\n
<div id=\"sidebar\">
$eres
$search_terms
</div>
</div>";

//////////////////////////
// Print out results
//////////////////////////

print $results;

///////////////////////////
// Email results
///////////////////////////

print "<p>recipients are: $recipients</p>";

normanMailer($results, $recipients);

/////////////////////////////////
// Some functions
/////////////////////////////////

function getItems($array) {

$last_id = "";
$row_count = 0;
$colour1 = "evenrow";
$colour2 = "oddrow";

global $AssetPath;

if (!is_array($array)) { $result = "<p>None.  How sad.</p>"; return $result;}

	foreach ($array as $key => $value) {
		$row_colour = ($row_count % 2) ? $colour1 : $colour2;
		if ($value[0] != $last_id) {
			$result .= "<div class=\"item $row_colour\">";
			if ($value[1] == "insert") {
				$result .= "<img src=\"$AssetPath" . "images/icons/required.png\"> ";
				$creator = "(created by $value[3])";
			} else {
				$creator = "";
			}
			$result .= $value[2] . " $creator";
			$row_count++;
			$result .= "</div>\n";
		}


		$last_id = $value[0];

	}
	return $result;
}

function checkCount($num) {
	if ($num == 1) {
			$text = " was";
		} else {
			$text = "s were";
		}

return $text;

}

function normanMailer($results, $recipients) {

global $administrator_email;
global $css;

ini_set("SMTP", "icmail.ithaca.edu");
ini_set("sendmail_from", $administrator_email);
$to = $recipients;

print "<p>$recipients</p>";
$subjectline = "SubjectsPlus Newsletter: " .  date("F j, Y");
$from = "Library_No_Reply <" . $administrator_email . ">";

/* here the subject and header are assembled */

$header = "Return-Path: $from\r\n";
$header .= "From:  $from\r\n";
$header .= "Content-Type: text/html; charset=iso-8859-1;\n\n\r\n";

$message = "<html>\n<head>\n$css\n</head>\n<body>\n
$results
<br style=\"clear:both;\">\n
<div id=\"footer\">This is an automatically generated email.  Please do not respond.  <strong>Email sent: " . date("l F j, Y, g:i a") . "</strong></div>\n
</body>\n
</html>\n";

$success = mail ($to,$subjectline,$message,$header);

if ($success) { print "email sent"; return "mailed away";}

}

?>