<?php
use SubjectsPlus\Control\Template;
use SubjectsPlus\Control\ReCaptchaService;
use SubjectsPlus\Control\MailMessage;
use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\SlackMessenger;
use SubjectsPlus\Control\ProblemReportService;


include( "../control/includes/config.php" );
include( "../control/includes/functions.php" );
include( "../control/includes/autoloader.php" );


$problemReportService = new ProblemReportService();

/**
 *
 * @global $AssetPath
 */
global $AssetPath;

/**
 *
 * @var $set_filter
 */
$set_filter  = "";

/**
 * $branch_filter is used to filter comments by branch
 * it is set in branch_metadata.php
 *
 * @var $branch_filter
 *
 */
$branch_filter = "";

$feedback = "";

$item_title = "";
$item_permalink = "";

/**
 * this can be overriden in branch_metadata.php
 * @var $form_action
 */
$form_action = "usearch-problem-report.php";


global $problem_report_use_email;



global $problem_report_use_slack;

global $problem_report_slack_channel;

global $problem_report_slack_webhook_url;

global $problem_report_slack_emoji;

/**
 * globals for ReCaptcha
 * @global $problem_report_use_recaptcha
 */
global $problem_report_use_recaptcha;

/**
 *
 * @var $problem_report_recaptcha_secret_key
 */
global $problem_report_recaptcha_secret_key;

/**
 *
 * @var $problem_report_recaptcha_site_key
 */
global $problem_report_recaptcha_site_key;


/**
 * @todo this should be defined in branch_metadata.php
 * init the $recaptcha_response var for use with the template after it's set in the if/else block
 * @var $recaptcha_response
 */
$recaptcha_response = "";

/**
 * @todo this should be defined in branch_metadata.php
 * @var $recaptcha_response_fail
 */
$recaptcha_response_fail = _("Recaptcha score is too low. Your comment was not submitted: ");




/**
 * Include the page metadata based on the theme used
 * Variables set here will be used in the appropriate theme header
 * @global $subjects_theme
 */
if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	include("./themes/{$subjects_theme}/views/usearch-problem-report/page_metadata.php");
} else {
	include( "views/usearch-problem-report/page_metadata.php" );
}


/**
 * Include the branch_metadata based on the theme used
 * @global $subjects_theme
 */
