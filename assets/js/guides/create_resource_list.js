$(document).ready(function () {
/*
 * This assumes you have some markup that looks like this:
 * 
 * <ul class="db-list-results ui-sortable">     
 <li class="db-list-item-draggable" value="253">Record Title
 <div><span class="show-description-toggle"><i class="fa fa-check" style="display: none;"></i>
  Show Description  </span><span class="show-icons-toggle"> <i class="fa fa-check" style="display: none;">
  </i>Show Icons </span><span class="include-note-toggle"><i class="fa fa-check" style="display: none;">
  </i> Include Note </span></div></li></ul>
 */
	
	var DatabaseToken = {	

			"label" : "",
			"record_id" : "",
			"token_string" : ""
			
	}	
	
	var click_count = 0;
	
	$(".dblist-button").on("click", function() {
        plantClone('', 'Basic', '');        
        var waitCKEDITOR = setInterval(function() {
            if (window.CKEDITOR) {
               clearInterval(waitCKEDITOR);
               
            	   var token_string = "<ul class='token-list'>";
               $('.db-list-item-draggable').each(function(data){
            	   var title = $(this).find('.db-list-label').text()
                   var record_id = $(this).val();
            	   
            	   console.log($(this).data());
            	   
            	   // Grab the options
            	   var display_options = $(this).data().display_options;
       

            	   // If these are undefined, make them 0
            	   display_options = (typeof display_options === 'undefined') ? "000" : display_options;
            	  

            	   if ($(this).text()) {
            	   token_string += "<li class='token-list-item'>{{dab},{" + record_id + "},{" + title + "}" + ",{" + display_options + "}}</li>";
            	   }
               });
               
               
               token_string += "</ul>";
               console.log(token_string);
               
               
              
               
               CKEDITOR.instances[Object.keys(CKEDITOR.instances)[click_count]].setData(token_string.trim());

               click_count++;
  
               $('.db-list-results').empty();
            }
        }, 100);
        
	});
	
	
	
	$('.dblist-reset-button').on("click", function() {
        $('.db-list-results').empty();
        $('.databases-search').val("");

	});
	
		$('.databases-search').keypress(function(data) {

			$('.databases-searchresults').empty();
			var search_url;
			var search_term = $('.databases-search').val(); 
			var limit_az = $('#limit-az').prop("checked");
			
			if(limit_az) {
			 	search_url = "../includes/autocomplete_data.php?collection=azrecords&term=";
			} else {
				search_url = "../includes/autocomplete_data.php?collection=records&term=";
			}
			
		 $.get(search_url +  search_term, function(data) {

			 if(data.length != 0) {
				for(var i = 0; i < 10; i++) {

					if (data[i]['content_type'] == "Record") {

					$('.databases-searchresults').append("<li data-pluslet-id='" + data[i].id + "' class=\"db-list-item database-listing\">" +
							"<div class=\"pure-g\"><div class=\"pure-u-4-5 list-search-label\" title=\"" + data[i].label + "\">" + data[i].label + "</div>" +
									"<div class=\"pure-u-1-5\" style=\"text-align:right;\">" +
									"<button data-label='" + data[i].label + "' value='" + data[i].id  + "' class=\"add-to-list-button pure-button pure-button-secondary\"><i class=\"fa fa-plus\"></i></button></div></div></li>");				
					}
						
				}
		} else {
			$('.databases-searchresults').html("<li><span class=\"no-box-results\">No Results</span></li>");
			   }
		 });
		});
	
		$('body').on("click", '.add-to-list-button', function() {
			
			$('.db-list-buttons').show();
			$('.db-list-content').show();
			
			var databaseToken = Object.create(DatabaseToken);
			databaseToken.label = $(this).attr('data-label').trim();
			databaseToken.record_id = $(this).val();
			
			
			
			$('.db-list-results').append("<li class='db-list-item-draggable' value='" + databaseToken.record_id +"'><span class='db-list-label'>"+ databaseToken.label +
					"</span><div><span class='show-description-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
					" Description  </span><span class='show-icons-toggle db-list-toggle'><i class='fa fa-minus'></i> <i class='fa fa-check'></i>" +
					" Icons </span><span class='include-note-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
					" Note </span></div></li>");
			$('.db-list-results').sortable();
		    $('.db-list-results').disableSelection();
			$('.fa-check').hide();
		
		});
		
		
		$('body').on("click", ".show-description-toggle", function(data) { 
			toggleOptions($(this));
		});
		$('body').on("click", ".show-icons-toggle", function(data) { 
			
			toggleOptions($(this));
		});
		$('body').on("click", ".include-note-toggle", function(data) { 
			toggleOptions($(this));
		});
	
	function toggleOptions(toggleElement) {
		    toggleElement.find('.fa-minus').toggle();
		    toggleElement.find('.fa-check').toggle();

			toggleElement.toggleClass("active");
			
			toggleElement.children().find('.fa-minus').toggle();

			
		    include_description = toggleElement.parent().find('.show-description-toggle').hasClass('active') | 0; 
		    include_icons = toggleElement.parent().find('.show-icons-toggle').hasClass('active') | 0;
		    display_note = toggleElement.parent().find('.include-note-toggle').hasClass('active') | 0; 
			
			var display_options = ''+include_description +  ''+include_icons + ''+display_note +"";
		    toggleElement.parent().parent().data({'display_options' : display_options});

	}
	
});




