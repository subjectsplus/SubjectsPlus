// Handles the autocomplete for the box search

function activateFindboxSearch() {
$('.findbox-search').keypress(function(data) {

	$('.findbox-searchresults').empty();
	var search_term = $('.findbox-search').val(); 
   
 $.get('../includes/autocomplete_data.php?collection=home&term=' +  search_term, function(data) {

	 if(data.length != 0) {
		for(var i = 0; i < data.length; i++) {

			if (data[i]['content_type'] == "Pluslet") {

			$('.findbox-searchresults').append("<li data-pluslet-id='" + data[i].id + "' class=\"pluslet-listing\">"  + data[i].label + "<button class=\"clone-button pure-button pure-button-primary\">Clone</button><button class=\"copy-button pure-button pure-button-primary\">Copy</button></li>");
			
			}
				
		}
} else {
	$('.findbox-searchresults').html("<span class=\"no-box-results\">No Results</span>");
	   }
 });
});
}

