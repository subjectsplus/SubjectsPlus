<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Autocomplete;

include("../../control/includes/autoloader.php");

$auto_complete = new Autocomplete();
$auto_complete->setSearchPage("public");
$auto_complete->setParam($_GET["term"]);
$auto_complete->setCollection($_GET["collection"]);
echo $auto_complete->search();

