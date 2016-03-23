<?php
/**
 *   @file TitleFactory.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
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
        $my_title->setDescription($title['description']);
        $my_title->setLastModifiedBy($title['last_modified_by']);
        $my_title->setLastModified($title['last_modified']);
        $my_title->setSubjects($title['subjects']);
        return $my_title;

    }
}