<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-02-13
 * Time: 14:45
 */

namespace SubjectsPlus\Control\Guide;

use PDO;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Interfaces\OutputInterface;

class SectionService implements OutputInterface {

	private $_db;
	private $_connection;
	public $section_ids;
	public $last_insert;


	public function __construct(Querier $db) {
		$this->_db = $db;
		$this->_connection = $this->_db->getConnection();
		$this->_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$this->_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}

	public function create($section_index, $layout, $tab_id) {

		$this->_connection->beginTransaction();
		$this->last_insert = null;

		$statement = $this->_connection->prepare("INSERT INTO section (section_index, layout, tab_id) VALUES (:section_index, :layout, :tab_id)");

		$statement->bindParam(':section_index', $section_index);
		$statement->bindParam(':layout', $layout);
		$statement->bindParam(':tab_id', $tab_id);

		$statement->execute();
		$this->last_insert = $this->_connection->lastInsertId();
		$this->_connection->commit();

		return $this->last_insert;
	}


	public function updateSectionLayout($section_id, $layout) {
		$this->_connection->beginTransaction();
		$statement = $this->_connection->prepare("UPDATE section SET layout = :layout WHERE section_id = :section_id");
		$statement->bindParam(':section_id', $section_id);
		$statement->bindParam(':layout', $layout);
		$statement->execute();
		return $this->_connection->commit();
	}

	public function fetchSectionIdsBySubjectId( $subject_id ) {
		$statement = $this->_connection->prepare( "SELECT * FROM section
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