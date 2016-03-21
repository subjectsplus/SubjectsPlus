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

use SubjectsPlus\Control\Interfaces\OutputInterface;

class Title implements TitleInterface,OutputInterface
{
    private $title_id;
    private $title;
    private $alternate_title;
    private $description;
    private $pre;
    private $last_modified_by;
    private $last_modified;
    private $locations;
    private $subjects;

    /**
     * @return mixed
     */
    public function getTitleId()
    {
        return $this->title_id;
    }

    /**
     * @param mixed $title_id
     */
    public function setTitleId($title_id)
    {
        $this->title_id = $title_id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getAlternateTitle()
    {
        return $this->alternate_title;
    }

    /**
     * @param mixed $alternate_title
     */
    public function setAlternateTitle($alternate_title)
    {
        $this->alternate_title = $alternate_title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPre()
    {
        return $this->pre;
    }

    /**
     * @param mixed $pre
     */
    public function setPre($pre)
    {
        $this->pre = $pre;
    }

    /**
     * @return mixed
     */
    public function getLastModifiedBy()
    {
        return $this->last_modified_by;
    }

    /**
     * @param mixed $last_modified_by
     */
    public function setLastModifiedBy($last_modified_by)
    {
        $this->last_modified_by = $last_modified_by;
    }

    /**
     * @return mixed
     */
    public function getLastModified()
    {
        return $this->last_modified;
    }

    /**
     * @param mixed $last_modified
     */
    public function setLastModified($last_modified)
    {
        $this->last_modified = $last_modified;
    }

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return mixed
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param mixed $subjects
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
    }

    public function toArray(){
        return get_object_vars($this);
    }

    public function toJSON(){
        return get_object_vars($this);
    }
}