<?php
namespace SubjectsPlus\Control;

class TalkbackComment {

	private $_question;
	private $_q_from;
	private $_date_submitted;
	private $_display;
	private $_answer;
	private $_tbtags;

	public function __construct( $params = array()) {

		if ( (is_array($params)) && (!empty($params)) ) {
			if (isset($params['question'])) {
				$this->_question = $params['question'];
			}

			if (isset($params['q_from'])) {
				$this->_q_from = $params['q_from'];
			}

			if (isset($params['date_submitted'])) {
				$this->_date_submitted = $params['date_submitted'];
			}

			if (isset($params['display'])) {
				$this->_display = $params['display'];
			}

			if (isset($params['answer'])) {
				$this->_answer = $params['answer'];
			}

			if (isset($params['tbtags'])) {
				$this->_tbtags = $params['tbtags'];
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getQuestion() {
		return $this->_question;
	}

	/**
	 * @param mixed $question
	 */
	public function setQuestion( $question ) {
		$this->_question = $question;
	}

	/**
	 * @return mixed
	 */
	public function getQFrom() {
		return $this->_q_from;
	}

	/**
	 * @param mixed $q_from
	 */
	public function setQFrom( $q_from ) {
		$this->_q_from = $q_from;
	}

	/**
	 * @return mixed
	 */
	public function getDateSubmitted() {
		return $this->_date_submitted;
	}

	/**
	 * @param mixed $date_submitted
	 */
	public function setDateSubmitted( $date_submitted ) {
		$this->_date_submitted = $date_submitted;
	}

	/**
	 * @return mixed
	 */
	public function getDisplay() {
		return $this->_display;
	}

	/**
	 * @param mixed $display
	 */
	public function setDisplay( $display ) {
		$this->_display = $display;
	}

	/**
	 * @return mixed
	 */
	public function getAnswer() {
		return $this->_answer;
	}

	/**
	 * @param mixed $answer
	 */
	public function setAnswer( $answer ) {
		$this->_answer = $answer;
	}

	/**
	 * @return mixed
	 */
	public function getTbtags() {
		return $this->_tbtags;
	}

	/**
	 * @param mixed $tbtags
	 */
	public function setTbtags( $tbtags ) {
		$this->_tbtags = $tbtags;
	}

}