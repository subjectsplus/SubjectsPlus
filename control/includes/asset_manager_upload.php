<?php
use SubjectsPlus\Control\Querier;


require_once("../includes/autoloader.php");
require_once("../includes/upload/class.upload.php");
require_once("../includes/config.php");

$handle = new upload($_FILES['file']['tmp_name']);


// Find out the name and location for this person's picture

$staff_id = $_POST['staff_id'];


$staff_query = "select distinct lname, fname, email from staff WHERE staff_id = $staff_id";

$db = new Querier;
//$staff_result = $db->query($staff_query);
$staffer = $db->query($staff_query);

$truncated_email = explode("@", $staffer[0]["email"]);


// set variables
$dir_dest = "../../assets/users/_" . $truncated_email[0];



if ($handle->uploaded) {


	// yes, the file is on the server
	// below are some example settings which can be used if the uploaded file is an image.
	$handle->image_resize = false;
	$handle->image_ratio_y = false;
	$handle->image_convert = 'jpg';
	$handle->file_overwrite = true;
	$handle->dir_auto_chmod = true;

	// now, we start the upload 'process'. That is, to copy the uploaded file
	// from its temporary location to the wanted location
	// It could be something like $handle->Process('/home/www/my_uploads/');
	$handle->Process($dir_dest);

	// we check if everything went OK
	if ($handle->processed) {


	}
	
	// we delete the temporary files
	$handle->Clean();
} else {
	http_response_code(500);
}

