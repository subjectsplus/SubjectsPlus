<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 3/28/16
 * Time: 7:56 AM
 */

namespace SubjectsPlus\Control;


interface PlusletInterface
{
     function getMenuIcon();
     function getMenuName();
     function onEditOutput();
     function onViewOutput();
}