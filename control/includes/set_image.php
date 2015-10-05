<?php

/**
 *   @file set_image.php
 *   @brief upload an image for use in video module
 *
 *   @author adarby
 *   @date feb 2012
 *   @todo this is hopelessly redundant of set_picture.php
 */


$subsubcat = "";
$subcat = "";
$page_title = "Set Picture";

$no_header = "yes";

include("../includes/header.php");
include("../includes/upload/class.upload.php");

// Connect to database


// Make sure they have permission to change a picture
// needs to be either theirs, or they're an admin

if ($_SESSION["admin"] != "1") {

    echo "<p>" . _("You are not authorized to view this.") . "</p>";
    exit;
}

if (is_numeric($_REQUEST["video_id"])) {
    $video_id = $_REQUEST["video_id"];
} else {
    print _("Perhaps you have come here by a funny path?");
    exit;
}

// set variables
//$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'test');
$dir_dest = "../../assets/images/video_thumbs/";
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);


$original_photo = "
<div class=\"box no_overflow\" id=\"original_photo\">
<p><img src=\"../../assets/images/video_thumbs/" . $video_id . "_medium.jpg\" class=\"staff_photo\" align=\"left\"   />" . _("Current Thumbnail . . . boooring.") . "</p>
</div>";



$upload_box = "<div class=\"box no_overflow\">
<p>" . _("Once you click upload, the current image will be replaced.  <strong>Note:</strong>  Images should be 200x150 px.") . "</p>
<br />
<form name=\"upload_form\" enctype=\"multipart/form-data\" method=\"post\" action=\"set_image.php\" />
<input type=\"hidden\" name=\"video_id\" value=\"$video_id\" />
        <p>Resize image to 200px wide? <input type=\"checkbox\" name=\"resizeme\" /></p>
        <br />
<p><input type=\"file\" size=\"32\" name=\"my_field\" value=\"\" /></p>
<p class=\"button\"><input type=\"hidden\" name=\"action\" value=\"image\" />
<input type=\"submit\" name=\"Submit\" value=\"" . ("Upload!") . "\" /></p>
</div>";


if ((isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '')) == 'image') {

    // ---------- IMAGE UPLOAD ----------
    // we create an instance of the class, giving as argument the PHP object
    // corresponding to the file field from the form
    // All the uploads are accessible from the PHP object $_FILES
    $handle = new Upload($_FILES['my_field']);

    // then we check if the file has been uploaded properly
    // in its *temporary* location in the server (often, it is /tmp)
    if ($handle->uploaded) {

        // yes, the file is on the server
        // below are some example settings which can be used if the uploaded file is an image.
        if (isset($_POST["resizeme"])) {
          $handle->image_resize = true;
          $handle->image_ratio_y = true;
          $handle->image_x = 200;
        }
        $handle->image_convert = 'jpg';
        $handle->file_new_name_body = $_POST["video_id"]."_medium";
        $handle->file_overwrite = true;
        $handle->file_auto_rename = false;
        $handle->dir_auto_chmod = true;

        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
        $handle->Process($dir_dest);

        // we check if everything went OK
        if ($handle->processed) {
            // everything was fine ! Close the modal window
?>
            <script type="text/javascript" language="javascript"> $(document).ready(function(){ parent.$.colorbox.close(); }); </script>
<?php

        } else {
            // one error occured
            echo '<fieldset>';
            echo '  <legend>file not uploaded to the wanted location</legend>';
            echo '  Error: ' . $handle->error . '';
            echo '</fieldset>';
        }

        // we delete the temporary files
        $handle->Clean();
    } else {
        // if we're here, the upload file failed for some reasons
        // i.e. the server didn't receive the file
        echo '<fieldset>';
        echo '  <legend>file not uploaded on the server</legend>';
        echo '  Error: ' . $handle->error . '';
        echo '</fieldset>';
    }
}

print "<div id=\"maincontent\">
<h2 class=\"bw_head\">" . _("Update Image") . "</h2>";
print $upload_box;
print $original_photo;
print $result;
print "</div>";

include("../includes/footer.php");
?>