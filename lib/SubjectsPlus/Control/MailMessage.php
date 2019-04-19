<?php
namespace SubjectsPlus\Control;

class MailMessage {


	private $_from;
	private $_replyTo;
	private $_address;
	private $_subject;
	private $_msgHTML;
	private $_altBody;
	private $_attachment;


	public function __construct() {

	}

	/**
	 * @return mixed
	 */
	public function getFrom() {
		return $this->_from;
	}

	/**
	 * @param mixed $from
	 */
	public function setFrom( $from ) {
		$this->_from = $from;
	}

	/**
	 * @return mixed
	 */
	public function getReplyTo() {
		return $this->_replyTo;
	}

	/**
	 * @param mixed $replyTo
	 */
	public function setReplyTo( $replyTo ) {
		$this->_replyTo = $replyTo;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->_address;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress( $address ) {
		$this->_address = $address;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->_subject;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject( $subject ) {
		$this->_subject = $subject;
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
	public function setMsgHTML( $msgHTML ) {
		$this->_msgHTML = $msgHTML;
	}

	/**
	 * @return mixed
	 */
	public function getAltBody() {
		return $this->_altBody;
	}

	/**
	 * @param mixed $altBody
	 */
	public function setAltBody( $altBody ) {
		$this->_altBody = $altBody;
	}

	/**
	 * @return mixed
	 */
	public function getAttachment() {
		return $this->_attachment;
	}

	/**
	 * @param mixed $attachment
	 */
	public function setAttachment( $attachment ) {
		$this->_attachment = $attachment;
	}



}