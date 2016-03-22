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
        $my_title = new Title();
        $my_title->setTitleId($title['title_id']);
        $my_title->setPre($title['pre']);
        $my_title->setTitle($title['title']);
        $my_title->setAlternateTitle($title['alternate_title']);
        $my_title->setDescription($title['description']);t
        $my_title->setLastModifiedBy($title['last_modified_by']);
        $my_title->setLastModified($title['last_modified']);

        return $my_title;

    }
}