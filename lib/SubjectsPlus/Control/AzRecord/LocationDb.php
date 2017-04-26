<?php
/**
 *   @file LocationDB.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;
use PDO;

class LocationDb
{
    private $last_insert;

    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();

    }

    public function insertLocation(Location $location) {
            $format = $location->getFormat();
            $call_number = $location->getCallNumber();
            $location_param = $location->getLocation();
            $access_r = $location->getAccessRestrictions();
            $eres_display = $location->getEresDisplay();
            $display_note = $location->getDisplayNote();
            $help_guide = $location->getHelpguide();
            $citation_guide = $location->getCitationGuide();
            $ctags = $location->getCtags();
            $record_status = $location->getRecordStatus();


            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $this->connection->beginTransaction();
            $statement = $this->connection->prepare("INSERT INTO location (format, call_number, location, access_restrictions, eres_display,
            display_note, helpguide, citation_guide, ctags, record_status)
            VALUES (:format, :call_number, :location, :access_restrictions, :eres_display,
            :display_note, :helpguide, :citation_guide, :ctags, :record_status)");
            $statement->bindParam(':format', $format);
            $statement->bindParam(':call_number', $call_number);
            $statement->bindParam(':location', $location_param);
            $statement->bindParam(':access_restrictions', $access_r);
            $statement->bindParam(':eres_display', $eres_display);
            $statement->bindParam(':display_note',$display_note);
            $statement->bindParam(':helpguide', $help_guide);
            $statement->bindParam(':citation_guide', $citation_guide);
            $statement->bindParam(':ctags', $ctags);
            $statement->bindParam(':record_status', $record_status);
            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            return $this->last_insert;

    }
    public function updateLocation(Location $location) {
        $format = $location->getFormat();
        $call_number = $location->getCallNumber();
        $location_param = $location->getLocation();
        $access_r = $location->getAccessRestrictions();
        $eres_display = $location->getEresDisplay();
        $display_note = $location->getDisplayNote();
        $help_guide = $location->getHelpguide();
        $citation_guide = $location->getCitationGuide();
        $ctags = $location->getCtags();
        $record_status = $location->getRecordStatus();

        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        $this->connection->beginTransaction();
        $statement = $this->connection->prepare("UPDATE location SET format = :format, call_number = :call_number, location = :location, access_restrictions = :access_restrictions, eres_display = :eres_display,
            display_note = :display_note, helpguide = :helpguide, citation_guide = :citation_guide, ctags = :ctags, record_status = :record_status WHERE location_id = :location_id");
        $statement->bindParam(':location_id', $location->getId());
        $statement->bindParam(':format', $format);
        $statement->bindParam(':call_number', $call_number);
        $statement->bindParam(':location', $location_param);
        $statement->bindParam(':access_restrictions', $access_r);
        $statement->bindParam(':eres_display', $eres_display);
        $statement->bindParam(':display_note',$display_note);
        $statement->bindParam(':helpguide', $help_guide);
        $statement->bindParam(':citation_guide', $citation_guide);
        $statement->bindParam(':ctags', $ctags);
        $statement->bindParam(':record_status', $record_status);
        $statement->execute();
        $this->connection->commit();

    }
}
