<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 1/5/16
 * Time: 1:45 PM
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;

use SubjectsPlus\Control\Interfaces\OutputInterface;

class PlusletData extends GuideBase implements OutputInterface
{

    private $db;
    private $connection;

    public $pluslet_ids;
    public $cloned_pluslets;

    public function __construct(Querier $db)
    {
        $this->db = $db;

        $this->connection = $this->db->getConnection();
    }


    public function fetchAllPluslets() {
        // Find ALL our existing pluslets from all guides
        $statement = $this->connection->prepare("SELECT DISTINCT pluslet_id, title, body, clone, type, extra, hide_titlebar,
                                                           titlebar_styling, favorite_box, target_blank_links
                                          FROM pluslet");

        $statement->execute();
        $pluslets = $statement->fetchAll();

        $this->pluslets = $pluslets;
    }

    public function fetchAllPlusletIds() {
        // Find ALL our existing pluslet ids from all guides
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT pluslet_id FROM pluslet");

        $statement->execute();
        $pluslet_ids = $statement->fetchAll();

        $this->pluslet_ids = $pluslet_ids;
    }


    public function fetchPlusletsBySubjectId($subject_id = null) {
        $connection = $this->db->getConnection ();

        $pluslets_statement = $connection->prepare ( "SELECT * FROM subject
                                INNER JOIN tab on tab.subject_id = subject.subject_id
                                INNER JOIN section on tab.tab_id = section.tab_id
                                INNER JOIN pluslet_section on section.section_id = pluslet_section.section_id
                                INNER JOIN pluslet on pluslet_section.pluslet_id = pluslet.pluslet_id
                            WHERE subject.subject_id = :subject_id" );
        $pluslets_statement->bindParam ( ":subject_id", $subject_id );
        $pluslets_statement->execute ();
        $pluslets = $pluslets_statement->fetchAll ();

        return $pluslets;
    }

    public function fetchPlusletsBySubjectIdTabId($subject_id = null, $tab_id = null) {
        $connection = $this->db->getConnection ();

        $pluslets_statement = $connection->prepare ( "SELECT * FROM subject
                                INNER JOIN tab on tab.subject_id = subject.subject_id
                                INNER JOIN section on tab.tab_id = section.tab_id
                                INNER JOIN pluslet_section on section.section_id = pluslet_section.section_id
                                INNER JOIN pluslet on pluslet_section.pluslet_id = pluslet.pluslet_id
                            WHERE subject.subject_id = :subject_id
                            AND tab.tab_id = :tab_id" );
        $pluslets_statement->bindParam ( ":subject_id", $subject_id );
        $pluslets_statement->bindParam ( ":tab_id", $tab_id );
        $pluslets_statement->execute ();
        $pluslets = $pluslets_statement->fetchAll ();

        return $pluslets;
    }

    public function fetchPlusletsBySectionId($section_id = null) {


    }

    public function fetchPlusletByPlusletId($pluslet_id = null) {


    }


    public function fetchClonedPlusletsById($master_id = null) {
        // Find ALL our existing pluslet ids from all guides
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT * FROM pluslet WHERE type like 'Clone' AND extra LIKE '%master%' AND extra like '%{$master_id}%' ");

        $statement->execute();
        $cloned_pluslets = $statement->fetchAll();

        $this->cloned_pluslets = $cloned_pluslets;
    }


    public function toArray() {
        return get_object_vars ( $this );
    }
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }
}