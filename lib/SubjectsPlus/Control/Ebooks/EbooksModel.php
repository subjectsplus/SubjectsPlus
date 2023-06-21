<?php

namespace SubjectsPlus\Control\Ebooks;

use SubjectsPlus\Control\Querier;

class EbooksModel
{

    private $_db;
    /**
     * @var \PDO
     */
    private $_connection;

    public function __construct(Querier $db) {
        $this->_db = $db;
        $this->_connection = $this->_db->getConnection();
    }

    public function fetchEbooksAll() {
        //$statement = $this->_connection->prepare();
    }

    public function fetchEbooksBySubjectId($subject_id) {
        $statement = $this->_connection->prepare("
        	SELECT distinct t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide, alternate_title
        	FROM title as t
        	INNER JOIN location_title as lt
        	ON t.title_id = lt.title_id
        	INNER JOIN location as l
        	ON lt.location_id = l.location_id
        	INNER JOIN restrictions as r
        	ON l.access_restrictions = r.restrictions_id
        	INNER JOIN `rank` as rk
        	ON rk.title_id = t.title_id
        	INNER JOIN source as s
        	ON rk.source_id = s.source_id
            WHERE subject_id = :subject_id
            ORDER BY newtitle");

        $statement->bindParam(':subject_id', $subject_id);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function fetchEbooksByLetter($letter) {
        $statement = $this->_connection->prepare("SELECT t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide,alternate_title
        	FROM title as t
        	INNER JOIN location_title as lt
        	ON t.title_id = lt.title_id
        	INNER JOIN location as l
        	ON lt.location_id = l.location_id
        	INNER JOIN restrictions as r
        	ON l.access_restrictions = r.restrictions_id
        	INNER JOIN `rank` as rk
        	ON rk.title_id = t.title_id
        	INNER JOIN source as s
        	ON rk.source_id = s.source_id
            WHERE left(t.title, 1) LIKE :letter
            AND l.format = 4

        UNION
        	SELECT  alternate_title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide,alternate_title
            FROM title as t
        	INNER JOIN location_title as lt
        	ON t.title_id = lt.title_id
        	INNER JOIN location as l
        	ON lt.location_id = l.location_id
        	INNER JOIN restrictions as r
        	ON l.access_restrictions = r.restrictions_id
        	INNER JOIN `rank` as rk
        	ON rk.title_id = t.title_id
        	INNER JOIN source as s
        	ON rk.source_id = s.source_id
            WHERE left(alternate_title, 1) LIKE :letter
            AND l.format = 4
            ORDER BY newtitle");

        $letter = $letter . "%";

        $statement->bindParam(":letter", $letter);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function fetchEbooksByNum() {
    }

    public function getEbooksAlphabet() {
        $sql = "SELECT distinct UCASE(left(title,1)) AS initial
                    FROM location l, location_title lt, title t
                    WHERE l.location_id = lt.location_id AND lt.title_id = t.title_id
                    AND l.format = 4  
                    AND left(title,1) REGEXP '[A-Z]'
                    ORDER BY initial";

        $statement = $this->_connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function fetchNewEbooks() {
        $sql = "SELECT title, location, access_restrictions, last_modified 
                FROM title t, location_title lt, location l 
                WHERE t.title_id = lt.title_id 
                AND l.location_id = lt.location_id 
                AND l.format = 4 
                ORDER BY t.last_modified DESC LIMIT 0,5";

        $statement = $this->_connection->prepare( $sql );
        $statement->execute();
        return $statement->fetchAll();
    }
}