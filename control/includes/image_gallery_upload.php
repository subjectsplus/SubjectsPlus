<?php
include("../includes/autoloader.php");
include("../includes/upload/class.upload.php");

use SubjectsPlus\Control\Querier;


$handle = new upload($_FILES['file']['tmp_name']);

$filename = basename($_FILES['file']['name']);


$dir_dest = "../../assets/images/" . $filename;


if ($handle->uploaded) {


	// yes, the file is on the server
	// below are some example settings which can be used if the uploaded file is an image.
	$handle->image_resize = false;
	$handle->image_ratio_y = false;
	$handle->image_convert = 'jpg';
	$handle->file_overwrite = true;
	$handle->file_auto_rename = false;
	$handle->dir_auto_chmod = true;

	// now, we start the upload 'process'. That is, to copy the uploaded file
	// from its temporary location to the wanted location
	// It could be something like $handle->Process('/home/www/my_uploads/');
	$handle->Process($dir_dest);

	// we check if everything went OK
	if ($handle->processed) {


	}
		
		// everything was fine ! Close the modal window
		?>
      <?php

    } else {
      // one error occured
      echo '<fieldset>';
      echo '  <legend>' . _("File not uploaded to the specified location") . '</legend>';
      echo '  Error: ' . $handle->error . '';
      echo '</fieldset>';
    }

    // we delete the temporary files
    $handle->Clean();




