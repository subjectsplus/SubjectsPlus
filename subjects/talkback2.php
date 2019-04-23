<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\TalkbackService;
use SubjectsPlus\Control\TalkbackComment;
use SubjectsPlus\Control\MailMessage;
use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\SlackMessenger;
use SubjectsPlus\Control\Template;

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
$todaycomputer = date( 'Y-m-d H:i:s' );

// Show headshots
$show_talkback_face = 1;

/////////////////////////
// Deal with multiple talkback instances
// Usually if you have branch libraries who want separate
// pages/results
////////////////////////
$form_action = "talkback2.php"; // this can be overriden below
$set_filter  = ""; // tritto

if ( isset( $all_tbtags ) ) {

	// Let's get the first item off the tb array to use as our default
	reset( $all_tbtags ); // make sure array pointer is at first element
	$set_filter = key( $all_tbtags );


	// determine branch/filter
	if ( isset( $_REQUEST["v"] ) ) {
		$set_filter = scrubData( lcfirst( $_REQUEST["v"] ) );


		// Quick'n'dirty setup email recipients
		switch ( $set_filter ) {
			case "music":
				$page_title   = "Comments for the Music Library";
				$form_action  = "talkback2.php?v=$set_filter";
				$tb_bonus_css = "talkback_form_music";
				break;
			case "rsmas":
				$page_title  = "Comments for the Marine Library";
				$form_action = "talkback2.php?v=$set_filter";
				break;
			default:
				// nothing, we just use the $administrator email on file (config.php)
				$form_action = "talkback2.php";
		}

		// override our admin email
		if ( isset( $all_tbtags[ $set_filter ] ) && $all_tbtags[ $set_filter ] != "" ) {
			$administrator_email = $all_tbtags[ $set_filter ];
		}

	}
}



/////////////////////////////////////////////////////////////////////////////////////////
// Display Public view /views/talkback/public.php
/////////////////////////////////////////////////////////////////////////////////////////

// echo "Test message from Linux server using ssmtp" | sudo ssmtp -vvv cgb37@miami.edu

if ( isset( $_POST['the_suggestion'] ) ) {

	// clean up post variables
	if ( isset( $_POST["name"] ) ) {
		$this_name = scrubData( $_POST["name"] );
	} else {
		$this_name = "Anonymous";
	}

	if ( isset( $_POST["the_suggestion"] ) ) {
		$this_comment = scrubData( $_POST["the_suggestion"] );
	} else {
		$this_comment = "";
	}


	$newComment = new TalkbackComment();
	$newComment->setQuestion($this_comment);
	$newComment->setQFrom($this_name);
	$newComment->setDateSubmitted($todaycomputer);
	$newComment->setDisplay('No');
	$newComment->setTbtags($set_filter);
	$newComment->setAnswer('');

	global $talkback_use_recaptcha;
	if( $talkback_use_recaptcha === TRUE ) {

		// Call the function post_captcha
		$res = post_captcha($_POST['g-recaptcha-response']);

		if (!$res['success']) {
			// What happens when the reCAPTCHA is not properly set up
			$feedback = $submission_failure_feedback;

		} else {
			// If CAPTCHA is successful...
			// insert the new comment into the db
			$talkbackService->insertComment($newComment);


			global $talkback_use_email;
			if( $talkback_use_email === TRUE ) {

				// get globals for MailMessage class
				global $talkback_to_address;
				global $talkback_to_address_label;
				global $talkback_subject_line;

				$tpl_name = 'html_msg';
				$tpl = new Template( './views/talkback' );
				$html_message = $tpl->render( $tpl_name, array(
					'this_name'    => $this_name,
					'this_comment' => $this_comment,
					'datetime'     => date( 'Y-m-d H:i:s' )

 				) );


				$mailMessege = new MailMessage();
				$mailMessege->setFromAddress($this_name);
				$mailMessege->setFromLabel($this_name);
				$mailMessege->setToAddress($talkback_to_address);
				$mailMessege->setToAddressLabel($talkback_to_address_label);
				$mailMessege->setSubject($talkback_subject_line);
				$mailMessege->setMsgHTML($html_message);

				// get globals for Mailer class
				global $email_host;
				global $email_port;
				global $email_smtp_auth;
				global $email_smtp_debug;

				$mailer = new Mailer($mailMessege);
				$mailer->Host = $email_host;
				$mailer->Port = $email_port;
				$mailer->SMTPAuth = $email_smtp_auth;
				$mailer->SMTPDebug = $email_smtp_debug;
				$mailer->send();
			}


			global $talkback_use_slack;
			if( $talkback_use_slack === TRUE ) {

				global $talkback_slack_channel;
				global $talkback_slack_webhook_url;
				global $talkback_slack_emoji;

				$msg = _( "New Comment via Talkback" ) . PHP_EOL;
				$msg .= "$this_comment" . PHP_EOL;
				$msg .= _( "From: " ) . $this_name . PHP_EOL;
				$msg .= _( "Date submitted: " ) . $todaycomputer . PHP_EOL;
				$msg .= _( "Tags: " ) . $set_filter . PHP_EOL;

				$slackMsg = new SlackMessenger();
				$slackMsg->setChannel($talkback_slack_channel);
				$slackMsg->setIcon($talkback_slack_emoji);
				$slackMsg->setWebhookurl($talkback_slack_webhook_url);
				$slackMsg->setMessage($msg);
				//$slackMsg->send();
			}
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
	$comment_header =  _( "Comments from Previous Years" );
	$current_comments_link = "?v=".$set_filter;
	$current_comments_label = _( "See this year" );

} else {
	$comment_year = 'current';
	$comment_header = _( "Comments from " ) . $this_year;
	$current_comments_link = "?t=prev&v=".$set_filter;
	$current_comments_label = _( "See previous years" );
}


$comments_response = $talkbackService->getComments($comment_year, $this_year, $filter, $cat_tags);

if(!empty($comments_response)) {

	$comments = $comments_response;
} else {

	$comments = _( "There are no comments just yet.  Be the first!" );
}

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


// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	$tpl_folder = "./views/{$subjects_theme}/talkback";
} else {
	$tpl_folder = "./views/talkback";
}

$tpl_name = 'public';

$tpl = new Template( $tpl_folder );
echo $tpl->render( $tpl_name, array(
	'form_action'  => $form_action,
	'comments'     => $comments,
	'this_name'    => $this_name,
	'this_comment' => $this_comment,
	'show_talkback_face'    => $show_talkback_face,
	'set_filter'            => $set_filter,
	'comment_year'          => $comment_year,
	'comment_header'        => $comment_header,
	'current_comments_link' => $current_comments_link,
	'current_comments_label' => $current_comments_label

) );






///////////////////////////
// Load footer file
///////////////////////////

require_once './includes/footer.php';