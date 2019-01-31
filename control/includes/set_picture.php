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
   
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

global $headshot_thumb_width;
global $headshot_large_width;

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
<p><img src=\"../../assets/users/_" . $truncated_email[0] . "/headshot.jpg\" class=\"staff_photo\" align=\"left\"   />&nbsp;" . _("Current Picture . . . boooring.") . "</p>
</div>";



$upload_box = "<div class=\"box no_overflow\">
<h3>" . _("Upload the new you.") . "</h3>
<p>" .  _("Once you click upload, the current image will be replaced.  <br /><br /><strong>Note:</strong> Minimum image width is " . $headshot_large_width . "px. Using a square image is best.") ."</p>
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

  	
  	list( $intOriginalWidth, $intOriginalHeight ) = getimagesize($handle->file_src_pathname); 

      //set a min width
      $handle->image_min_width = $headshot_large_width;
      $handle->image_x = $intOriginalWidth;
      $handle->file_new_name_body = 'headshot_original';
      $lboolResize = TRUE;   
  			
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

        	//if resize is true, large was uploaded so resize and create 2 thumbnails
        	//if resize is false, print error
          if( $lboolResize ) {
          	
            list( $width, $height ) = getimagesize($dir_dest . DIRECTORY_SEPARATOR . "headshot_original.jpg");
            $rscThumbnail = imagecreatetruecolor($headshot_thumb_width, $headshot_thumb_width);
            $rscLargeImage = imagecreatetruecolor($headshot_large_width, $headshot_large_width);
            
            $rscOriginalImage = imagecreatefromjpeg($dir_dest . DIRECTORY_SEPARATOR . "headshot_original.jpg");
            
            imagecopyresampled( $rscThumbnail, $rscOriginalImage, 0, 0, 0, 0, $headshot_thumb_width, $headshot_thumb_width, $width, $height );
            imagecopyresampled( $rscLargeImage, $rscOriginalImage, 0, 0, 0, 0, $headshot_large_width, $headshot_large_width, $width, $height );

            imagejpeg( $rscThumbnail, $dir_dest . DIRECTORY_SEPARATOR . "headshot.jpg" );
            imagejpeg( $rscLargeImage, $dir_dest . DIRECTORY_SEPARATOR . "headshot_large.jpg" );
          }

      // everything was fine ! Close the modal window and reload page to show changes
      ?>
      <script type="text/javascript" language="javascript"> 
      $(document).ready(function(){ 
        parent.$.colorbox.close();
      }); 
      </script>      

      <?php 

      } else {
        // one error occured
        echo '<div class="setStaffPhotoWarning">';
        echo '<p>' . _("File could not be uploaded to the specified location") . '</p>' ;
        echo '<p> Error: ' . $handle->error . '';
        echo '</p></div>';
      }

    // we delete the temporary files
    $handle->Clean();
  
  } else {
    // if we're here, the upload file failed for some reasons
    // i.e. the server didn't receive the file
    echo '<div class="setStaffPhotoWarning">';
    echo '  <p>' . _("File not uploaded to the server") . '</p>';
    echo '  <p>Error: ' . $handle->error . '';
    echo '</p></div>';
  }
}

print "<div id=\"maincontent\"><div class=\"set_photo_container\">
<h2 class=\"bw_head\">" . _("Update Picture for ") . $staffer[0]['fname'] . "&nbsp;" . $staffer[0]['lname'] . "</h2>";
print $original_photo;
print $upload_box;
print "</div></div>";

?>

<script type="text/javascript" language="javascript"> 
  $(document).ready(function(){ 
      $(".setStaffPhotoWarning").insertBefore(".bw_head");
      $(".setStaffPhotoWarning").css("width","92%");
  }); 
</script>  

<?php

include("../includes/footer.php");
