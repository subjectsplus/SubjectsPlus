<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/24/16
 * Time: 9:52 AM
 */

namespace SubjectsPlus\Control\AzRecord;


class LocationFactory
{
    public static function create($location) {
        return new Location($location['location_id'],
            $location['format'],
            $location['call_number'],
            $location['location'],
            $location['access_restrictions'],
            $location['eres_display'],
            $location['display_note'],
            $location['helpguide'],
            $location['citation_guide'],
            $location['ctags']);
    }
}