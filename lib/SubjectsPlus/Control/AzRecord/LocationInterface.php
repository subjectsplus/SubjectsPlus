<?php
/**
 *   @file LocationInterface.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */


namespace SubjectsPlus\Control\AzRecord;


interface LocationInterface
{
    /**
     * @return mixed
     */
    public function getId();


    /**
     * @param mixed $id
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getFormat();


    /**
     * @param mixed $format
     */
    public function setFormat($format);

    /**
     * @return mixed
     */
    public function getCallNumber();


    /**
     * @param mixed $call_number
     */
    public function setCallNumber($call_number);


    /**
     * @return mixed
     */
    public function getLocation();

    /**
     * @param mixed $location
     */
    public function setLocation($location);

    /**
     * @return mixed
     */
    public function getAccessRestrictions();


    /**
     * @param mixed $access_restrictions
     */
    public function setAccessRestrictions($access_restrictions);


    /**
     * @return mixed
     */
    public function getEresDisplay();

    /**
     * @param mixed $eres_display
     */
    public function setEresDisplay($eres_display);


    /**
     * @return mixed
     */
    public function getDisplayNote();


    /**
     * @param mixed $display_note
     */
    public function setDisplayNote($display_note);


    /**
     * @return mixed
     */
    public function getHelpguide();


    /**
     * @param mixed $helpguide
     */
    public function setHelpguide($helpguide);


    /**
     * @return mixed
     */
    public function getCitationGuide();


    /**
     * @param mixed $citation_guide
     */
    public function setCitationGuide($citation_guide);


    /**
     * @return mixed
     */
    public function getCtags();


    /**
     * @param mixed $ctags
     */
    public function setCtags($ctags);


}