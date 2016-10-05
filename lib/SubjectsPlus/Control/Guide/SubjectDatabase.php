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

    public function saveChanges($title_id, $subject_id, $description_override) {
        $rank_id = $this->getRankId($subject_id, $title_id);

        if ($rank_id) {
            $this->updateDescriptionOverride($rank_id, $description_override);
        }else{
            $this->insert($title_id, $subject_id, $description_override);
        }
    }

    function insert ($title_id, $subject_id, $description_override){
        $statement = $this->connection->prepare ("INSERT INTO rank (rank, subject_id, title_id, source_id, description_override, dbbysub_active)
VALUES (0,:subject_id,:title_id, 1, :description_override, 1)");
        $statement->bindParam ( ":description_override", $description_override );
        $statement->bindParam ( ":title_id", $title_id );
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
    }

    function updateDescriptionOverride ($rank_id, $description_override){
        $statement = $this->connection->prepare ( "UPDATE rank
                SET description_override = :description_override, dbbysub_active = 1
                WHERE rank_id = :rank_id"
        );
        $statement->bindParam ( ":description_override", $description_override );
        $statement->bindParam ( ":rank_id", $rank_id );
        $statement->execute();
    }

    function getRankId($subject_id, $title_id) {
        $statement = $this->connection->prepare("SELECT rank_id FROM rank
                    WHERE subject_id = :subject_id
                    AND title_id = :title_id"
        );
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->bindParam ( ":title_id", $title_id );
        $statement->execute();
        $statement = $statement->fetchAll();
        return $statement[0]['rank_id'];
    }

    public function fetchSubjectDatabases($subject_id) {
        $statement = $this->connection->prepare("SELECT t.title, l.record_status, r.title_id, r.rank_id, r.description_override, r.rank_id
FROM rank r, location_title lt, location l, title t
    WHERE r.subject_id = :subject_id
    AND lt.title_id = r.title_id
    AND l.location_id = lt.location_id
    AND t.title_id = lt.title_id
    AND r.dbbysub_active = 1");

        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
        $databases = $statement->fetchAll();

        $this->databases = $databases;
    }

    public function hideDatabaseFromGuide($rank_id, $dbbysub_active) {
        $statement = $this->connection->prepare("UPDATE rank SET dbbysub_active = 0  WHERE rank_id = :rank_id");
        $statement->bindParam ( ":rank_id", $rank_id );
        $statement->execute();
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