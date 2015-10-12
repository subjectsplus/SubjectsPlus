$(document).ready(function() {

	$('.box_options_container, .fav-boxes-content, .db-list-results, .user_guides_display').enscroll({
	    verticalTrackClass: 'track',
	    verticalHandleClass: 'handle',
	    minScrollbarLength: 28
	});


	$('.find-box-tab-list-content .pluslet-list, .find-box-tab-list-content .findbox-searchresults, .databases-searchresults').enscroll({
	    verticalTrackClass: 'track2',
	    verticalHandleClass: 'handle2',
	    minScrollbarLength: 28
	});

	    
}); // End document.ready

	



