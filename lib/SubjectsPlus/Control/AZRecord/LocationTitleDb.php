<?php
/**
 *   @file LocationTitleDb.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;
use PDO;
class LocationTitleDb
{
    private $location_id;
    private $title_id;


    public function __construct($location_id, $title_id, Querier $db)
    {
        $this->location_id = $location_id;
        $this->title_id = $title_id;
        $this->connection = $db->getConnection();

    }

    public function insertLocationTitle() {
        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );


            $this->connection->beginTransaction();
            $statement = $this->connection->prepare("INSERT INTO location_title (location_id, title_id) VALUES (:location_id,:title_id)");
            $statement->bindParam(':location_id', $this->location_id);
            $statement->bindParam(':title_id', $this->title_id);

            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            return $this->last_insert;
    }
}