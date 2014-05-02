<?php

include("includes/header.php");

use SubjectsPlus\Control\Search;
$search = new Search;
$search->setSearch('test');
print_r($search->getResults());


