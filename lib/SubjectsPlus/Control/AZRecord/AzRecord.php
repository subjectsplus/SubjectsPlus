<?php
namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;

/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/23/16
 * Time: 4:06 PM
 *
 * An AZRecord is a combination of the location, title, and rank tables
 *
 */
class AzRecord
{
    private $connection;
    private $title;

    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();

    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function toJSON() {
       return json_encode($this->title->toArray());

    }

    public function getRecord($id) {

        $title_db = new AzRecordDb($this->connection);
        $this->title = $title_db->getSingleById("SELECT * FROM title WHERE title_id = :id",new TitleFactory(), $id);
        $this->getRecordLocations($id);
        $this->title->subjects = $this->getSubjectAssociations($id);
    }

    private function getRecordLocations($id) {

        $locations_db = new AzRecordDb($this->connection);
        $locations = $locations_db->getArrayById("SELECT * FROM location
INNER JOIN location_title ON location.location_id = location_title.location_id
INNER JOIN title ON location_title.title_id = title.title_id
WHERE title.title_id = :id", new LocationFactory(), $id);
        foreach ($locations as $location) {
            $this->title->locations[] = $location->toArray();
        }

    }


    private function getSubjectAssociations($title_id) {
        $statement = $this->connection->prepare('SELECT subject_id FROM rank WHERE title_id = :title_id');
        $statement->bindParam(':title_id', $title_id);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach ($results as $result) {
            $subjects[] = array("subjectId" => $result['subject_id']);
        }
        return $subjects;
    }

}