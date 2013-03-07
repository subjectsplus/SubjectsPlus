<?php

/**
 *   @file save_guide.php
 *   @brief Save the contents of the guide.
 *   @description Called by guide.js, which passes in an array of all the pluslets for a guide
 *   and their position within the page (i.e., left, center or right column + row number).  The existing
 *   entries in the intervening pluslet_subject table are emptied out, and new ones are added.
 *
 *   @author agdarby
 *   @date updated dec 2012
 */

$subsubcat = "";
$subcat = "guides";
$page_title = "Save Guides include";
$header = "noshow";


include("../../includes/header.php");

//print_r($_POST);

// Connect to database
try {$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}

$left_col = $_POST["left_data"];
$center_col = $_POST["center_data"];
$sidebar = $_POST["sidebar_data"];
$subject_id = $_POST["this_subject_id"];

//added by dgonzalez in order to separate by '&pluslet[]=' even if dropspot-left doesn't exist
$left_col = "&" . $left_col;
$center_col = "&" . $center_col;
$sidebar = "&" . $sidebar;

// remove the "drop here" non-content & get all our "real" contents into array
$left_col = str_replace("dropspot-left[]=1", "", $left_col);
$leftconts = explode("&pluslet[]=", $left_col);

$center_col = str_replace("dropspot-center[]=1", "", $center_col);
$centerconts = explode("&pluslet[]=", $center_col);

$sidebar = str_replace("dropspot-sidebar[]=1", "", $sidebar);
$sidebarconts = explode("&pluslet[]=", $sidebar);

// CHECK IF THERE IS CONTENT

// Remove all existing entries for that guide from intervening table

$qd = "DELETE FROM pluslet_subject WHERE subject_id = '$subject_id'";
$dr = mysql_query($qd);

// Now insert the appropriate entries

foreach ($leftconts as $key => $value) {
	if ($key != 0) {
		$qi = "INSERT INTO pluslet_subject (pluslet_id, subject_id, pcolumn, prow) VALUES ('$value', '$subject_id', 0, '$key')";
		//print $qi . "<br />";
		$ir = mysql_query($qi);
	}
}

foreach ($centerconts as $key => $value) {
	if ($key != 0) {
		$qi = "INSERT INTO pluslet_subject (pluslet_id, subject_id, pcolumn, prow) VALUES ('$value', '$subject_id', 1, '$key')";
		//print $qi . "<br />";
		$ir = mysql_query($qi);
	}
}

foreach ($sidebarconts as $key => $value) {
	if ($key != 0) {
		$qi = "INSERT INTO pluslet_subject (pluslet_id, subject_id, pcolumn, prow) VALUES ('$value', '$subject_id', 2, '$key')";
		//print $qi . "<br />";
		$ir = mysql_query($qi);
	}
}

/////////////////////
// Alter chchchanges table
// table, flag, item_id, title, staff_id
////////////////////

$updateChangeTable = changeMe("guide", "update", $_COOKIE["our_guide_id"], $_COOKIE["our_guide"], $_SESSION['staff_id']);


print "<strong>" . _("Guide Updated.") . "</strong>";
?>
