<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/24/16
 * Time: 10:02 AM
 */

namespace SubjectsPlus\Control\AzRecord;


class TitleFactory
{
    public static function create($title) {
        return new Title($title['title_id'], $title['title'], $title['alternate_title'], $title['description'], $title['pre'],
            $title['last_modified_by'], $title['last_modified'],$title['subjects']);
    }
}