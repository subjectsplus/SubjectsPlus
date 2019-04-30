<?php
namespace SubjectsPlus\Control;

class TalkbackService {

	private $_db;
	private $_connection;
	private $_use_email;
	private $_use_slack;
	private $_use_recaptcha;
	private $_smtp_address;
	private $_admin_email;


	public function __construct(Querier $db) {
		$this->_db = $db;
		$this->_connection = $this->_db->getConnection();
	}

	/**
	 * @param TalkbackComment $comment
	 */
	public function insertComment(TalkbackComment $comment) {

		$this_comment  = $comment->getQuestion();
		$this_name     = $comment->getQFrom();
		$todaycomputer = $comment->getDateSubmitted();
		$set_filter    = $comment->getTbtags();
		$display       = $comment->getDisplay();
		$answer        = $comment->getAnswer();

		$statement = $this->_connection->prepare( "INSERT INTO talkback (question, q_from, date_submitted, display, tbtags, answer)
			VALUES (:question, :q_from, :date_submitted, :display, :tbtags, :answer)" );

		$statement->bindParam( ":question", $this_comment );
		$statement->bindParam( ":q_from", $this_name );
		$statement->bindParam( ":date_submitted", $todaycomputer );
		$statement->bindParam( ":tbtags", $set_filter );
		$statement->bindParam( ":display", $display );
		$statement->bindParam( ":answer", $answer );

		$statement->execute();
	}

	/**
	 * @param string $comment_year
	 * @param $this_year
	 * @param $filter
	 * @param $cat_tags
	 *
	 * @return array
	 */
	public function getComments($comment_year = 'current', $this_year, $filter, $cat_tags) {

		if($comment_year == 'current') {
			$operator = '>=';
		} elseif($comment_year == 'prev') {
			$operator = '<';
		} else {
			$operator = '=';
		}

		$statement  = $this->_connection->prepare( "SELECT talkback_id, question, q_from, date_submitted, DATE_FORMAT(date_submitted, '%b %d %Y') as thedate,
	answer, a_from, fname, lname, email, staff.title, YEAR(date_submitted) as theyear
	FROM talkback LEFT JOIN staff
	ON talkback.a_from = staff.staff_id
	WHERE (display ='1' OR display ='Yes')
    AND tbtags LIKE :tbtags
	AND cattags LIKE :ctags
	AND YEAR(date_submitted) {$operator} :year
	ORDER BY date_submitted DESC" );

		$statement->bindParam( ":year", $this_year );
		//AND tbtags LIKE :tbtags
		$statement->bindParam( ":tbtags", $filter );
		$statement->bindParam( ":ctags", $cat_tags );
		$statement->execute();

		return $statement->fetchAll();
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
	public function setUseEmail( $use_email ) {
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
	public function setUseSlack( $use_slack ) {
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
	public function setUseRecaptcha( $use_recaptcha ) {
		$this->_use_recaptcha = $use_recaptcha;
	}

	/**
	 * @return mixed
	 */
	public function getSmtpAddress() {
		return $this->_smtp_address;
	}

	/**
	 * @param mixed $smtp_address
	 */
	public function setSmtpAddress( $smtp_address ) {
		$this->_smtp_address = $smtp_address;
	}

	/**
	 * @return mixed
	 */
	public function getAdminEmail() {
		return $this->_admin_email;
	}

	/**
	 * @param mixed $admin_email
	 */
	public function setAdminEmail( $admin_email ) {
		$this->_admin_email = $admin_email;
	}








}