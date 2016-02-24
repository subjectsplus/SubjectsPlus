<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/23/16
 * Time: 5:09 PM
 *
 * Titles are metadata for Locations, which can have multiple titles
 *
 *
 */

namespace SubjectsPlus\Control\AzRecord;


class Title
{

    public $title_id;
    public $title;
    public $alternate_title;
    public $description;
    public $pre;
    public $last_modified_by;
    public $last_modified;
    public $subjects;
    public  $locations;


    /**
     * _title constructor.
     * @param $subjects
     * @param $last_modified
     * @param $pre
     * @param $last_modified_by
     * @param $title
     * @param $alternate_title
     * @param $description
     * @param $title_id
     */
    public function __construct($title_id, $title,$alternate_title, $description,$pre , $last_modified_by, $last_modified  )
    {

        $this->last_modified = $last_modified;
        $this->pre = $pre;
        $this->last_modified_by = $last_modified_by;
        $this->title = $title;
        $this->alternate_title = $alternate_title;
        $this->description = $description;
        $this->title_id = $title_id;
    }


    public function toArray(){
        return get_object_vars($this);
    }


}