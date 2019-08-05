<?php
namespace SubjectsPlus\Control;

class MailMessage {


	private $_fromAddress;
	private $_fromLabel;
	private $_replyToAddress;
	private $_replyToLabel;
	private $_toAddress;
	private $_toAddressLabel;
	private $_toCcAddresses = array();
	private $_toBccAddresses = array();
	private $_subject;
	private $_msgHTML;
	private $_altBody;
	private $_attachment;


	public function __construct() {

	}

	/**
	 * @return mixed
	 */
	public function getFromAddress() {
		return $this->_fromAddress;
	}

	/**
	 * @param mixed $fromAddress
	 */
	public function setFromAddress( $fromAddress ){
		$this->_fromAddress = $fromAddress;
	}

	/**
	 * @return array
	 */
	public function getToCcAddresses() {
		return $this->_toCcAddresses;
	}

	/**
	 * @param array $toCcAddresses
	 */
	public function setToCcAddresses( array $toCcAddresses ) {
		$this->_toCcAddresses = $toCcAddresses;
	}

	/**
	 * @return array
	 */
	public function getToBccAddresses() {
		return $this->_toBccAddresses;
	}

	/**
	 * @param array $toBccAddresses
	 */
	public function setToBccAddresses( array $toBccAddresses ){
		$this->_toBccAddresses = $toBccAddresses;
	}

	/**
	 * @return mixed
	 */
	public function getFromLabel() {
		return $this->_fromLabel;
	}

	/**
	 * @param mixed $fromLabel
	 */
	public function setFromLabel( $fromLabel ){
		$this->_fromLabel = $fromLabel;
	}

	/**
	 * @return mixed
	 */
	public function getReplyToAddress() {
		return $this->_replyToAddress;
	}

	/**
	 * @param mixed $replyToAddress
	 */
	public function setReplyToAddress( $replyToAddress ){
		$this->_replyToAddress = $replyToAddress;
	}

	/**
	 * @return mixed
	 */
	public function getReplyToLabel() {
		return $this->_replyToLabel;
	}

	/**
	 * @param mixed $replyToLabel
	 */
	public function setReplyToLabel( $replyToLabel ){
		$this->_replyToLabel = $replyToLabel;
	}

	/**
	 * @return mixed
	 */
	public function getToAddress() {
		return $this->_toAddress;
	}

	/**
	 * @param mixed $toAddress
	 */
	public function setToAddress( $toAddress ){
		$this->_toAddress = $toAddress;
	}

	/**
	 * @return mixed
	 */
	public function getToAddressLabel() {
		return $this->_toAddressLabel;
	}

	/**
	 * @param mixed $toAddressLabel
	 */
	public function setToAddressLabel( $toAddressLabel ){
		$this->_toAddressLabel = $toAddressLabel;
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