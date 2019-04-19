<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-04-18
 * Time: 12:56
 */

use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\MailMessage;


//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('America/New_York');

include( "../control/includes/config.php" );
include( "../control/includes/functions.php" );
include( "../control/includes/autoloader.php" );

/* Set local variables */

$page_title       = _( "Talk Back" );
$page_description = _( "Share your comments and suggestions about the library" );
$page_keywords    = _( "library, comments, suggestions, complaints" );

require_once './includes/header.php';

global $email_host;
global $email_port;
global $email_smtp_auth;
global $email_smtp_debug;


$message = new MailMessage();
$message->setFrom('cgb37@miami.edu');
$message->setAddress('charlesbrownroberts@miami.edu');
$message->setMsgHTML('message body goes here');
$message->setSubject('a great comment subject line');

$mailer = new Mailer($message);
$mailer->Host = $email_host;
$mailer->Port = $email_port;
$mailer->SMTPAuth = $email_smtp_auth;
$mailer->SMTPDebug = $email_smtp_debug;
$mailer->send();



///////////////////////////
// Load footer file
///////////////////////////

require_once './includes/footer.php';