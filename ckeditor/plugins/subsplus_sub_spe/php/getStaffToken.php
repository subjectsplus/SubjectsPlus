<?php

//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');
include_once('../../../../control/includes/autoloader.php');

global $AssetPath;

if( !isset($_POST['staff_list']) && count($_POST['staff_list']) <= 0 )
{
	exit;
}

$lobjSelectedStaff = $_POST['staff_list'];

$lstrHTML = "";

// Output Picture and Contact Info
$lstrHTML .= "<br /><div class=\"subsplus_sub_spe\" data-subsplus-sub-spe=\"" . implode( ',', $lobjSelectedStaff ) . "\" contenteditable=\"false\">";

foreach( $lobjSelectedStaff as $lstrEmail )
{
	$truncated_email = explode("@", $lstrEmail);
	$staff_picture = $AssetPath . "users/_" . $truncated_email[0] . "/headshot.jpg";

	$lstrHTML .= "<img src=\"$staff_picture\" class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />";
}

$lstrHTML .= "</div><br />";

print $lstrHTML;

?>