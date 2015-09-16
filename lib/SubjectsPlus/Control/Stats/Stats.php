<?php
namespace SubjectsPlus\Control\Stats;

use SubjectsPlus\Control\Querier;

use SubjectsPlus\Control\Interfaces\OutputInterface;


class Stats implements OutputInterface {
	
	private $db; 
	private $remote_address;
	private $http_referer;
	private $query_string;
	private $date;
	private $page_title;
	private $user_agent; 
	private $subject_short_form;
	private $event_type;
	private $tab_name;
	private $link_url;
	
	
	public function __construct(Querier $db) {
		$this->db = $db; 
		$this->date = time();
	}
	
	public function getPageTitle() {
		return $this->page_title;
	}
	public function setPageTitle($page_title) {
		$this->page_title = $page_title;
		return $this;
	}
	
	public function getRemoteAddress() {
		return $this->remote_address;
	}
	public function setRemoteAddress($remote_address) {
		$this->remote_address = $remote_address;
		return $this;
	}
	public function getHttpReferer() {
		return $this->http_referer;
	}
	public function setHttpReferer($http_referer) {
		$this->http_referer = $http_referer;
		return $this;
	}
	public function getQueryString() {
		return $this->query_string;
	}
	public function setQueryString($query_string) {
		$this->query_string = $query_string;
		return $this;
	}
	public function getUserAgent() {
		return $this->user_agent;
	}
	public function setUserAgent($user_agent) {
		$this->user_agent = $user_agent;
		return $this;
	}
	public function getSubjectShortForm() {
		return $this->subject_short_form;
	}
	public function setSubjectShortForm($subject_short_form) {
		$this->subject_short_form = $subject_short_form;
		return $this;
	}
	public function getEventType() {
		return $this->event_type;
	}
	public function setEventType($event_type) {
		$this->event_type = $event_type;
		return $this;
	}
	public function getTabName() {
		return $this->tab_name;
	}
	public function setTabName($tab_name) {
		$this->tab_name = $tab_name;
		return $this;
	}
	
	public function getLinkUrl() {
		return $this->link_url;
	}
	public function setLinkUrl($link_url) {
		$this->link_url = $link_url;
		return $this;
	}
					
	
	public function loadStats($short_form) {
		$connection = $this->db->getConnection();
		$statement = $connection->prepare("SELECT * FROM stats WHERE subject_short_form = :short_form");
		$statement->bindParam(":short_form", $short_form);
		$statement->execute();
	    $stats = $statement->fetchAll();
	    
	    return json_encode((object) $stats);
		
		
	}
	
	public function saveStats() {
		
		$connection = $this->db->getConnection();
		$statement = $connection->prepare("INSERT INTO stats (http_referer, remote_address, date, page_title, user_agent, subject_short_form, event_type,tab_name,link_url) VALUES (:http_referer, :remote_address, :date, :page_title, :user_agent, :subject_short_form, :event_type, :tab_name, :link_url)");
		$statement->bindParam(":http_referer", $this->http_referer);
		$statement->bindParam(":remote_address", $this->remote_address);
		$statement->bindParam(":date", $this->date);
		$statement->bindParam(":page_title", $this->page_title);
		$statement->bindParam(":user_agent", $this->user_agent);
		$statement->bindParam(":tab_name", $this->tab_name);
		$statement->bindParam(":subject_short_form", $this->subject_short_form);
		$statement->bindParam(":event_type", $this->event_type);
		$statement->bindParam(":tab_name", $this->tab_name);
		$statement->bindParam(":link_url", $this->link_url);
		
		$statement->execute();

	}

	public function toArray() {
		return get_object_vars ( $this );
	}
	public function toJSON() {
		return json_encode ( get_object_vars ( $this ) );
	}
	
	
	
	
	
	
}