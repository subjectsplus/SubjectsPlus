<?php

/**
 *   @file set_picture.php
 *   @brief update staff picture
 *
 *   @author adarby
 *   @date Sep 17, 2009
 *   @todo
 */
    

use SubjectsPlus\Control\Querier;
   
error_reporting(E_ALL);

$subcat = "";
$page_title = "Set Picture";

$no_header = "yes";

include("../includes/header.php");
include("../includes/upload/class.upload.php");

$feedback = "";
$result = "";

// Make sure they have permission to change a picture
// needs to be either theirs, or they're an admin

if ($_REQUEST["staff_id"] != $_SESSION["staff_id"] && $_SESSION["admin"] != "1") {

  echo "<p>" . _("You are not authorized to view this.") . "</p>";
  exit;
}

if (is_numeric($_REQUEST["staff_id"])) {
  $staff_id = $_REQUEST["staff_id"];
} else {
  print _("Perhaps you have come here by a funny path?");
  exit;
}

// Find out the name and location for this person's picture

$staff_query = "select distinct lname, fname, email from staff WHERE staff_id = $staff_id";

$db = new Querier;
//$staff_result = $db->query($staff_query);
$staffer = $db->query($staff_query);

$truncated_email = explode("@", $staffer[0]["email"]);

$test_file = $UserPath . "/_" . $truncated_email[0];

// set variables
//$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'test');
$dir_dest = "../../assets/users/_" . $truncated_email[0];
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);


$original_photo = "
<div class=\"box no_overflow\" id=\"original_photo\">
<p><img src=\"../../assets/users/_" . $truncated_email[0] . "/headshot.jpg\" class=\"staff_photo\" align=\"left\"   />" . _("Current Picture . . . boooring.") . "</p>
</div>";



$upload_box = "<div class=\"box no_overflow\">
<p>" . _("Upload the new you.  Once you click upload, the current image will be replaced.  <strong>Note:</strong>  Your image will be scaled to <strong>70px wide</strong>.  Using a square image is best.") . "</p>
<br />
<form name=\"upload_form\" enctype=\"multipart/form-data\" method=\"post\" action=\"set_picture.php\" />
<input type=\"hidden\" name=\"staff_id\" value=\"$staff_id\" />
<p><input type=\"file\" size=\"32\" name=\"my_field\" value=\"\" /></p>
<p class=\"button\"><input type=\"hidden\" name=\"action\" value=\"image\" />
<input type=\"submit\" name=\"Submit\" value=\"" . ("Upload the New Me!") . "\" /></p>
</div>";


if ((isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '')) == 'image') {

  // ---------- IMAGE UPLOAD ----------
  // we create an instance of the class, giving as argument the PHP object
  // corresponding to the file field from the form
  // All the uploads are accessible from the PHP object $_FILES

  $handle = new upload($_FILES['my_field']);

  // then we check if the file has been uploaded properly
  // in its *temporary* location in the server (often, it is /tmp)


  if ($handle->uploaded) {

  	//check width.
  	list( $intOriginalWidth, $intOriginalHeight ) = getimagesize($handle->file_src_pathname);
  	switch( $intOriginalWidth )
  	{
  		case ( $intOriginalWidth < 70 ): //if less than 70, resize to 70 and create headshot file
  			$handle->image_x = 70;
  			$handle->file_new_name_body = 'headshot';
  			$lboolResize = FALSE;
  			break;
  		case ( $intOriginalWidth < 225 ): //If greater than 70 and less than 225, upload with width as is to headshot_large
  			$handle->image_x = $intOriginalWidth;
  			$handle->file_new_name_body = 'headshot_large';
  			$lboolResize = TRUE;
  			break;
  		default:
  			$handle->image_x = 225; //If greater than 225, resize to 225 and create headshot_large file
  			$handle->file_new_name_body = 'headshot_large';
  			$lboolResize = TRUE;
  			break;
  	}

    // yes, the file is on the server
    // below are some example settings which can be used if the uploaded file is an image.
    $handle->image_resize = true;
    $handle->image_ratio_y = true;
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

    	//if resize is true, large was uploaded so resize and create thumbnail
    	//if resize is false, just copy thumbnail as headshot_large
      if( $lboolResize )
      {
      	list( $width, $height ) = getimagesize($dir_dest . DIRECTORY_SEPARATOR . "headshot_large.jpg");
        $rscThumbnail = imagecreatetruecolor(70, 70);
        $rscLargeImage = imagecreatefromjpeg($dir_dest . DIRECTORY_SEPARATOR . "headshot_large.jpg");
        imagecopyresampled( $rscThumbnail, $rscLargeImage, 0, 0, 0, 0, 70, 70, $width, $height );
        imagejpeg( $rscThumbnail, $dir_dest . DIRECTORY_SEPARATOR . "headshot.jpg" );
      }else
      {
      	copy( $dir_dest . DIRECTORY_SEPARATOR . "headshot.jpg", $dir_dest . DIRECTORY_SEPARATOR . "headshot_large.jpg" );
      }

      // everything was fine ! Close the modal window
      ?>
      <script type="text/javascript" language="javascript"> $(document).ready(function(){ parent.$.colorbox.close(); }); </script>
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
  } else {
    // if we're here, the upload file failed for some reasons
    // i.e. the server didn't receive the file
    echo '<fieldset>';
    echo '  <legend>' . _("File not uploaded to the server") . '</legend>';
    echo '  Error: ' . $handle->error . '';
    echo '</fieldset>';
  }
}

print "<div id=\"maincontent\">
<h2 class=\"bw_head\">" . _("Update Picture for ") . $staffer[0]['fname'] . $staffer[0]['lname'] . "</h2>";
print $upload_box;
print $original_photo;
print "</div>";

include("../includes/footer.php");
