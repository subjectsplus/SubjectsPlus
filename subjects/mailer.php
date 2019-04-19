<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-04-18
 * Time: 12:56
 */

//Import the PHPMailer class into the global namespace
//use PHPMailer\PHPMailer\PHPMailer;
use SubjectsPlus\Control\Mailer;



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

var_dump($email_host);
var_dump($email_port);
var_dump($email_smtp_auth);
var_dump($email_smtp_debug);

////Create a new PHPMailer instance
//$mail = new PHPMailer;
////Tell PHPMailer to use SMTP
//$mail->isSMTP();
////Enable SMTP debugging
//// 0 = off (for production use)
//// 1 = client messages
//// 2 = client and server messages
//$mail->SMTPDebug = 2;
////Set the hostname of the mail server
//$mail->Host = 'smtp.cgcent.miami.edu';
////Set the SMTP port number - likely to be 25, 465 or 587
//$mail->Port = 25;
////We don't need to set this as it's the default value
////$mail->SMTPAuth = false;
////Set who the message is to be sent from
//$mail->setFrom('cgb37@miami.edu', 'Charles Roberts');
////Set an alternative reply-to address
//$mail->addReplyTo('cgb37@miami.edu', 'Charles Roberts');
////Set who the message is to be sent to
//$mail->addAddress('charlesbrownroberts@miami.edu', 'Charles Roberts');
////Set the subject line
//$mail->Subject = 'PHPMailer SMTP without auth test';
////Read an HTML message body from an external file, convert referenced images to embedded,
////convert HTML into a basic plain-text alternative body
//$mail->msgHTML('message contents');
////Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//
////send the message, check for errors
//if (!$mail->send()) {
//	echo "Mailer Error: " . $mail->ErrorInfo;
//} else {
//	echo "Message sent!";
//}


$mailer = new Mailer();
//$mailer->setHost($email_host);
$mailer->setHost('smtp.cgcent.miami.edu');
////$mailer->setPort($email_port);
//$mailer->setPort(25);
////$mailer->setSMTPAuth($email_smtp_auth);
//$mailer->setSMTPAuth(false);
////$mailer->setSMTPDebug($email_smtp_debug);
//$mailer->setSMTPDebug(2);
//$mailer->configureMailer();
//$mailer->configureMessage();
$mailer->send();



///////////////////////////
// Load footer file
///////////////////////////

require_once './includes/footer.php';