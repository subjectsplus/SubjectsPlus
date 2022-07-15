<?php

namespace App\Service\Migration;

class SubjectSpecialistMigrationService {

    /**
     * Parses provided json in the extra field and converts to newer format.
     *
     * @param string $extraJson
     * @return string|false
     */
    public static function getUpdatedExtraField(string $extraJson)
    {
        $extra = json_decode($extraJson, true);
        $newExtra = [];

        foreach ($extra as $entryName => $entryValue) {
            preg_match('/^([a-zA-Z]+)(\d+)$/', $entryName, $matches);
            $fieldName = $matches[1];
            $staffId = $matches[2];

            if (!isset($newExtra[$staffId])) {
                $newExtra[$staffId] = array();
            }
            
            $newExtra[$staffId][$fieldName] = isset($entryValue[0]);

        }

        return json_encode($newExtra);
    }
}