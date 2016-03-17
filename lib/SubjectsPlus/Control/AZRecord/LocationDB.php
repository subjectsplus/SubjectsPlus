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
            $statement->bindParam(':format', $location->getFormat());
            $statement->bindParam(':call_number', $location->getCallNumber());
            $statement->bindParam(':location', $location->getLocation());
            $statement->bindParam(':access_restrictions', $location->getAccessRestrictions());
            $statement->bindParam(':eres_display', $location->getEresDisplay());
            $statement->bindParam(':display_note', $location->getDisplayNote());
            $statement->bindParam(':helpguide', $location->getHelpguide());
            $statement->bindParam(':citation_guide', $location->getCitationGuide());
            $statement->bindParam(':ctags', $location->getCtags());
            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            return $this->last_insert;

    }
    public function updateLocation(Location $location) {
        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        $this->connection->beginTransaction();
        $statement = $this->connection->prepare("UPDATE location SET format = :format, call_number = :call_number, location = :location, access_restrictions = :access_restrictions, eres_display = :eres_display,
            display_note = :display_note, helpguide = :helpguide, citation_guide = :citation_guide, ctags = :ctags WHERE location_id = :location_id");
        $statement->bindParam(':location_id', $location->getId());
        $statement->bindParam(':format', $location->getFormat());
        $statement->bindParam(':call_number', $location->getCallNumber());
        $statement->bindParam(':location', $location->getLocation());
        $statement->bindParam(':access_restrictions', $location->getAccessRestrictions());
        $statement->bindParam(':eres_display', $location->getEresDisplay());
        $statement->bindParam(':display_note', $location->getDisplayNote());
        $statement->bindParam(':helpguide', $location->getHelpguide());
        $statement->bindParam(':citation_guide', $location->getCitationGuide());
        $statement->bindParam(':ctags', $location->getCtags());
        $statement->execute();
        $this->connection->commit();

    }
}