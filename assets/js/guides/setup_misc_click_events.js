
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
    
    $(".box-item").click(function(event) {
        var edit_id = $(this).attr("id").split("-");
        plantClone('', edit_id[2], '');

    });
    
    $('body').on('click', '.clone-button',function() {
    	

    	var origin_id = $(this).parent().parent().parent().attr('data-pluslet-id');   	       	
    	var origin_title = $(this).parent().parent().find('.box-search-label').text();


    	plantClone('','Clone',origin_id, origin_title);
    	
   
    });
    
    $('body').on('click', '.copy-button',function() {
    	
    	    	var origin_id = $(this).parent().parent().parent().attr('data-pluslet-id');       	    	
    	    	var origin_title = $(this).parent().parent().parent().text().replace(" /Clone Copy/g","");

    	    	plantClone(origin_id,'Basic', origin_title);
    	    
    	});



    $('body').on('click', '.clone-favorite',function() {

        var origin_id = $(this).attr('data-pluslet-id');
        var origin_title = $(this).html();

        plantClone('','Clone',origin_id, origin_title);

    });

}