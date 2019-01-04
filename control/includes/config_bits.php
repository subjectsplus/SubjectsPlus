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


//print_r($_POST);

switch ($_POST["type"]) {
    case "set_css":
    // update the extra field or set a cookie or both
        $extra_stuff = "{\"css\": \"" . $_POST["css_file"] . "\"}";
        $q = "UPDATE staff SET extra = " . $db->quote($extra_stuff) . " WHERE staff_id = " . $_SESSION["staff_id"];
        $r = $db->query($q);
        $_SESSION['css'] = $_POST["css_file"];
        print "<div class=\"feedback\" style=\"display: block;\">Background Updated</div>";
        break;
}
