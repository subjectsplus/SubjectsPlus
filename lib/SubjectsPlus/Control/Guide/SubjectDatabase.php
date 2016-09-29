<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 6/28/16
 * Time: 4:11 PM
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Interfaces\OutputInterface;


/**
 * Class GuideCollection
 * @package SubjectsPlus\Control\Guide
 */
class SubjectDatabase implements OutputInterface
{

    protected $db;
    protected $connection;

    public $response;
    public $collection;
    public $collections;
    public $databases;
    public $lastInsertId;
    public $shortform;

    public function __construct(Querier $db) {
        $this->db = $db;
        $this->connection = $this->db->getConnection();
    }

    /**
     *
     */
    public function fetchCollections() {
        $statement = $this->connection->prepare("SELECT c.collection_id, c.title, c.description, c.shortform 
                                                  FROM collection c 
                                                  ORDER BY c.title ASC");
        $statement->execute();
        $collections = $statement->fetchAll();

        $this->collections = $collections;
    }

    /**
     * @param $collection_id
     */
    public function fetchCollectionById($collection_id) {

        $statement = $this->connection->prepare("SELECT c.collection_id, c.title, c.description, c.shortform 
                                            FROM collection c
                                            WHERE c.collection_id = :collection_id");

        $statement->bindParam ( ":collection_id", $collection_id );
        $statement->execute();
        $collection = $statement->fetch();

        $this->collection = $collection;
    }

    /**
     * @param $title
     * @param $description
     * @param $shortform
     */
    public function createCollection($title, $description, $shortform) {
        $statement = $this->connection->prepare ( "INSERT INTO collection (`title`, `description`, `shortform`) 
                                                    VALUES (:title, :description, :shortform) " );
        $statement->bindParam ( ":title", $title );
        $statement->bindParam ( ":description", $description );
        $statement->bindParam ( ":shortform", $shortform );
        $statement->execute();

        $this->lastInsertId = $this->connection->lastInsertId();
    }

    /**
     * @param $collection_id
     */
    public function updateCollection($collection_id, $title, $description, $shortform) {
        $statement = $this->connection->prepare ( "UPDATE collection 
                                                    SET `title` = :title, 
                                                    `description` = :description, 
                                                    `shortform` = :shortform 
                                                    WHERE collection_id = :collection_id" );
        $statement->bindParam ( ":title", $title );
        $statement->bindParam ( ":description", $description );
        $statement->bindParam ( ":shortform", $shortform );
        $statement->bindParam ( ":collection_id", $collection_id );
        $statement->execute();
    }

    /**
     * @param $collection_id
     */
    public function deleteCollection($collection_id) {
        $statement = $this->connection->prepare("DELETE FROM collection WHERE collection_id = :collection_id");
        $statement->bindParam ( ":collection_id", $collection_id );
        $statement->execute();
    }

    /**
     * @param $collection_id
     */
    public function deleteCollectionGuides($collection_id) {
        //delete guides first
        $this->deleteAllGuidesFromCollection($collection_id);

        //delete collection
        $this->deleteCollection($collection_id);
    }

    public function fetchSubjectDatabases($subject_id) {
        $statement = $this->connection->prepare("SELECT r.rank_id, l.eres_display, t.title
                                                  FROM rank r, title t, location l, location_title lt
                                                  WHERE r.subject_id = :subject_id
                                                  AND lt.title_id = r.title_id
                                                  AND l.location_id = lt.location_title
                                                  ORDER BY r.sort DESC");
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $databases = $statement->fetchAll();

        $this->databases = $databases;
    }

    /**
     * @param $collectionId
     */
    public function addGuideToCollection($collection_id, $guide_id) {
        $statement = $this->connection->prepare ( "INSERT INTO collection_subject (`collection_id`, `subject_id`) 
                                                    VALUES (:collection_id, :subject_id) " );
        $statement->bindParam ( ":collection_id", $collection_id );
        $statement->bindParam ( ":subject_id", $guide_id );
        $statement->execute();

        $this->lastInsertId = $this->connection->lastInsertId();
    }

    /**
     * @param $guide_id
     * @param $collection_id
     */
    public function deleteGuideFromCollection($guide_id, $collection_id) {
        $statement = $this->connection->prepare("DELETE FROM collection_subject 
                                                  WHERE collection_id = :collection_id
                                                  AND subject_id = :guide_id");
        $statement->bindParam ( ":collection_id", $collection_id );
        $statement->bindParam ( ":guide_id", $guide_id );
        $statement->execute();
    }

    /**
     * @param $collection_id
     */
    public function deleteAllGuidesFromCollection($collection_id) {
        $statement = $this->connection->prepare("DELETE FROM collection_subject WHERE collection_id = :collection_id");
        $statement->bindParam ( ":collection_id", $collection_id );
        $statement->execute();
    }


    public function updateGuideSortOrderInCollection($sort, $rank_id) {
            // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value
            $statement = $this->connection->prepare ( "UPDATE ranl 
                                                    SET sort = :sort
                                                    WHERE rank_id = :rank_id" );
            $statement->bindParam ( ":sort", $sort );
            $statement->bindParam ( ":rank_id", $rank_id );
            $statement->execute();
    }

    public function validateShortform($shortform) {
        //shortform must be unique
        $statement = $this->connection->prepare("SELECT shortform
                                                  FROM collection
                                                  WHERE shortform = :shortform");

        $statement->bindParam(":shortform", $shortform);
        $statement->execute();
        $shortform = $statement->fetch();
        $this->shortform = $shortform;
    }


    /**
     * @return array
     */
    public function toArray() {
        return get_object_vars ( $this );
    }

    /**
     * @return string
     */
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }
}