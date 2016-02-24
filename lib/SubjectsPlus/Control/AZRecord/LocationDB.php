<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/24/16
 * Time: 12:08 PM
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;
use PDO;

class LocationDB
{
    private $last_insert;

    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();

    }

    public function insertLocation(Location $location) {
        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );


            $this->connection->beginTransaction();
            $statement = $this->connection->prepare("INSERT INTO location (format, call_number, location, access_restrictions, eres_display,
            display_note, helpguide, citation_guide, ctags)
            VALUES (:format, :call_number, :location, :access_restrictions, :eres_display,
            :display_note, :helpguide, :citation_guide, :ctags)");
            $statement->bindParam(':format', $location->format);
            $statement->bindParam(':call_number', $location->call_number);
            $statement->bindParam(':location', $location->location);
            $statement->bindParam(':access_restrictions', $location->access_restrictions);
            $statement->bindParam(':eres_display', $location->eres_display);
            $statement->bindParam(':display_note', $location->display_note);
            $statement->bindParam(':helpguide', $location->helpguide);
            $statement->bindParam(':citation_guide', $location->citation_guide);
            $statement->bindParam(':ctags', $location->ctags);
            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            return $this->last_insert;

    }
}