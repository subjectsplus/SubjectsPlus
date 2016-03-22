<?php
/**
 *   @file TitleInterface.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */

namespace SubjectsPlus\Control\AzRecord;


interface TitleInterface
{
    /**
     * @return mixed
     */
    public function getTitleId();


    /**
     * @param mixed $title_id
     */
    public function setTitleId($title_id);


    /**
     * @return mixed
     */
    public function getTitle();


    /**
     * @param mixed $title
     */
    public function setTitle($title);

    /**
     * @return mixed
     */
    public function getAlternateTitle();


    /**
     * @param mixed $alternate_title
     */
    public function setAlternateTitle($alternate_title);

    /**
     * @return mixed
     */
    public function getDescription();


    /**
     * @param mixed $description
     */
    public function setDescription($description);

    /**
     * @return mixed
     */
    public function getPre();

    /**
     * @param mixed $pre
     */
    public function setPre($pre);

    /**
     * @return mixed
     */
    public function getLastModifiedBy();


    /**
     * @param mixed $last_modified_by
     */
    public function setLastModifiedBy($last_modified_by);


    /**
     * @return mixed
     */
    public function getLastModified();


    /**
     * @param mixed $last_modified
     */
    public function setLastModified($last_modified);

    /**
     * @return mixed
     */
    public function getLocations();


    /**
     * @param mixed $locations
     */
    public function setLocations($locations);


    /**
     * @return mixed
     */
    public function getSubjects();

    /**
     * @param mixed $subjects
     */
    public function setSubjects($subjects);


}