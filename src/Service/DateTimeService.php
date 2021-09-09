<?php

namespace App\Service;

class DateTimeService {

    /**
     * Verifies whether a given date follows a format.
     * 
     * @param string $date
     * @param string $format
     * @return DateTime|false
     */
    public function verifyDate(string $date, string $format='Y-m-d')
    {
        $datetime = \DateTime::createFromFormat($format, $date);
        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return false;
        }

        return $datetime;
    }
}