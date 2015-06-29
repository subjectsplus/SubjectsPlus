/**
 * 
 */

jQuery('.guides').select2({"width" : "75%"});

jQuery('.import_guide').prop("disabled", true);



$('.dropspotty').each(function() { 

	try {
		
var data_children =	$(this).attr('data-children'); 
var childs = data_children.split();

childs.forEach(function(data) {
	
	var split_ids = data.split(',');
	console.log(split_ids);
	split_ids.forEach(function(data) {
		
		$('#' + data ).hide();
console.log(data);
	})

});


	} catch(e) {
		
	}

});


$('.guides').on("change", function() {
	
	jQuery('.import_guide').prop("disabled", true);

});

 
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
	 //jQuery('.link_results').show();
     jQuery('.import-output').append("<h1 class=\"links-success\">" + "Links Imported Successfully" + "</h1>");

	 jQuery('.loading').remove();
	 jQuery('.import_guide').prop("disabled",false);
	 jQuery('.view-links-results').prop("disabled",false);
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
	         
	         jQuery('.import-output').append("<p class='import-feedback'>There was an error importing this guide. You may be trying to import a guide that has already been imported.</p>");
	         jQuery('.import-output').append("<p class='import-error'>" + data.responseText + "</p>");
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
		 
		 jQuery('.import-output').append("<h1 class='import-feedback'>Sucessfully Imported <a target=\"_blank\" href='../guides/guide.php?subject_id=" + data.imported_guide[0] +  "'>" + selected_guide_name  + "</a></h1>" ); 
		 jQuery('.import-output').append("<p class='import-feedback'>You can compare your guide with its <a target=\"_blank\" href='http://libguides.miami.edu/content.php?pid=" + data.imported_guide[0] +  "'>original LibGuide</a>.</p>" ); 

		 jQuery('.import-output').append("<p class='import-feedback'>Click here to view all your <a target=\"_blank\" href='../guides'> SubjectsPlus guides</a></p>" ); 
         jQuery('.previously-imported').append("<li><a target=\"_blank\" href='../guides/guide.php?subject_id=" + data.imported_guide[0] +  "'>" + selected_guide_name  + "</a></li>");

		 jQuery('.loading').remove();
	       }
	     }

	   });
 }
 
 jQuery('.import_links').on('click', function() {
	 
	 var selected_guide_name = jQuery(this).parent().parent().find('option:selected').text(); 
	 var selected_guide_id = jQuery(this).parent().parent().find('option:selected').val(); 
//	 jQuery(this).parent().parent().find('option:selected').remove();

	 jQuery('.import-output').append("<div class=\"loading loader\">Loading... </div>");
	 
	 importGuides(selected_guide_id, selected_guide_name,  "import_libguides_links.php");
	 
	 
 });
 
jQuery('.import_guide').on('click', function() {

	 var selected_guide_name = jQuery(this).parent().parent().find('option:selected').text(); 
	 var selected_guide_id = jQuery(this).parent().parent().find('option:selected').val(); 

     
	 importGuides(selected_guide_id, selected_guide_name, "import_libguides.php");
	 jQuery('.import-output').append("<div class=\"loading loader\">Loading...</div>");
	 

});


jQuery('.view-links-results').on('click', function(){

	jQuery('.link_results').toggle();
	
})


