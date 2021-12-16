<?php

namespace App\Service;

class UtilityService {

    public static function cleanString(string $str, $strip_new_lines = true) {
        if ($strip_new_lines) {
            $str = str_replace(["\r\n", "\r", "\n"], "", $str);
        }

        return html_entity_decode(trim(strip_tags($str)));
    }
}