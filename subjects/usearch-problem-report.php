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

global $BaseURL;

/**
 * globals for Mailer class
 * @global $email_host
 */
global $email_host;

/**
 *
 * @var $email_port
 */
global $email_port;

/**
 *
 * @var $email_smtp_auth
 */
global $email_smtp_auth;

/**
 *
 * @var $email_smtp_debug
 */
global $email_smtp_debug;

/**
 * global for the reply email address for subjectsplus administrator
 * @global $administrator_email
 */
global $administrator_email;

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


global $problem_report_use;

global $problem_report_use_email;

global $problem_report_email_recipients;

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
 * If set to false in the config the page will redirect to the baseurl
 */
if($problem_report_use !== true) {
	header( "Location: " . $BaseURL);
}



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


$form_submit_success = _("Thank you for reporting this issue. Someone will contact you shortly.");


$form_submit_fail = _("There was a problem submitting your issue. Please contact the website administrator.");

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




if ( isset($_POST['problem_report_form']) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {

	// clean up post variables
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
		$item_permalink = scrubData( urldecode($_POST["item_permalink"]) );
	} else {
		$item_permalink = "No Permalink Entered";
	}

	if ( isset( $_POST["primo_view"] ) ) {
		$primo_view = scrubData($_POST['primo_view']);
	} else {
		$primo_view = 'richter';
	}

	$date_submitted =  date( 'D M j, Y, g:i a' );


	/**
	 * create the html email template
	 * @var $tpl
	 * @var $tpl_name
	 * @var $tpl_folder
	 * @var $email_message
	 */
	if ( isset( $subjects_theme ) && $subjects_theme != "" ) {
		$tpl_folder = "./themes/{$subjects_theme}/views/usearch-problem-report";
	} else {
		$tpl_folder = "./views/usearch-problem-report";
	}

	$tpl_name     = 'email_msg';
	$tpl          = new Template( $tpl_folder );
	$email_message = $tpl->render( $tpl_name, array(
		'user_name'      => $user_name,
		'user_email'     => $user_email,
		'affiliation'    => $affiliation,
		'item_title'     => $item_title,
		'item_permalink' => $item_permalink,
		'primo_view'     => $primo_view,
		'problem_type'   => $problem_type,
		'description'    => $description,
		'date_submitted' => $date_submitted
	));

	/**
	 * configure MailMessage
	 * @var $mailMessege
	 */
	$mailMessege = new MailMessage();
	$mailMessege->setFromAddress( $user_email );
	$mailMessege->setFromLabel( $user_name );
	$mailMessege->setToAddress( $administrator_email );
	if ( isset($problem_report_email_recipients) && !empty($problem_report_email_recipients)) {
		$mailMessege->setToCcAddresses($problem_report_email_recipients);
	}
	$mailMessege->setSubject( 'uSearch Problem Report' );
	$mailMessege->setMsgHTML( $email_message );


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
	 * Assemble slack message
	 */
	$msg = _( "New uSearch Problem Reported" ) . PHP_EOL;
	$msg .= _( "From Name: " ) . $user_name . PHP_EOL;
	$msg .= _( "From Email: " ) . $user_email . PHP_EOL;
	$msg .= _( "Affiliation: " ) . $affiliation . PHP_EOL;
	$msg .= _( "Problem Item: " ) . $item_title . PHP_EOL;
	$msg .= _( "Problem Permalink: " ) . $item_permalink . PHP_EOL;
	$msg .= _( "Primo View: " ) . $primo_view . PHP_EOL;
	$msg .= _( "Problem Type: " ) . $problem_type . PHP_EOL;
	$msg .= _( "Problem Description: " ) . $description . PHP_EOL;
	$msg .= _( "Date submitted: " ) . date( 'D M j, Y, g:i a' ) . PHP_EOL;


	$webHookUrl = "https://hooks.slack.com/services/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";
	$channel = "primo";
	$message = "testing";
	$icon = ":anger:";

//	function slack($webHookUrl, $message, $channel = "general", $icon = ":sunglasses:") {
//		$channel = ($channel) ? $channel : "general";
//		$data = "payload=" . json_encode(array(
//				"channel"       =>  "#{$channel}",
//				"text"          =>  $message,
//				"icon_emoji"    =>  $icon
//			));
//		// Get your webhook endpoint from your Slack settings
//		$ch = curl_init($webHookUrl);
//		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		$result = curl_exec($ch);
//		curl_close($ch);
//		return $result;
//	}
//
//	slack($problem_report_slack_webhook_url, $msg, $problem_report_slack_channel, $problem_report_slack_emoji);

	/**
	 * send comment to slack channel talkback
	 * @var $slackMsg
	 */
	$slackMsg = new SlackMessenger();
	$slackMsg->setChannel( $problem_report_slack_channel );
	$slackMsg->setIcon( $problem_report_slack_emoji );
	$slackMsg->setWebhookurl( $problem_report_slack_webhook_url );
	$slackMsg->setMessage( $message );





	if ( $problem_report_use_recaptcha === true &&  isset( $_POST['use_recaptcha'] ) && isset($_POST['recaptcha_response']) ) {

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
			$is_robot = false;
		} else {
			// this is a robot or spam bot. Do not submit the form
			$is_robot = true;
		}

	} else {
		//no security on this form - it could be a robot but we cannot determine that so send it anyway
		$is_robot = false;
	}

	if($is_robot === false) {

		// insert the new comment into the db, send to email option, send to slack option, and provide user feedback
		if( $problemReportService->sendCommunications( $problem_report_use_email,  $mailer, $problem_report_use_slack,  $slackMsg ) ) {
			$feedback = $form_submit_success;
		} else {
			$feedback = $form_submit_fail;
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