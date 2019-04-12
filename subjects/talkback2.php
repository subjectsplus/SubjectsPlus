<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\TalkbackService;
use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\MailMessage;
use SubjectsPlus\Control\SlackMessenger;

include( "../control/includes/config.php" );
include( "../control/includes/functions.php" );
include( "../control/includes/autoloader.php" );

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	include( "themes/$subjects_theme/talkback.php" );
	exit;
}

/* Set local variables */

$page_title       = _( "Talk Back" );
$page_description = _( "Share your comments and suggestions about the library" );
$page_keywords    = _( "library, comments, suggestions, complaints" );

$db = new Querier();
$talkbackService = new TalkbackService($db);


require_once './includes/header.php';


/////////////////////////////////////////////////////////////////////////////////////////
// Get Active Comments and Pass off to /views/talkback/public.php
/////////////////////////////////////////////////////////////////////////////////////////

$today     = getdate();
$month     = $today['month'];
$mday      = $today['mday'];
$year      = $today['year'];
$this_year = date( "Y" );

/////////////////////////
// Deal with multiple talkback instances
// Usually if you have branch libraries who want separate
// pages/results
////////////////////////
$set_filter  = "";

if ( isset( $all_tbtags ) ) {
// Let's get the first item off the tb array to use as our default
	reset( $all_tbtags ); // make sure array pointer is at first element
	$set_filter = key( $all_tbtags );

// And set our default bonus sql
	$bonus_sql = "AND tbtags LIKE '%" . $set_filter . "%'";

// determine branch/filter
	if ( isset( $_REQUEST["v"] ) ) {
		$set_filter = scrubData( lcfirst( $_REQUEST["v"] ) );
		$bonus_sql  = "AND tbtags LIKE '%" . $set_filter . "%'";

		// Quick'n'dirty setup email recipients
		switch ( $set_filter ) {
			case "music":
				$page_title   = "Comments for the Music Library";
				$form_action  = "talkback.php?v=$set_filter";
				$tb_bonus_css = "talkback_form_music";
				break;
			case "rsmas":
				$page_title  = "Comments for the Marine Library";
				$form_action = "talkback.php?v=$set_filter";
				break;
			default:
				// nothing, we just use the $administrator email on file (config.php)
				$form_action = "talkback.php";
		}

		// override our admin email
		if ( isset( $all_tbtags[ $set_filter ] ) && $all_tbtags[ $set_filter ] != "" ) {
			$administrator_email = $all_tbtags[ $set_filter ];
		}

	}
}


$filter = '%' . $set_filter . '%';
if ( isset( $_GET['c'] ) ) {
	$cat_tags = '%' . scrubData( $_GET['c'] ) . '%';

} else {
	$cat_tags = "%%";

}

if ( isset( $_GET["t"] ) && $_GET["t"] == "prev" ) {
	$comment_year = 'prev';
} else {
	$comment_year = 'current';
}


$comments = $talkbackService->getComments($comment_year, $this_year, $filter, $cat_tags);

/////////////////////////////////////////////////////////////////////////////////////////
// End Get Comments
/////////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////////////
// Display Public view /views/talkback/public.php
/////////////////////////////////////////////////////////////////////////////////////////

// clean up post variables
if ( isset( $_POST["name"] ) ) {
	$this_name = scrubData( $_POST["name"] );
} else {
	$this_name = "";
}

if ( isset( $_POST["the_suggestion"] ) ) {
	$this_comment = scrubData( $_POST["the_suggestion"] );
} else {
	$this_comment = "";
}



if( $talkbackService->getUseRecaptcha() == TRUE ) {

	echo 'Use recaptcha: '. $talkbackService->getUseRecaptcha();


}


if( $talkbackService->getUseEmail() == TRUE ) {

	echo 'Use email: '. $talkbackService->getUseEmail();

	$mailMessege = new MailMessage();

	$mailer = new Mailer($mailMessege);
	$mailer->send($mailMessege);



}


if( $talkbackService->getUseSlack() == TRUE ) {

	echo 'Use slack: '. $talkbackService->getUseSlack();

	$slackMsg = new SlackMessenger();




}



require_once './views/talkback/public.php';



///////////////////////////
// Load footer file
///////////////////////////

require_once './includes/footer.php';