if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	include("./themes/{$subjects_theme}/views/usearch-problem-report/branch_metadata.php");
} else {
	include( "./views/usearch-problem-report/branch_metadata.php" );
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




if ( isset( $_POST['use_recaptcha'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {

//	// clean up post variables
	if ( isset( $_POST["user_name"] ) ) {
		$user_name = scrubData( $_POST["user_name"] );
	} else {
		$user_name = "Anonymous";
	}

	if ( isset( $_POST["user_email"] ) ) {
		$user_email = scrubData( $_POST["user_email"] );
	} else {
		$user_email = "";
	}

	if ( isset( $_POST["affiliation"] ) ) {
		$affiliation = scrubData( $_POST["affiliation"] );
	}

	if ( isset( $_POST["problem_type"] ) ) {
		$problem_type = scrubData( $_POST["problem_type"] );
	}

	if ( isset( $_POST["description"] ) ) {
		$description = scrubData( $_POST["description"] );
	} else {
		$description = "No Description Entered";
	}

	if ( isset( $_POST["item_title"] ) ) {
		$item_title = scrubData( $_POST["item_title"] );
	} else {
		$item_title = "No Title Entered";
	}

	if ( isset( $_POST["item_permalink"] ) ) {
		$item_permalink = scrubData( $_POST["item_permalink"] );
	} else {
		$item_permalink = "No Permalink Entered";
	}

	$msg = _( "New uSearch Problem Reported" ) . PHP_EOL;
	$msg .= _( "Date submitted: " ) . date( 'D M j, Y, g:i a' ) . PHP_EOL;
	$msg .= _( "From Name: " ) . $user_name . PHP_EOL;
	$msg .= _( "From Email: " ) . $user_email . PHP_EOL;
	$msg .= _( "Affiliation: " ) . $affiliation . PHP_EOL;
	$msg .= _( "Problem Item: " ) . $item_title . PHP_EOL;
	$msg .= _( "Problem Permalink: " ) . $item_permalink . PHP_EOL;
	$msg .= _( "Problem Type: " ) . $problem_type . PHP_EOL;
	$msg .= _( "Problem Description: " ) . $description . PHP_EOL;
	$msg .= _( "Branch: " ) . $branch_filter . PHP_EOL;


	/**
	 * create the html email template
	 * @var $tpl_name
	 * @var $tpl
	 * @var $html_message
	 */
	$tpl_name     = 'html_msg';
	$tpl          = new Template( './views/usearch-problem-report' );
	$html_message = $tpl->render( $tpl_name, array(
		'msg'   => $msg
	));

	/**
	 * configure MailMessage
	 * @var $mailMessege
	 */
	$mailMessege = new MailMessage();
	$mailMessege->setFromAddress( $user_email );
	$mailMessege->setFromLabel( $user_name );
	$mailMessege->setToAddress( $administrator_email );
	//$mailMessege->setToAddressLabel( $talkback_to_address_label );
	$mailMessege->setSubject( 'uSearch Problem Report' );
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


	/**
	 * send comment to slack channel talkback
	 * @var $slackMsg
	 */
	$slackMsg = new SlackMessenger();
	$slackMsg->setChannel( $problem_report_slack_channel );
	$slackMsg->setIcon( $problem_report_slack_emoji );
	$slackMsg->setWebhookurl( $problem_report_slack_webhook_url );
	$slackMsg->setMessage( $msg );


	if ( $problem_report_use_recaptcha === true && isset($_POST['recaptcha_response']) ) {

		/**
		 * init ReCaptchaService
		 * @var $recaptcha_service
		 */
		$recaptcha_service = new ReCaptchaService();
		$recaptcha_service->setServerName( scrubData( $_SERVER['SERVER_NAME'] ) );
		$recaptcha_service->setRemoteAddr( scrubData( $_SERVER['REMOTE_ADDR'] ) );
		$recaptcha_service->setAction( 'usearch_problem_report' );
		$recaptcha_service->setToken( scrubData( $_POST['recaptcha_response'] ) );
		$recaptcha_response = $recaptcha_service->verify( $talkback_recaptcha_secret_key );


		// Take action based on the score returned:
		if ( $recaptcha_response->getScore() >= 0.5 ) {

			// If CAPTCHA is successful...
			$feedback_success = $recaptcha_response->getScore();
			$is_robot = false;

		} else {
			// Not verified - show form error
			$feedback_fail = $recaptcha_response_fail;
			$is_robot              = true;

		}

	} else {
		//no security on this form - it could be a robot but we cannot determine that so send it anyway
		$is_robot = false;
	}

	if($is_robot === false) {

		// insert the new comment into the db, send to email option, send to slack option, and provide user feedback
		if( $problemReportService->sendCommunications( $problem_report_use_email,  $mailer, $problem_report_use_slack,  $slackMsg ) ) {
			$feedback = $feedback_success;
		} else {
			$feedback = $feedback_fail;
		}
	}

}



/**
 * Pass the template parameters to the index view template based on the theme used
 * @global $subjects_theme
 * @var $tpl_folder
 */

if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
	$tpl_folder = "./themes/{$subjects_theme}/views/usearch-problem-report";
} else {
	$tpl_folder = "./views/usearch-problem-report";
}

/**
 *
 * @var $tpl_name
 */
$tpl_name = 'index';

/**
 *
 * @var $tpl
 */
$tpl = new Template( $tpl_folder );
echo $tpl->render( $tpl_name, array(
	'asset_path'                          => $AssetPath,
	'page_title'                          => $page_title,
	'page_description'                    => $page_description,
	'page_keywords'                       => $page_keywords,
	'form_action'                         => $form_action,
	'set_filter'                          => $set_filter,
	'feedback'                            => $feedback,
	'problem_report_use_recaptcha'        => $problem_report_use_recaptcha,
	'problem_report_recaptcha_site_key'   => $problem_report_recaptcha_site_key,
	'problem_report_recaptcha_secret_key' => $problem_report_recaptcha_secret_key,
	'item_title'                          => $item_title,
	'item_permalink'                      => $item_permalink


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