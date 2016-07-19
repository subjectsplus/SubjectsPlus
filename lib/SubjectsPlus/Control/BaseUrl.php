<?php

namespace SubjectsPlus\Control;
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/19/16
 * Time: 1:49 PM
 */
class BaseUrl
{
    public function __construct($baseUrl) {
        if (str_pos("//",$baseUrl == 0)) {
            $baseUrl = str_replace("//","http://");
        }
        return $baseUrl;
    }

}