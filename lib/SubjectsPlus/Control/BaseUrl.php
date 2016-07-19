<?php

namespace SubjectsPlus\Control;
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/19/16
 * Time: 1:49 PM
 */
class BaseUrl
{   private $baseUrl;

    public function __construct($baseUrl)
    {
        if (strpos("//", $baseUrl == 0)) {
            $baseUrl = str_replace($baseUrl, "//", "http://");
        }
        $this->baseUrl = $baseUrl;
    }

    public function __toString(){
        return $this->baseUrl;
    }


}