<?php

/**
 *   @file config_bits.php
 *   @brief updating some config info
 *
 *   @author adarby
 *   @date
 *   @todo scrub post vars
 */
$subsubcat = "";
$subcat = "";
$page_title = "Record Bits include";
$header = "noshow";


include("../includes/header.php");

// Connect to database
try {
    $dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}

//print_r($_POST);

switch ($_POST["type"]) {
    case "set_css":
    // update the extra field or set a cookie or both
        $extra_stuff = "{\"css\": \"" . $_POST["css_file"] . "\"}";
        $q = "UPDATE staff SET extra = '" . mysql_real_escape_string($extra_stuff) . "' WHERE staff_id = " . $_SESSION["staff_id"];
        $r = MYSQL_QUERY($q);
        $_SESSION['css'] = $_POST["css_file"];
        print _("Background Updated!");
        break;
}
?>