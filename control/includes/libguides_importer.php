<?php

// For instruction on how to use this please see the follwing page on the wiki:
// http://www.subjectsplus.com/wiki/index.php?title=Libguides_Importer

header("Content-Type: text/html");
error_reporting(1);
ini_set('display_errors', 1);
include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');
include('../includes/header.php');

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;


?>

<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Instructions</div>
  </div>
  <div class="pluslet_body"> 
    For instructions on how to use this view the <a href="http://www.subjectsplus.com/wiki/index.php?title=Libguides_Importer">documentation</a> on the SubjectsPlus wiki.
  </div>
 
</div>

<?php 

$libguides_importer = new LibGuidesImport;
 ?>

<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Select a Guide to Import</div>
  </div>
  <div class="pluslet_body"> 


<?php  

$libguides_importer->output_guides('libguides.xml');
?>
    <button class="import_guide">Import Guide</button>
  </div>
 
</div>
<link rel="stylesheet" href="<?php echo getControlURL(); ?>includes/css.php" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>";
<style>
.select2-container {
width: 20%;
margin-right: 3%;
}
</style>
<script>

jQuery('.guides').select2();
 
jQuery('.import_guide').on('click', function() {

   
   
   var selected_guide = jQuery('.guides').select2("val"); 
   var selected_guide_name = jQuery('.guides option:selected').text(); 

  // Progress needed!

   jQuery.ajax({
     type: "POST",
     url: "import_libguides.php",
     data: "libguide=" + selected_guide,
     success:  function(data) {
       console.log("Success"); 
       console.log(data);

       if (!data) {
	 jQuery('.pluslet_body').append("<p class='import-feedback'>There was problem importing this guide</p>"); 

       } else {
	 console.log(selected_guide_name);
	 jQuery('.pluslet_body').append( "<p class='import-feedback'>Sucessfully Imported <a href='../guides/guide.php?subject_id=" + data +  "'>" + selected_guide_name  + "</a></p>" ); 
       }

     }

   });

});

</script>
