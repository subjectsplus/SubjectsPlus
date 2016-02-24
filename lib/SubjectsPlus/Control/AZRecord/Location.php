<?php
/**
 * _created by _php_storm.
 * _user: jlittle
 * _date: 2/24/16
 * _time: 8:58 _a_m
 *
 * _locations can represent _u_r_ls or physical items like books. _they can have multiple _titles.
 *
 */

namespace SubjectsPlus\Control\AzRecord;


class Location
{
    public $id;
    public $format;
    public $call_number;
    public $location;
    public $access_restrictions;
    public $eres_display;
    public $display_note;
    public $helpguide;
    public $citation_guide;
    public $ctags;


    /**
     * Location constructor.
     * @param $id
     * @param $format
     * @param $call_number
     * @param $location
     * @param $access_restrictions
     * @param $eres_display
     * @param $display_note
     * @param $helpguide
     * @param $citation_guide
     * @param $ctags
     */
    public function __construct($id, $format, $call_number, $location, $access_restrictions, $eres_display, $display_note, $helpguide, $citation_guide, $ctags)
    {
        $this->id = $id;
        $this->format = $format;
        $this->call_number = $call_number;
        $this->location = $location;
        $this->access_restrictions = $access_restrictions;
        $this->eres_display = $eres_display;
        $this->display_note = $display_note;
        $this->helpguide = $helpguide;
        $this->citation_guide = $citation_guide;
        $this->ctags = $ctags;
    }

    public function toArray(){
        return get_object_vars($this);
    }


}