<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\TalkbackService;
use SubjectsPlus\Control\TalkbackComment;
use SubjectsPlus\Control\MailMessage;
use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\SlackMessenger;
use SubjectsPlus\Control\Template;
use SubjectsPlus\Control\ReCaptchaService;

include( "../control/includes/config.php" );
include( "../control/includes/functions.php" );
include( "../control/includes/autoloader.php" );

/**
 * Set global vars.
 * @global $administrator_email
 * @global $talkback_use_recaptcha
 * @global $talkback_to_address
 * @global $talkback_to_address_label
 * @global $talkback_subject_line
 */
global $administrator_email;

// get globals for MailMessage class
global $talkback_to_address;
global $talkback_to_address_label;
global $talkback_subject_line;

// get globals for Mailer class
global $email_host;
global $email_port;
global $email_smtp_auth;
global $email_smtp_debug;

// get globals for ReCaptcha
global $talkback_use_recaptcha;
global $talkback_recaptcha_secret_key;
global $talkback_recaptcha_site_key;

// get globals for Slack
global $talkback_use_slack;
global $talkback_slack_channel;
global $talkback_slack_webhook_url;
global $talkback_slack_emoji;


// Show headshots
$show_talkback_face = 1;



/**
 * Set local variables
 * @var $page_title
 * @var $page_description
 * @var $page_keywords
 * @var $insertCommentFeedback
 * @var $today
 * @var $month
 * @var $mday
 * @var $year
 * @var $this_year
 * @var $todaycomputer
 */

$page_title       = _( "Talk Back" );
$page_description = _( "Share your comments and suggestions about the library" );
$page_keywords    = _( "library, comments, suggestions, complaints" );

$today     = getdate();
$month     = $today['month'];
$mday      = $today['mday'];
$year      = $today['year'];
$this_year = date( "Y" );
$todaycomputer = date( 'Y-m-d H:i:s' );

$insertCommentFeedback = "";
$insertCommentSuccessFeedback =  _("Thank you for your feedback.  We will try to post a response within the next three business days.");
$insertCommentFeedbackFail = _("There was a problem with your submission.  Please try again.") . PHP_EOL;
$insertCommentFeedbackFail .= _("If you continue to get an error, please contact the <a href='mailto:{$administrator_email}'>administrator</a>");




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


/**
 * init the $recaptcha_response var for use with the template after it's set in the if/else block
 * @var $recaptcha_response
 */
$recaptcha_response = "";

