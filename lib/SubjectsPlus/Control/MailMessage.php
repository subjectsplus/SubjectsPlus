<?php
   namespace SubjectsPlus\Control;
   //Import the PHPMailer class into the global namespace
   use PHPMailer\PHPMailer\PHPMailer;
class MailMessage {


	private $_setFrom;
	private $_addReplyTo;
	private $_addAddress;
	private $_Subject;
	private $_msgHTML;
	private $_AltBody;
	private $_addAttachment;



	public function __construct() {

		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		date_default_timezone_set('America/New_York');
	}


	/**
	 * @return mixed
	 */
	public function getSetFrom() {
		return $this->_setFrom;
	}

	/**
	 * @param mixed $setFrom
	 */
	public function setSetFrom( $setFrom ): void {
		$this->_setFrom = $setFrom;
	}

	/**
	 * @return mixed
	 */
	public function getAddReplyTo() {
		return $this->_addReplyTo;
	}

	/**
	 * @param mixed $addReplyTo
	 */
	public function setAddReplyTo( $addReplyTo ): void {
		$this->_addReplyTo = $addReplyTo;
	}

	/**
	 * @return mixed
	 */
	public function getAddAddress() {
		return $this->_addAddress;
	}

	/**
	 * @param mixed $addAddress
	 */
	public function setAddAddress( $addAddress ): void {
		$this->_addAddress = $addAddress;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->_Subject;
	}

	/**
	 * @param mixed $Subject
	 */
	public function setSubject( $Subject ): void {
		$this->_Subject = $Subject;
	}

	/**
	 * @return mixed
	 */
	public function getMsgHTML() {
		return $this->_msgHTML;
	}

	/**
	 * @param mixed $msgHTML
	 */
	public function setMsgHTML( $msgHTML ): void {
		$this->_msgHTML = $msgHTML;
	}

	/**
	 * @return mixed
	 */
	public function getAltBody() {
		return $this->_AltBody;
	}

	/**
	 * @param mixed $AltBody
	 */
	public function setAltBody( $AltBody ): void {
		$this->_AltBody = $AltBody;
	}

	/**
	 * @return mixed
	 */
	public function getAddAttachment() {
		return $this->_addAttachment;
	}

	/**
	 * @param mixed $addAttachment
	 */
	public function setAddAttachment( $addAttachment ): void {
		$this->_addAttachment = $addAttachment;
	}


//    private $_to;
//    private $_from;
//    private $_fromString;
//    private $_subjectLine;
//    private $_content;
//    private $_header;
//
//    public function __construct($params=NULL) {
//        if (is_array($params)) {
//            if (isset($params['to'])) {
//                $this->_to = $params['to'];
//            }
//            if (isset($params['subjectLine'])) {
//                $this->_subjectLine = $params['subjectLine'];
//            }
//            if (isset($params['content'])) {
//                $this->_content = $params['content'];
//            }
//            if (isset($params['from'])) {
//                $this->_from = $params['from'];
//                $this->_fromString = _("Library_No_Reply") . "<" . $this->_from . ">";
//                $this->_header = "Return-Path: " . $this->_from . "\n";
//                $this->_header .= "From:  " . $this->_from . "\n";
//                $this->_header .= "Content-Type: text/html; charset=iso-8859-1;\n";
//            }
//        }
//    }
//
//    public function getTo() {
//        return $this->_to;
//    }
//
//    public function getSubjectLine() {
//        return $this->_subjectLine;
//    }
//
//    public function getContent() {
//        return $this->_content;
//    }
//
//    public function getFrom() {
//        return $this->_fromString;
//    }
//
//    public function getHeader() {
//        return $this->_header;
//    }
//
//    public function setTo($recipientEmail) {
//        $this->_to = $recipientEmail;
//    }
//
//    public function setSubjectLine($subjectLine) {
//        $this->_subjectLine = $subjectLine;
//    }
//
//    public function setContent($contentString) {
//        $this->_content = "<html><body>" . $contentString . "<p>" . _("This is an automatically generated email. Please do not respond.") . "</p><strong>" . _("Email sent: ") . date("l F j, Y, g:i a") . "</strong></body></html>";
//    }
//
//    public function setFrom($from) {
//        $this->_fromString = "Library_No_Reply <" . $from . ">";
//        $this->_header = "Return-Path: $from\r\n";
//        $this->_header .= "From:  $this->_from\r\n";
//        $this->_header .= "Content-Type: text/html; charset=iso-8859-1;\n\n\r\n";
//    }

}
?>