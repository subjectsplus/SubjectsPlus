$(document).ready(function () {

	$('#show_dblist_options').on("click", function() {
	
		$('.fa-check').hide();

		
	});
	
	var databaseOptions = {
			
			"include_icons" : 0,
			"include_description" : 0,
			"display_note" : 0
			
	}
	
	var DatabaseToken = {
			
			"label" : "",
			"record_id" : "",
			"database_options" : Object.create(databaseOptions),
			"token_string" : ""
			
			
	}

	

	
	$.get("../../subjects/json/db_json.php", function(data) {
	
		$('.db-list-results').empty();
		
		for(var i=0; i < data['databases'].length; i++) {
			
			$('.customdb-list').append("<option value='" + data['databases'][i].this_record + "'>" + data['databases'][i].newtitle + "</option>");

		}
		
		
	});
	
	
	$('.customdb-list').on("change", function() {
		
		$( ".customdb-list option:selected" ).each(function() {
		
			console.log($(this).val());
			
			$('.db-list-results').append("<li class='db-list-item' value='" + $(this).val() +"'>"+ $(this).html() +"</li>")
			$('.db-list-results').sortable();
		    $('.db-list-results').disableSelection();

	});		
	});
	
	$('.customdb-list').hide();
	
	var click_count = 0;
	
	$(".dblist-button").on("click", function() {
        plantClone('', 'Basic', '');        
        var waitCKEDITOR = setInterval(function() {
            if (window.CKEDITOR) {
               clearInterval(waitCKEDITOR);
               
            //   var token_string = "<ul class='token-list'>";
            	   var token_string = "";
               $('.db-list-item-draggable').each(function(data){
            	   console.log($(this).html());
            	   var title = $(this).text()
                   var record_id = $(this).val();
            	   if ($(this).text()) {
            	   token_string += "<p class='token-list-item'>{{dab},{" + record_id + "},{" + title + "}" + ",{" + "" + databaseOptions.include_icons + databaseOptions.include_description + databaseOptions.display_note + "}}<p>";
            	   }
                   console.log(token_string);
               });
               
           //    token_string += "</ul>";
               
               CKEDITOR.instances[Object.keys(CKEDITOR.instances)[click_count]].setData(token_string);

               click_count++;
  
            }
        }, 100);
        
	});
	
	
	
		$('.databases-search').keypress(function(data) {

			$('.databases-searchresults').empty();
			var search_term = $('.databases-search').val(); 
		   
		 $.get('../includes/autocomplete_data.php?collection=records&term=' +  search_term, function(data) {

			 if(data.length != 0) {
				for(var i = 0; i < data.length; i++) {

					console.log(data);
					if (data[i]['content_type'] == "Record") {

					$('.databases-searchresults').append("<li data-pluslet-id='" + data[i].id + "' class=\"db-list-item database-listing\">" +
							"<div class=\"pure-g\"><div class=\"pure-u-3-5 box-search-label\" title=\"" + data[i].label + "\">" + data[i].label + "</div>" +
									"<div class=\"pure-u-2-5\" style=\"text-align:right;\">" +
									"<button data-label='" + data[i].label + "' value='" + data[i].id  + "' class=\"add-to-list-button pure-button pure-button-secondary\">Add to List</button></div></div></li>");
					
					}
						
				}
		} else {
			$('.databases-searchresults').html("<li><span class=\"no-box-results\">No Results</span></li>");
			   }
		 });
		});
	
		var list = [];

		$('body').on("click", '.add-to-list-button', function() {
		
		
			var databaseToken = Object.create(DatabaseToken);
			databaseToken.label = $(this).attr('data-label');;
			databaseToken.record_id = $(this).val();
			
			$('body').on("click", ".show-description-toggle", function(data) { 
				
				$(this).toggleClass("active");
				$(this).children().toggle();
				databaseToken.database_options.include_description = $(this).hasClass('active') | 0;
				console.log(databaseToken);

			});
			$('body').on("click", ".show-icons-toggle", function(data) { 
				
				$(this).toggleClass("active");
				$(this).children().toggle();
				databaseToken.database_options.include_icons = $(this).hasClass('active') | 0;
				console.log(databaseToken);
				
			});
			$('body').on("click", ".include-note-toggle", function(data) { 
				
				$(this).toggleClass("active");
				$(this).children().toggle();
				databaseToken.database_options.display_note = $(this).hasClass('active') | 0;
				console.log(databaseToken);

			});
			
			$('.db-list-results').append("<li class='db-list-item-draggable' value='" + databaseToken.record_id +"'>"+ databaseToken.label +"<div><span class='show-description-toggle'><i class='fa fa-check'></i> Show Description  </span><span class='show-icons-toggle'> <i class='fa fa-check'></i>Show Icons </span><span class='include-note-toggle'><i class='fa fa-check'></i> Include Note </span></div></li>");
			list.push(databaseToken);
			$('.db-list-results').sortable();
		    $('.db-list-results').disableSelection();
			$('.fa-check').hide();

			console.log(list);
		})
		
	
	
	
});