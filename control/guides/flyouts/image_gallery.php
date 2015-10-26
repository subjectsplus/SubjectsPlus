<div id="image_gallery">

<style>
.dropzone {
    background: #484848 none repeat scroll 0 0;
    border-color: #3e3e3e #525252 #525252 #3e3e3e;
    border-radius: 4px;
    border-style: solid;
    border-width: 1px;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    margin: 0 -3px 20px;
    max-height: 385px;
    overflow: auto;
    padding-bottom: 25%;
    width: 98% !important;
    padding-top: 10%;
}
</style>



<form action="../includes/image_gallery_upload.php" class="dropzone dz-clickable user_guides_display">
<div class="dz-default dz-message"><span>Drop files here to upload</span></div>

<input type="hidden" name="staff_id" value="<?php echo $_SESSION['staff_id']; ?>" />
</form>
</div>