<?php

require_once("../includes/config.php");
require_once("../includes/functions.php");

use SubjectsPlus\Control\Guide\SubjectDatabase;
use SubjectsPlus\Control\Querier;

$db = new Querier;
$objDatabases = new SubjectDatabase($db);

if (isset($_SESSION['staff_id'])) {
    $staff_id = scrubData($_SESSION['staff_id'], 'integer');
}
$result = $objDatabases->getSubjectsDropDownItems($staff_id);