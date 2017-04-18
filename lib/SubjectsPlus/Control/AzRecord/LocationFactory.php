<?php
/**
 *   @file LocationFactory.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */


namespace SubjectsPlus\Control\AzRecord;


class LocationFactory
{
    public static function create($location) {
        $new_location = new Location();
        $new_location->setCallNumber($location['call_number']);
        $new_location->setFormat($location['format']);
        $new_location->setLocation($location['location']);
        $new_location->setEresDisplay($location['eres_display']);
        $new_location->setAccessRestrictions($location['access_restrictions']);
        $new_location->setHelpguide($location['helpguide']);
        $new_location->setCitationGuide(  $location['citation_guide']);
        $new_location->setDisplayNote( $location['display_note']);
        $new_location->setCtags($location['ctags']);
        $new_location->setRecordStatus($location['record_status']);
        return $new_location;
    }
}