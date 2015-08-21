<?php
namespace SubjectsPlus\Control\Stats;

use SubjectsPlus\Control\Querier;

use SubjectsPlus\Control\Interfaces\OutputInterface;

class GuideStats implements OutputInterface {
	
	private $db;
	private $short_form;
	private $total_views;
	private $tab_names = array();	
	private $tab_clicks = 0;
	private $tab_count = 0;
	public function __construct(Querier $db) {
		$this->db = new Querier;
	}
	
	public function loadStats($short_form) {
		$this->short_form = $short_form;
		$connection = $this->db->getConnection();
		$statement = $connection->prepare("SELECT * FROM stats WHERE subject_short_form = :short_form");
		$statement->bindParam(":short_form", $this->short_form);
		$statement->execute();
		$stats = $statement->fetchAll();
			
		foreach ($stats as $stat) {
			if ($stat['event_type'] == 'view') {
		
				$this->total_views++;
	
			}
			
		
			if ($stat['event_type'] == 'tab_click') {
				
				if ($stat['tab_name']) {	
					array_push($this->tab_names,$stat['tab_name'] );
					$this->tab_clicks++;
					
					
				}
				
				
			
			
			}
			
		}	
		
			$this->tab_clicks = array_count_values($this->tab_names);
	
	}
	
	public function toArray() {
		return get_object_vars ( $this );
	}
	public function toJSON() {
		return json_encode ( get_object_vars ( $this ) );
	}
	
}