if ( isset( $_POST['the_suggestion'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response']) ) {

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


	/**
	 * TalkbackComment object
	 * @var $newComment
	 */
	$newComment = new TalkbackComment();
	$newComment->setQuestion( $this_comment );
	$newComment->setQFrom( $this_name );
	$newComment->setDateSubmitted( $todaycomputer );
	$newComment->setDisplay( 'No' );
	$newComment->setTbtags( $set_filter );
	$newComment->setAnswer( '' );


	if ( $talkback_use_recaptcha === true ) {

		/**
		 * init ReCaptchaService
		 * @var $recaptcha_service
		 */
		$recaptcha_service = new ReCaptchaService();
		$recaptcha_service->setServerName( scrubData( $_SERVER['SERVER_NAME'] ) );
		$recaptcha_service->setRemoteAddr( scrubData( $_SERVER['REMOTE_ADDR'] ) );
		$recaptcha_service->setAction( 'talkback' );
		$recaptcha_service->setToken( scrubData( $_POST['recaptcha_response'] ) );
		$recaptcha_response = $recaptcha_service->verify( $talkback_recaptcha_secret_key );


		// Take action based on the score returned:
		if ( $recaptcha_response->getScore() >= 0.5 ) {
			// Verified - send email
			$recaptcha_response = "recaptcha score: " . $recaptcha_response->getScore();

			// If CAPTCHA is successful...
			// insert the new comment into the db and provide user feedback
			if( !$talkbackService->insertComment( $newComment ) ) {
				$insertCommentFeedback = $insertCommentSuccessFeedback;
			} else {
				$insertCommentFeedback = $insertCommentFeedbackFail;
			}


			if ( $talkback_use_email === true ) {

				/**
				 * create the html email template
				 * @var $tpl_name
				 * @var $tpl
				 * @var $html_message
				 */
				$tpl_name     = 'html_msg';
				$tpl          = new Template( './views/talkback' );
				$html_message = $tpl->render( $tpl_name, array(
					'this_name'    => $this_name,
					'this_comment' => $this_comment,
					'datetime'     => date( 'Y-m-d H:i:s' )

				) );

				/**
				 * configure MailMessage
				 * @var $mailMessege
				 */
				$mailMessege = new MailMessage();
				$mailMessege->setFromAddress( $this_name );
				$mailMessege->setFromLabel( $this_name );
				$mailMessege->setToAddress( $talkback_to_address );
				$mailMessege->setToAddressLabel( $talkback_to_address_label );
				$mailMessege->setSubject( $talkback_subject_line );
				$mailMessege->setMsgHTML( $html_message );


				/**
				 * configure Mailer and send email
				 * @var $mailer
				 */
				$mailer            = new Mailer( $mailMessege );
				$mailer->Host      = $email_host;
				$mailer->Port      = $email_port;
				$mailer->SMTPAuth  = $email_smtp_auth;
				$mailer->SMTPDebug = $email_smtp_debug;

				// provide user feedback for mail
				$mailer->send();
			}

			// set up a slack message
			if ( $talkback_use_slack === true ) {

				$msg = _( "New Comment via Talkback" ) . PHP_EOL;
				$msg .= "$this_comment" . PHP_EOL;
				$msg .= _( "From: " ) . $this_name . PHP_EOL;
				$msg .= _( "Date submitted: " ) . $todaycomputer . PHP_EOL;
				$msg .= _( "Tags: " ) . $set_filter . PHP_EOL;

				/**
				 * send comment to slack channel talkback
				 * @var $slackMsg
				 */
				$slackMsg = new SlackMessenger();
				$slackMsg->setChannel( $talkback_slack_channel );
				$slackMsg->setIcon( $talkback_slack_emoji );
				$slackMsg->setWebhookurl( $talkback_slack_webhook_url );
				$slackMsg->setMessage( $msg );
				$slackMsg->send();
			}

		}

	} else {
		// Not verified - show form error
		$recaptcha_response = "Recaptcha score is too low. Your comment was not submitted: " . $recaptcha_response->getScore();
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


/**
 * init Querier to pass to TalkbackService class
 * @var $db
 * @var $talkbackService
 */
$db = new Querier();
$talkbackService = new TalkbackService($db);

/**
 * Get Active Comments and Pass off to if/else block for use with template
 * @var $comments_response
 */
$comments_response = $talkbackService->getComments($comment_year, $this_year, $filter, $cat_tags);


/**
 * Set the $comments template var
 * @var $comments
 */
if(!empty($comments_response)) {
	$comments = $comments_response;
} else {
	$comments = _( "There are no comments just yet.  Be the first!" );
}


/**
 * Include the header based on the theme used
 * @global $subjects_theme
 */
if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	include( "includes/header_{$subjects_theme}.php" );
} else {
	include( "includes/header.php" );
}


/**
 * Pass the template parameters to the public view template based on the theme used
 * @global $subjects_theme
 * @var $tpl_folder
 * @var $tpl_name
 * @var $tpl
 */

if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	$tpl_folder = "./views/{$subjects_theme}/talkback";
} else {
	$tpl_folder = "./views/talkback";
}

$tpl_name = 'public';

$tpl = new Template( $tpl_folder );
echo $tpl->render( $tpl_name, array(
	'form_action'                 => $form_action,
	'comments'                    => $comments,
	'this_name'                   => $this_name,
	'this_comment'                => $this_comment,
	'show_talkback_face'          => $show_talkback_face,
	'set_filter'                  => $set_filter,
	'comment_year'                => $comment_year,
	'comment_header'              => $comment_header,
	'current_comments_link'       => $current_comments_link,
	'current_comments_label'      => $current_comments_label,
	'recaptcha_response'          => $recaptcha_response,
	'insertCommentFeedback'       => $insertCommentFeedback,
	'talkback_recaptcha_site_key' => $talkback_recaptcha_site_key

) );

/**
 * Include the footer based on the theme used
 * @global $subjects_theme
 */
if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	include( "includes/footer_{$subjects_theme}.php" );
} else {
	include( "includes/footer.php" );
}