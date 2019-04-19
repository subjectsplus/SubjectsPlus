<?php
namespace SubjectsPlus\Control;

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

class Mailer {
	
	private $_mailer;

	private $_SMTPDebug;
	private $_Host;
	private $_Port;
	private $_SMTPAuth;
	private $_Username;
	private $_Password;

	public function __construct() {
		global $email_server;
		global $administrator_email;

		ini_set( "SMTP", $email_server );
		ini_set( "sendmail_from", $administrator_email );

		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		date_default_timezone_set( 'America/New_York' );

		//Create a new PHPMailer instance
		$this->_mailer = new PHPMailer;
		$this->configureMailer();

	}
	
	
	protected function configureMailer() {

		//Tell PHPMailer to use SMTP
		$this->_mailer->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		//$this->_mailer->SMTPDebug = $this->getSMTPDebug();
		$this->_mailer->SMTPDebug = 2;

		//Set the hostname of the mail server
		//$this->_mailer->Host = $this->getHost();
		$this->_mailer->Host = 'smtp.cgcent.miami.edu';

		//Set the SMTP port number - likely to be 25, 465 or 587
		//$this->_mailer->Port = $this->getPort();
		$this->_mailer->Port = 25;

		//Whether to use SMTP authentication
		//$this->_mailer->SMTPAuth = $this->getSMTPAuth();
		$this->_mailer->SMTPAuth = false;

		//Username to use for SMTP authentication
		//$this->_mailer->Username = $this->getUsername();

		//Password to use for SMTP authentication
		//$this->_mailer->Password = $this->getPassword();

	}


	public function configureMessage() {


		//Set who the message is to be sent from
		$this->_mailer->setFrom( 'cgb37@miami.edu', 'Charles Brown-Roberts' );

		//Set an alternative reply-to address
		$this->_mailer->addReplyTo( 'cgb37@miami.edu', 'Charles Brown-Roberts' );

		//Set who the message is to be sent to
		$this->_mailer->addAddress( 'charlesbrownroberts@miami.edu', 'Charles Brown-Roberts' );

		//Set the subject line
		$this->_mailer->Subject = 'PHPMailer SMTP test';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->_mailer->msgHTML( 'message content' );

		//Replace the plain text body with one created manually
		$this->_mailer->AltBody = 'This is a plain-text message body';

		//Attach an image file
		//$this->_mailer->addAttachment('images/phpmailer_mini.png');

	}

	/**
	 * @return mixed
	 */
	public function getSMTPDebug() {
		return $this->_SMTPDebug;
	}

	/**
	 * @param mixed $SMTPDebug
	 */
	public function setSMTPDebug( $SMTPDebug ){
		$this->_SMTPDebug = $SMTPDebug;
	}

	/**
	 * @return mixed
	 */
	public function getHost() {
		return $this->_Host;
	}

	/**
	 * @param mixed $Host
	 */
	public function setHost( $Host ){
		$this->_Host = $Host;
	}

	/**
	 * @return mixed
	 */
	public function getPort() {
		return $this->_Port;
	}

	/**
	 * @param mixed $Port
	 */
	public function setPort( $Port ){
		$this->_Port = $Port;
	}

	/**
	 * @return mixed
	 */
	public function getSMTPAuth() {
		return $this->_SMTPAuth;
	}

	/**
	 * @param mixed $SMTPAuth
	 */
	public function setSMTPAuth( $SMTPAuth ){
		$this->_SMTPAuth = $SMTPAuth;
	}

	/**
	 * @return mixed
	 */
	public function getUsername() {
		return $this->_Username;
	}

	/**
	 * @param mixed $Username
	 */
	public function setUsername( $Username ){
		$this->_Username = $Username;
	}

	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->_Password;
	}

	/**
	 * @param mixed $Password
	 */
	public function setPassword( $Password ){
		$this->_Password = $Password;
	}





	public function send() {

		//send the message, check for errors
		if ( ! $this->_mailer->send() ) {
			echo 'Mailer Error: ' . $this->_mailer->ErrorInfo;
		} else {
			echo 'Message sent!';
		}


	}
//  public function send(MailMessage $m) {
//    if (mail($m->getTo(), $m->getSubjectLine(), $m->getContent(), $m->getHeader())) {
//      return true;
//    }
//  }

}