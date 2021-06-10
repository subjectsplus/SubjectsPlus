<?php

include_once("./control/includes/autoloader.php");
include_once("./control/includes/config.php");
use SubjectsPlus\Control\Querier;
$db = new Querier;
include_once("./control/includes/functions.php");

$db = new Querier;
$staff_results = $db->query('SELECT staff_id, password FROM staff');
foreach ($staff_results as $staff) {
    $password = $staff['password'];
    if (password_needs_rehash($password, PASSWORD_BCRYPT)) {
        print "Rehashing passsword for staff user " . $staff['staff_id'] . "\n";
        $new_hash = password_hash($password, PASSWORD_BCRYPT);
        $db->exec("UPDATE staff SET password = " . $db->quote($new_hash) . " WHERE staff_id = " . $db->quote($staff['staff_id']));
    }
}

?>
