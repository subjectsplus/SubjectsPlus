
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
    	
   // 	var clone_id = Math.floor(Math.random()*1000001);
    	console.log($(this).parent().parent().parent().attr('data-pluslet-id'));
    	
    	var origin_id = $(this).parent().parent().parent().attr('data-pluslet-id');   	
       	
    	var origin_title = $(this).parent().parent().parent().text().replace("CloneCopy","");

    	
    	
    	plantClone('','Clone',origin_id, origin_title);
    	
   
    });
    
    $('body').on('click', '.copy-button',function() {
    	
    	   // 	var clone_id = Math.floor(Math.random()*1000001);
    	    	var origin_id = $(this).parent().parent().parent().attr('data-pluslet-id');   	
    	    	var origin_title = $(this).parent().parent().parent().text().replace("CloneCopy","");

    	    	plantClone(origin_id,'Basic', origin_title);
    	    
    	});
    
    
    
    
}
