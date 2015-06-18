/**
 * 
 */


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
		 jQuery('.guide_results').show();
		 jQuery('.loading').remove();
	 }
 }

 function linksHandler (titles) {
	 var table_data;
	 
	 for(var i=0; i<titles.length; i++) {

		 var title = titles[i];

		
		 table_data += "<tr>" +
		 	              "<td>" + title[0].title + "</td>" +
		 	              "<td>" + title[1].status + "</td>" +
		 	              "<td>" + title[2].url + "</td>" ;
		 	              //"<td>" + title[3].working_link + "</td>";
		 

	      
 }
	 jQuery('.link-results-body').empty().append(table_data);
	 jQuery('.link_results').show();
	 jQuery('.loading').remove();
	 
 }
 
 function importGuides(selected_guide_id, selected_guide_name, url) {

	   var guide = [ selected_guide_id, selected_guide_name ];

	   console.log(selected_guide_id);
	   console.log(selected_guide_name);

	   jQuery.ajax({
	     type: "GET",
	     url: url,
	     data: "libguide=" + selected_guide_id,
	     error: function(data) {
	         
	         jQuery('.import-output').append("<p class='import-feedback'> There was an error importing this guide.</p>");
	         jQuery('.import-output').append("<p>" + data.responseText + "</p>");
	    	 console.log(data);
	    	 
	     },
	     success:  function(data) {
	       console.log(data);

	       if (!data) {
		 jQuery('.import-output').append("<p class='import-feedback'>There was problem importing this guide</p>"); 
           		 	jQuery('.loading').remove();
           		 	
	       } 

	       if (data.titles) {

	    	   linksHandler(data.titles);
		   } 


		   if (data.titles && data.titles.length && data.titles.length === 0) {

			   jQuery('.loading').html("The importer couldn't find any links in this guide.");
			   
			   
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


	 jQuery('.import-output').append("<div class=\"loading loader\">Loading... </div>");
	 
	 importGuides(selected_guide_id, selected_guide_name,  "import_libguides_links.php");
	 
	 
 });
 
jQuery('.import_guide').on('click', function() {

	 var selected_guide_name = jQuery(this).parent().parent().find('option:selected').text(); 
	 var selected_guide_id = jQuery(this).parent().parent().find('option:selected').val(); 

     
	 importGuides(selected_guide_id, selected_guide_name, "import_libguides.php");
	 jQuery('.import-output').append("<div class=\"loading loader\">Loading...</div>");
	 

});