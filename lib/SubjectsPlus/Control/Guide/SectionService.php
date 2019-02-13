<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-02-13
 * Time: 14:45
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Interfaces\OutputInterface;

class SectionService implements OutputInterface {

	private $db;
	public $section_ids;


	public function __construct(Querier $db) {
		$this->db = $db;
	}


	public function fetchSectionIdsBySubjectId( $subject_id ) {
		$connection = $this->db->getConnection();
		$statement = $connection->prepare( "SELECT * FROM section
    											INNER JOIN tab on section.tab_id = tab.tab_id
											    INNER JOIN subject on tab.subject_id = subject.subject_id
												WHERE tab.subject_id = :subject_id" );
		$statement->bindParam( ":subject_id", $subject_id );
		$statement->execute();
		$section_ids        = $statement->fetchAll();
		$this->section_ids = $section_ids;

	}


	public function toArray() {
		return get_object_vars( $this );
	}

	public function toJSON() {
		return json_encode( get_object_vars( $this ) );
	}

}