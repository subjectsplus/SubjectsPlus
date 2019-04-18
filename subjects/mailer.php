<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-04-18
 * Time: 12:56
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
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

$mailer = new Mailer();
$mailer->send();

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
////Whether to use SMTP authentication
//$mail->SMTPAuth = false;
////Username to use for SMTP authentication
////$mail->Username = 'yourname@example.com';
////Password to use for SMTP authentication
////$mail->Password = 'yourpassword';
////Set who the message is to be sent from
//$mail->setFrom('cgb37@miami.edu', 'Charles Brown-Roberts');
////Set an alternative reply-to address
//$mail->addReplyTo('cgb37@miami.edu', 'Charles Brown-Roberts');
////Set who the message is to be sent to
//$mail->addAddress('charlesbrownroberts@miami.edu', 'Charles Brown-Roberts');
////Set the subject line
//$mail->Subject = 'PHPMailer SMTP test';
////Read an HTML message body from an external file, convert referenced images to embedded,
////convert HTML into a basic plain-text alternative body
//$mail->msgHTML('message content');
////Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
////Attach an image file
////$mail->addAttachment('images/phpmailer_mini.png');
//
////send the message, check for errors
//if (!$mail->send()) {
//	echo 'Mailer Error: ' . $mail->ErrorInfo;
//} else {
//	echo 'Message sent!';
//}


///////////////////////////
// Load footer file
///////////////////////////

require_once './includes/footer.php';