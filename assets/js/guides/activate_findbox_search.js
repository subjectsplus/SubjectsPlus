// Handles the autocomplete for the box search

function activateFindboxSearch() {
$('.findbox-search').keyup(function(data) {

	$('.findbox-searchresults').empty();
	var search_term = $('.findbox-search').val(); 

	
	
 $.get('../includes/autocomplete_data.php?collection=pluslet&term=' +  search_term, function(data) {

	 if(data.length != 0) {

		 console.log(data);
		for(var i = 0; i < data.length; i++) {

			if (data[i]['content_type'] == "Pluslet") {

			$('.findbox-searchresults').append("<li data-pluslet-id='" + data[i].id + "' class=\"pluslet-listing\"><div class=\"pure-g\"><div class=\"pure-u-3-5 box-search-label\" title=\"" + data[i].label + "\">" + data[i].label + "</div><div class=\"pure-u-2-5\" style=\"text-align:right;\"><button class=\"clone-button pure-button pure-button-secondary\">Link</button>&nbsp;<button class=\"copy-button pure-button pure-button-secondary\">Copy</button></div></div></li>");
			
			}
				
		}
} else {
	$('.findbox-searchresults').html("<li><span class=\"no-box-results\">No Results</span></li>");
	   }
 });
});
}

