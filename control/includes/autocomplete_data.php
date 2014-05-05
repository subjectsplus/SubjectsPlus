<?php

include('../includes/autoloader.php');
use SubjectsPlus\Control\Autocomplete;
use SubjectsPlus\Control\Search;

$auto_complete = new Autocomplete();

$auto_complete->setParam($_GET["term"]);
$auto_complete->setCollection($_GET["collection"]);
if (isset($_GET["subject_id"])) {

$auto_complete->setSubjectId($_GET["subject_id"]);

}
echo $auto_complete->search();
use
