<?php
/**
 *   @file Title.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Interfaces\OutputInterface;

/*
 * testing deploy.sh
 */

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
        $re = "/\\b(the|a|an|la|les|el|las|los)+[[:space:]]/i";
        preg_match($re, $title, $matches);

        if (isset($matches[0])) {
            $this->pre = $matches[0];
            $title_array = explode(" ",$title);
            if (isset($title_array[0])) {
                if ($title_array[0] == trim($matches[0])) {
                    $title_array[0] = "";
                }
                $title = trim(implode(" ",$title_array));
            }
        }

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