<?php
/**
 *   @file AzRecord.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;


class AzRecord
{
    private $connection;
    private $title;
    private $db;

    public function __construct(Querier $db)
    {
        $this->db = $db;
        $this->connection = $db->getConnection();

    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function toJSON() {
       return json_encode($this->title->toArray());

    }

    public function getRecord($id) {


        $this->title = $this->db->getSingleById("SELECT * FROM title WHERE title_id = :id",new TitleFactory(), $id);
        $this->getRecordLocations($id);
        $this->title->setSubjects($this->getSubjectAssociations($id));
    }

    private function getRecordLocations($id) {

        $locations = $this->db->getArrayById("SELECT * FROM location
INNER JOIN location_title ON location.location_id = location_title.location_id
INNER JOIN title ON location_title.title_id = title.title_id
WHERE title.title_id = :id", new LocationFactory(), $id);
        foreach ($locations as $location) {
            $locations_array[] = $location->toArray();
        }
        $this->title->setLocations($locations_array);

    }


    private function getSubjectAssociations($title_id) {
        $statement = $this->connection->prepare('SELECT subject_id FROM rank WHERE title_id = :title_id');
        $statement->bindParam(':title_id', $title_id);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach ($results as $result) {
            $subjects_array[] = array("subjectId" => $result['subject_id']);
        }
        return $subjects_array;
    }

}