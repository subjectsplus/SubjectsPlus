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

	public function __construct(MailMessage $message) {

		$this->_msg = $message;

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

		return $mailer;
	}


	public function configureMessage() {

		$this->_mailer = $this->configureMailer();

		//Set who the message is to be sent from
		$this->_mailer->setFrom( $this->_msg->getFrom(), 'Charles Brown-Roberts' );

		//Set an alternative reply-to address
		$this->_mailer->addReplyTo( 'cgb37@miami.edu', 'Charles Brown-Roberts' );

		//Set who the message is to be sent to
		$this->_mailer->addAddress( $this->_msg->getAddress(), 'Charles Brown-Roberts' );

		//Set the subject line
		$this->_mailer->Subject = $this->_msg->getSubject();

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->_mailer->msgHTML( $this->_msg->getMsgHTML() );

		//Attach an image file
		//$this->_mailer->addAttachment('images/phpmailer_mini.png');
		return $this->_mailer;

	}

	public function send() {

		$this->_mailer = $this->configureMessage();


		//send the message, check for errors
		if ( ! $this->_mailer->send() ) {
			echo 'Mailer Error: ' . $this->_mailer->ErrorInfo;
		} else {
			echo 'Message sent!';
		}
	}


}