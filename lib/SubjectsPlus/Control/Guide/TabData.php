<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 11/10/15
 * Time: 11:55 AM
 */

namespace SubjectsPlus\Control\Guide;

use PDO;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Interfaces\OutputInterface;

class TabData implements OutputInterface
{

    private $_db;
	private $_connection;
    public $tab_ids;
    public $tab_data;
    public $tabs;
	public $last_insert;

    public function __construct(Querier $db) {
        $this->_db = $db;
	    $this->_connection = $this->_db->getConnection();
	    $this->_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    }

	public function create($subject_id, $label, $tab_index, $external_url = null, $visibility) {
		$this->_connection->beginTransaction();
		$this->last_insert = null;

		$statement = $this->_connection->prepare("INSERT INTO tab (subject_id, label, tab_index, external_url, visibility) VALUES (:subject_id, :label, :tab_index, :external_url, :visibility)");

		$statement->bindParam(':subject_id', $subject_id);
		$statement->bindParam(':label', $label);
		$statement->bindParam(':tab_index', $tab_index);
		$statement->bindParam(':external_url', $external_url);
		$statement->bindParam(':visibility', $visibility);

		$statement->execute();
		$this->last_insert = $this->_connection->lastInsertId();
		$this->_connection->commit();

		return $this->last_insert;
	}


	public function loadTabs( $status_filter = "", $subject_id = null ) {
		switch ( $status_filter ) {
			case 'hidden':
				// Find our existing tabs for this guide that is hidden
				$statement = $this->_connection->prepare( "SELECT DISTINCT tab_id, label, tab_index, external_url, visibility 
																	FROM tab 
																	WHERE subject_id = :subject_id 
																	AND visibility = 0 
																	ORDER BY tab_index" );
				break;
			case 'public':
				// Find our existing tabs for this guide that is public
				$statement = $this->_connection->prepare( "SELECT DISTINCT tab_id, label, tab_index, external_url, visibility 
																	FROM tab 
																	WHERE subject_id = :subject_id 
																    AND visibility = 1 
																	ORDER BY tab_index" );
				break;
			default:
				// Find ALL our existing tabs for this guide
				$statement = $this->_connection->prepare( "SELECT DISTINCT tab_id, label, tab_index, external_url, visibility 
																	FROM tab 
																	WHERE subject_id = :subject_id 
																	ORDER BY tab_index" );
				break;
		}

		$statement->bindParam( ":subject_id", $subject_id );
		$statement->execute();
		$tabs = $statement->fetchAll();

		$this->tabs = $tabs;
	}

    public function saveTabOrder($data) {
        if(isset($data)) {
            parse_str($data['data'], $str);

            $tabs = $str['item'];
            foreach($tabs as $key => $value) {
                $q = "UPDATE tab SET tab_index =" . $this->_db->quote(scrubData($key) ). " WHERE tab_id = " . $value;
	            $this->_db->exec($q);
            }
        }
    }


    public function fetchTabIdsBySubjectId($subject_id) {
        $statement = $this->_connection->prepare("SELECT tab_id FROM tab WHERE subject_id = :subject_id");
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $tab_ids = $statement->fetchAll();
        $this->tab_ids = $tab_ids;
    }

	public function fetchTabDataBySubjectId($subject_id) {
		$statement = $this->_connection->prepare("SELECT * FROM tab WHERE subject_id = :subject_id");
		$statement->bindParam ( ":subject_id", $subject_id );
		$statement->execute();
		$tabs = $statement->fetchAll();
		$this->tab_data = $tabs;
	}


    public function toArray() {
        return get_object_vars ( $this );
    }
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }

}