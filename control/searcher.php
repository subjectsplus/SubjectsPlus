<?php

include("includes/autoloader.php");

use SubjectsPlus\Control\Search;
$search = new Search;
$search->setSearch($_GET['q']);
echo json_encode(($search->getResults()));


