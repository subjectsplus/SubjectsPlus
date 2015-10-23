
function setupMiscClickEvents()
{
    //////////////////////////////
    // .togglenew causes the all-results div to fade in
    // -- loads get_pluslets.php
    // mod agd OCT 2010
    //////////////////////////////

    $('#newbox1').click(function(event) {
        $("#all-results").toggle('slow');
    });

    ///////////////////////////////////
    // #hide_header makes the SubsPlus header appear/disappear; it's hidden onload
    ///////////////////////////////////

    $('#hide_header').click(function(event) {
        $("#header, #subnavcontainer").toggle();
    });
    
    $(".box-item").dblclick('click', function(event) {
        var edit_id = $(this).attr("id").split("-");
        dropPluset('', edit_id[2], '');

    });


    $('body').on('click', '.clone-button',function() {


    	var origin_id = $(this).parent().parent().parent().attr('data-pluslet-id');
    	var origin_title = $(this).parent().parent().find('.box-search-label').text();

    	dropPluset('','Clone',origin_id, origin_title);

    });
    
    $('body').on('click', '.copy-button',function() {
    	
    	    	var origin_id = $(this).parent().parent().parent().attr('data-pluslet-id');       	    	
    	    	var origin_title = $(this).parent().parent().parent().text().replace(" /Clone Copy/g","");

    	    	
    	    	// Get the type and pass it to the dropPluset function
    	    	var type = $("#pluslet-" + origin_id).attr('name');
    	    	dropPluset(origin_id, type, origin_title);
    	    
    	});



    $('body').on('dblclick', '.clone-favorite', function() {

        var origin_id = $(this).attr('data-pluslet-id');
        var origin_title = $(this).html();

        dropPluset('','Clone',origin_id, origin_title);

    });

}