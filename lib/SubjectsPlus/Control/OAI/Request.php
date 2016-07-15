<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/14/16
 * Time: 2:45 PM
 */

namespace SubjectsPlus\Control\OAI;

class Request
{
    public $verb;
    public $queryString;
    public $identifier;
    public $params;

    public function __construct($verb, $queryString)
    {
        $this->verb = $verb;
        $this->queryString = $queryString;
        parse_str($queryString, $this->params);

    }
}