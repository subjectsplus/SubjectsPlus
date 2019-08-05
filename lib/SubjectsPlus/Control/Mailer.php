<?php
namespace SubjectsPlus\Control;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer {

	private $_mailer;

	public $SMTPDebug;
	public $Host;
	public $Port;
	public $SMTPAuth;
	public $Username;
	public $Password;

	/**
	 * Mailer constructor.
	 *
	 * @param MailMessage $message
	 */
	public function __construct(MailMessage $message) {
		$this->_msg = $message;
	}

	/**
	 * @return PHPMailer
	 */
	protected function configureMailer() {
		// SMTP needs accurate times, and the PHP time zone MUST be set
		// This should be done in your php.ini, but if not, it can be set in control/includes/config.php

		// Create a new PHPMailer instance
		$mailer = new PHPMailer;

		//Tell PHPMailer to use SMTP
		$mailer->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mailer->SMTPDebug = $this->SMTPDebug;

		//Set the hostname of the mail server
		$mailer->Host = $this->Host;

		//Set the SMTP port number - likely to be 25, 465 or 587
		$mailer->Port = $this->Port;

		$mailer->isHTML(true);

		if($this->SMTPAuth == true) {

			//Whether to use SMTP authentication
			$mailer->SMTPAuth = true;

			//Username to use for SMTP authentication
			$mailer->Username = $this->Username;

			//Password to use for SMTP authentication
			$mailer->Password = $this->Password;
		} else {

			//Whether to use SMTP authentication
			$mailer->SMTPAuth = false;
		}

		return $mailer;
	}


	/**
	 * @return PHPMailer
	 * @throws \PHPMailer\PHPMailer\Exception
	 */
	public function configureMessage() {
		$this->_mailer = $this->configureMailer();

		//Set who the message is to be sent from
		$this->_mailer->setFrom( $this->_msg->getFromAddress(), $this->_msg->getFromLabel() );

		//Set who the message is to be sent to
		$this->_mailer->addAddress( $this->_msg->getToAddress(), $this->_msg->getToAddressLabel() );

		// Set CC addresses if they exist
		$cc_addresses = $this->_msg->getToCcAddresses();
		if( isset($cc_addresses) && !empty($cc_addresses)) {
			foreach($cc_addresses as $address) {
				$this->_mailer->addCC($address);
			}
		}

		//Set the subject line
		$this->_mailer->Subject = $this->_msg->getSubject();

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->_mailer->msgHTML( $this->_msg->getMsgHTML() );

		//Replace the plain text body with one created manually
		$this->_mailer->AltBody = $this->_msg->getAltBody();

		//Attach an image file
		//$this->_mailer->addAttachment('images/phpmailer_mini.png');
		return $this->_mailer;
	}

	/**
	 * @throws \PHPMailer\PHPMailer\Exception
	 */
	public function send() {
		$this->_mailer = $this->configureMessage();

		//send the message, check for errors
		if ( ! $this->_mailer->send() ) {
			echo 'Mailer Error: ' . $this->_mailer->ErrorInfo;
			return false;
		} else {
			return true;
		}
	}
}