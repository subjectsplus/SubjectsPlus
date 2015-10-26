<?php
namespace SubjectsPlus\Control\Databases;
/**
 *   @file AzList.php
 *   @brief 
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */

use SubjectsPlus\Control\Querier;
use PDO;
use SubjectsPlus\Control\Interfaces\OutputInterface;

class AzList implements OutputInterface {
	protected $db;
	protected $connection;
	protected $default_fetch;
		
	private $all_az;
	
	
	public function __construct(Querier $db) {
	
		$this->db = $db;
		$this->connection = $this->db->getConnection ();
		$this->default_fetch = PDO::FETCH_ASSOC;	
	
	}
	public function toArray() {
		$statement = $this->connection->prepare ( "		
		
				SELECT DISTINCT LEFT(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide,rk.subject_id
        	FROM title as t
        	INNER JOIN location_title as lt
        	ON t.title_id = lt.title_id
        	INNER JOIN location as l
        	ON lt.location_id = l.location_id
        	INNER JOIN restrictions as r
        	ON l.access_restrictions = r.restrictions_id
        	INNER JOIN rank as rk
        	ON rk.title_id = t.title_id
        	INNER JOIN source as s
        	ON rk.source_id = s.source_id
        	AND eres_display = 'Y'
        UNION
        	SELECT DISTINCT LEFT(alternate_title,1) as initial, alternate_title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide, rk.subject_id
            FROM title as t
        	INNER JOIN location_title as lt
        	ON t.title_id = lt.title_id
        	INNER JOIN location as l
        	ON lt.location_id = l.location_id
        	INNER JOIN restrictions as r
        	ON l.access_restrictions = r.restrictions_id
        	INNER JOIN rank as rk
        	ON rk.title_id = t.title_id
        	INNER JOIN source as s
        	ON rk.source_id = s.source_id
     
        	AND eres_display = 'Y'
			WHERE alternate_title != NULL
					
			ORDER BY newtitle
				
				");

		$statement->execute ();
		$this->all_az = $statement->fetchAll ( $this->default_fetch );
		return $this->all_az;
		
	}
	public function toJSON() {
		return json_encode ( self::outputArray ());
	
	}
	
	
}