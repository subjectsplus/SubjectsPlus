<?php

include('../includes/autoloader.php');
use SubjectsPlus\Control\Autocomplete;

$auto_complete = new Autocomplete();

$auto_complete->setParam($_GET["term"]);
$auto_complete->setCollection($_GET["collection"]);
echo $auto_complete->search();
