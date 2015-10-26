<div id="image_gallery" class="second-level-content" style="display:none;">

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
.dropzone-text {
margin-left:40px;
font-size:1.5em;
}

.dz-success-mark {
display:none;
}

.dz-error-mark {
display:none;
}

.dz-preview {
    margin-top: 60px;
    margin-left: 25px;
    margin-left: 20px;
}
</style>



<form id="imagezone" action="../includes/image_gallery_upload.php" class="dropzone dz-clickable user_guides_display">
<div class="dz-default dz-message"><span class="dropzone-text">Drop files here to upload</span></div>

<input type="hidden" name="staff_id" value="<?php echo $_SESSION['staff_id']; ?>" />
</form>
</div>


<script>
$(document).ready(function() {
Dropzone.options.imagezone = {
		  init: function() {
		    this.on("success", function(file) { $('.dz-success-mark').show(); });
		    this.on("error", function(file) { $('.dz-error-mark').show(); });
		    
		  }
		};
});
</script>