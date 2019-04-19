<?php
namespace SubjectsPlus\Control;

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

class Mailer {
	
	private $_mailer;

	public $SMTPDebug;
	public $Host;
	public $Port;
	public $SMTPAuth;
	public $Username;
	public $Password;

	public function __construct() {


	}

	protected function configureMailer() {
		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		date_default_timezone_set( 'America/New_York' );

		//Create a new PHPMailer instance
		$mailer = new PHPMailer;

		//Tell PHPMailer to use SMTP
		$mailer->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mailer->SMTPDebug = 2;

		//Set the hostname of the mail server
		$mailer->Host = $this->Host;

		//Set the SMTP port number - likely to be 25, 465 or 587
		$mailer->Port = $this->Port;

		//Whether to use SMTP authentication
		$mailer->SMTPAuth = false;

		//Username to use for SMTP authentication
		//$mailer->Username = $this->getUsername();

		//Password to use for SMTP authentication
		//$mailer->Password = $this->getPassword();

		//Set who the message is to be sent from
		$mailer->setFrom( 'cgb37@miami.edu', 'Charles Brown-Roberts' );

		//Set an alternative reply-to address
		$mailer->addReplyTo( 'cgb37@miami.edu', 'Charles Brown-Roberts' );

		//Set who the message is to be sent to
		$mailer->addAddress( 'charlesbrownroberts@miami.edu', 'Charles Brown-Roberts' );

		//Set the subject line
		$mailer->Subject = 'PHPMailer SMTP test';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mailer->msgHTML( 'message content' );

		//Replace the plain text body with one created manually
		$mailer->AltBody = 'This is a plain-text message body';

		//Attach an image file
		//$mailer->addAttachment('images/phpmailer_mini.png');

		return $mailer;
	}


	public function configureMessage() {

	}

	public function send() {
		$this->_mailer = $this->configureMailer();

		//send the message, check for errors
		if ( ! $this->_mailer->send() ) {
			echo 'Mailer Error: ' . $this->_mailer->ErrorInfo;
		} else {
			echo 'Message sent!';
		}
	}


}