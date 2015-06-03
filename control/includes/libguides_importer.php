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
  <div class="titlebar_text">Setup Instructions</div>
  </div>
  <div class="pluslet_body"> 
    For instructions on how to set this up please view the <a href="http://www.subjectsplus.com/wiki/index.php?title=Libguides_Importer">documentation</a> on the SubjectsPlus wiki.
  </div>
 
</div>

<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Import Output</div>
  </div>
  <div class="pluslet_body import-output"> 

  
 
  <table class="link_results pure-table">
  <caption>Link Import Results</caption>
  <tr><th>Title</th><th>Status</th><th>URL</th><th>Working Link?</th></tr>
  </table>	
  </div>
 
  <table class="guide_results pure-table">
  <caption>Guide Import Results</caption>
  <tr><th>Box Name</th><th>Box Type</th></tr>
  </table>	
  </div>
  
 
 
</div>

<?php 

$libguides_importer = new LibGuidesImport('libguides.xml');
 ?>

 
<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Select a Guide to Import</div>
  </div>
  <div class="pluslet_body"> 



<?php  

$libguides_with_owners = $libguides_importer->output_guides($_GET["email"]);


?>
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

//jQuery('.guides').select2();
 
 
 function guidesHandler(guides) {

	 console.log(guides[0]);
	 
	 for(var i=0; i<guides.length; i++) {

		 var guide = guides[i];

		 console.log(guide[0]);
		
		 var table_data = "<tr>" +
		 	              "<td>" + guide[0].box[0].box_name + "</td>" +
		 	              "<td>" + guide[0].box[1].box_type + "</td>";
		 	          
		 
		 jQuery('.guide_results').append(table_data);
		 
	 }
 }

 function linksHandler (titles) {

	 for(var i=0; i<titles.length; i++) {

		 var title = titles[i];

		
		 var table_data = "<tr>" +
		 	              "<td>" + title[0].title + "</td>" +
		 	              "<td>" + title[1].status + "</td>" +
		 	              "<td>" + title[2].url + "</td>" +
		 	              "<td>" + title[3].working_link + "</td>";
		 
		 jQuery('.link_results').append(table_data);
		 jQuery('.loading').remove();
	      
 }
 }
 
 function importGuides(selected_guide_id, selected_guide_name, url) {

	   var guide = [ selected_guide_id, selected_guide_name ];

	   console.log(selected_guide_id);
	   console.log(selected_guide_name);

	  // Progress needed!

	   jQuery.ajax({
	     type: "GET",
	     url: url,
	     data: "libguide=" + selected_guide_id,
	     success:  function(data) {
	       console.log(data);

	       if (!data) {
		 jQuery('.import-output').append("<p class='import-feedback'>There was problem importing this guide</p>"); 
           		 	jQuery('.loading').remove();
           		 	
	       } 

	       if (data.titles) {

	    	   linksHandler(data.titles)
		   } 

		   if (data.imported_guide) {
		 console.log(data);
		 guidesHandler(data);
		 
		 jQuery('.import-output').append( "<p class='import-feedback'>Sucessfully Imported <a href='../guides/guide.php?subject_id=" + data.imported_guide[0] +  "'>" + selected_guide_name  + "</a></p>" ); 
		 jQuery('.loading').remove();
	       }
	     }

	   });
 }
 
 jQuery('.import_links').on('click', function() {
	 
	 var selected_guide_name = jQuery(this).parent().parent().find('option:selected').text(); 
	 var selected_guide_id = jQuery(this).parent().parent().find('option:selected').val(); 


	 jQuery('.import-output').append("<p class=\"loading\">Loading...</p>");
	 
	 importGuides(selected_guide_id, selected_guide_name,  "import_libguides_links.php");
	 
	 
 });
 
jQuery('.import_guide').on('click', function() {

	 var selected_guide_name = jQuery(this).parent().parent().find('option:selected').text(); 
	 var selected_guide_id = jQuery(this).parent().parent().find('option:selected').val(); 

     
	 importGuides(selected_guide_id, selected_guide_name, "import_libguides.php");
	 jQuery('.import-output').append("<p class=\"loading\">Loading...</p>");
	 

});

</script>