<?php
namespace SubjectsPlus\Control;

use ReCaptcha\ReCaptcha;

class ReCaptchaService {

	private $_action;
	private $_token;
	private $_server_name;
	private $_remote_addr;

	public function __construct() {
	}


	/**
	 * @param $secret
	 *
	 * @return \ReCaptcha\Response
	 */
	public function verify($secret) {
		$recaptcha = new ReCaptcha($secret);
		return $recaptcha->setExpectedHostname($this->getServerName())
		                  ->setExpectedAction($this->getAction())
		                  ->setScoreThreshold(0.5)
		                  ->verify($this->getToken(), $this->getRemoteAddr());
	}

	/**
	 * @return mixed
	 */
	public function getAction() {
		return $this->_action;
	}

	/**
	 * @param mixed $action
	 */
	public function setAction( $action ) {
		$this->_action = $action;
	}

	/**
	 * @return mixed
	 */
	public function getToken() {
		return $this->_token;
	}

	/**
	 * @param mixed $token
	 */
	public function setToken( $token ) {
		$this->_token = $token;
	}

	/**
	 * @return mixed
	 */
	public function getServerName() {
		return $this->_server_name;
	}

	/**
	 * @param mixed $server_name
	 */
	public function setServerName( $server_name ) {
		$this->_server_name = $server_name;
	}

	/**
	 * @return mixed
	 */
	public function getRemoteAddr() {
		return $this->_remote_addr;
	}

	/**
	 * @param mixed $remote_addr
	 */
	public function setRemoteAddr( $remote_addr ) {
		$this->_remote_addr = $remote_addr;
	}



}