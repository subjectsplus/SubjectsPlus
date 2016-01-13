<?php
/**
 *   @file GuideList.php
 *   @brief Return a list of subjects guides as an array and json
 *
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Interfaces\OutputInterface;
use SubjectsPlus\Control\Querier;

class GuideList implements OutputInterface {
	private $db;
	private $_guide_list;
	
	public function __construct(Querier $db, $type = "", $active = 1) {
		$this->db = $db;
		$this->type = $type;
		$this->active = $active;

		$connection = $this->db->getConnection();

		if ($this->type != "") {
			$statement = $connection->prepare("SELECT * FROM subject WHERE active = :active AND type = :type ORDER BY subject ");
			$statement->bindParam(":type", $this->type);
		} else {
			$statement = $connection->prepare("SELECT * FROM subject WHERE active = :active ORDER BY subject ");
		}

		$statement->bindParam(":active", $this->active);
		$statement->execute();
		$this->_guide_list = $statement->fetchAll();

	}

	public function toArray() {
		// TODO: Auto-generated method stub
		return $this->_guide_list;
	}

	public function toJSON() {
		// TODO: Auto-generated method stub
		return json_encode((object) $this->_guide_list);
	}

}
