<?php
/**
 *   @file Location.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */

namespace SubjectsPlus\Control\AzRecord;


use SubjectsPlus\Control\Interfaces\OutputInterface;

class Location implements LocationInterface,OutputInterface
{

    private $id;
    private $format;
    private $call_number;
    private $location;
    private $access_restrictions;
    private $eres_display;
    private $display_note;
    private $helpguide;
    private $citation_guide;
    private $ctags;
    private $record_status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return mixed
     */
    public function getCallNumber()
    {
        return $this->call_number;
    }

    /**
     * @param mixed $call_number
     */
    public function setCallNumber($call_number)
    {
        $this->call_number = $call_number;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getAccessRestrictions()
    {
        return $this->access_restrictions;
    }

    /**
     * @param mixed $access_restrictions
     */
    public function setAccessRestrictions($access_restrictions)
    {
        $this->access_restrictions = $access_restrictions;
    }

    /**
     * @return mixed
     */
    public function getEresDisplay()
    {
        return $this->eres_display;
    }

    /**
     * @param mixed $eres_display
     */
    public function setEresDisplay($eres_display)
    {
        $this->eres_display = $eres_display;
    }

    /**
     * @return mixed
     */
    public function getDisplayNote()
    {
        return $this->display_note;
    }

    /**
     * @param mixed $display_note
     */
    public function setDisplayNote($display_note)
    {
        $this->display_note = $display_note;
    }

    /**
     * @return mixed
     */
    public function getHelpguide()
    {
        return $this->helpguide;
    }

    /**
     * @param mixed $helpguide
     */
    public function setHelpguide($helpguide)
    {
        $this->helpguide = $helpguide;
    }

    /**
     * @return mixed
     */
    public function getCitationGuide()
    {
        return $this->citation_guide;
    }

    /**
     * @param mixed $citation_guide
     */
    public function setCitationGuide($citation_guide)
    {
        $this->citation_guide = $citation_guide;
    }

    /**
     * @return mixed
     */
    public function getCtags()
    {
        return $this->ctags;
    }

    /**
     * @param mixed $ctags
     */
    public function setCtags($ctags)
    {
        $this->ctags = $ctags;
    }

    /**
     * @return mixed
     */
    public function getRecordStatus() {
        return $this->record_status;
    }

    /**
     * @param mixed $record_status
     */
    public function setRecordStatus($record_status) {
        $this->record_status = $record_status;
    }




    public function toArray(){
        return get_object_vars($this);
    }

    public function toJSON(){
        return json_encode(get_object_vars($this));
    }

}