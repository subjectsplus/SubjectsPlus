<?php
namespace SubjectsPlus\Control;


class ProblemReportService {

	private $_use_email;
	private $_use_slack;
	private $_use_recaptcha;


	public function sendCommunications( $use_email, Mailer $mailer, $use_slack, SlackMessenger $slackMsg) {


		if($use_email === true) {
			$mailer->send();
		}

		if($use_slack === true) {
			$slackMsg->send();
		}

		return true;
	}

	/**
	 * @return mixed
	 */
	public function getUseEmail() {
		return $this->_use_email;
	}

	/**
	 * @param mixed $use_email
	 */
	public function setUseEmail( $use_email ): void {
		$this->_use_email = $use_email;
	}

	/**
	 * @return mixed
	 */
	public function getUseSlack() {
		return $this->_use_slack;
	}

	/**
	 * @param mixed $use_slack
	 */
	public function setUseSlack( $use_slack ): void {
		$this->_use_slack = $use_slack;
	}

	/**
	 * @return mixed
	 */
	public function getUseRecaptcha() {
		return $this->_use_recaptcha;
	}

	/**
	 * @param mixed $use_recaptcha
	 */
	public function setUseRecaptcha( $use_recaptcha ): void {
		$this->_use_recaptcha = $use_recaptcha;
	}




}