<?php

use SubjectsPlus\Control\Querier;

//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');
include_once('../../../../control/includes/autoloader.php');

global $AssetPath;

try {
	} catch (Exception $e) {
	echo $e;
}

$querier = new Querier();

if( isset($_COOKIE["our_guide"]) && isset($_COOKIE["our_guide_id"]) )
{
	$qs = "SELECT lname, fname, email, tel, title, s.staff_id from staff s, staff_subject ss WHERE s.staff_id = ss.staff_id and ss.subject_id = " . $_COOKIE["our_guide_id"] . " ORDER BY lname, fname";

	$sugStaffArray = $querier->query($qs);
}

$lobjStaffIds = array();

foreach( $sugStaffArray as $lobjStaff )
{
	$lobjStaffIds[] = $lobjStaff[5];
}

$qs = "SELECT lname, fname, email, tel, title from staff s WHERE s.staff_id NOT IN ('" . implode( '\',\'', $lobjStaffIds ) . "')  ORDER BY lname, fname" ;

$staffArray = $querier->query($qs);

if( count($sugStaffArray) > 0 )
{
	$lstrHTML = "<table>";
	$lstrHTML .= "<strong>" . _("Specialists") . "</strong>";
}

foreach ($sugStaffArray as $value) {

	// get username from email
	$truncated_email = explode("@", $value[2]);

	$staff_picture = $AssetPath . "users/_" . $truncated_email[0] . "/headshot.jpg";

	if( isset( $_POST['emails'] ) )
		$lobjSelected = explode( ',', $_POST['emails']);

	if( in_array( $value[2], $lobjSelected ) )
		// Output Picture and Contact Info
		$lstrHTML .= "<tr><td><input type=\"checkbox\" name=\"selected_staff\" value=\"$value[2]\" checked></td><td><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
		</td><td>$value[1] $value[0]</td>\n";
	else
		// Output Picture and Contact Info
		$lstrHTML .= "<tr><td><input type=\"checkbox\" name=\"selected_staff\" value=\"$value[2]\"></td><td><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
		</td><td>$value[1] $value[0]</td>\n";
}

if( count($sugStaffArray) > 0 )
{
	$lstrHTML .= "</table>";
}

$lstrHTML .= "<strong>" . _("All") . "</strong>";
$lstrHTML .= "<table>";

foreach ($staffArray as $value) {

	// get username from email
	$truncated_email = explode("@", $value[2]);

	$staff_picture = $AssetPath . "users/_" . $truncated_email[0] . "/headshot.jpg";

	if( isset( $_POST['emails'] ) )
		$lobjSelected = explode( ',', $_POST['emails']);

	if( in_array( $value[2], $lobjSelected ) )
		// Output Picture and Contact Info
		$lstrHTML .= "<tr><td><input type=\"checkbox\" name=\"selected_staff\" value=\"$value[2]\" checked></td><td><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
		</td><td>$value[1] $value[0]</td>\n";
	else
		// Output Picture and Contact Info
		$lstrHTML .= "<tr><td><input type=\"checkbox\" name=\"selected_staff\" value=\"$value[2]\"></td><td><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
		</td><td>$value[1] $value[0]</td>\n";
}

$lstrHTML .= "</table>";

print $lstrHTML;

?>