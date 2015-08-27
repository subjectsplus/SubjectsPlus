function setupMiscEvents()
{
    /////////////////////////
    // HIDE NEW PLUSLETS DRAGGABLE SECTION
    /////////////////////////

    $(document.body).on('click','#closer', function(event) {
        $("#all-results").fadeOut("slow");
        return false;
    });

    /////////////////////////
    // HIDE FIND PLUSLETS DRAGGABLE SECTION
    /////////////////////////

    $(document.body).on('click','#closer2', function(event) {
        $("#find-results").fadeOut("slow");
        return false;
    });
    //////////////////////////////
    // .togglebody makes the body of a pluslet show or disappear
    //////////////////////////////

    $(document.body).on('click','.togglebody', function(event) {

        $(this).parent().parent().next('.pluslet_body').toggle('slow');
    });

    //////////////////////////////
    // .togglenew causes the all-results div to fade in
    // -- loads get_pluslets.php
    // mod agd OCT 2010
    //////////////////////////////

    $(document.body).on('click','.togglenew', function(event) {
 
        $("#all-results").fadeIn(2000);

    });

    ///////////////////////////////
    // Draw attention to TOC linked item
    ///////////////////////////////

    $(document.body).on('click','a[id*=boxid-]', function(event) {
    	var tab_id = $(this).attr('id').split('-')[1];
    	var box_id = $(this).attr('id').split('-')[2];
        var selected_box = ".pluslet-" + box_id;

    	$('#tabs').tabs('select', tab_id);
        $(selected_box).effect("pulsate", {
            times:1
        }, 2000);
	//$(selected_box).animateHighlight("#dd0000", 1000);

    });

    ////////////////////
    // Make page tabs clickable
    ///////////////////
    $(document.body).on('click','a[id*=tab-]', function(event) {
        var tab_id = $(this).attr("id").split("-");
        //var selected_tab = "#pluslet-" + box_id[1];
        setupTabs(tab_id[1]);

    });

    ////////////////////
    // box-settings bind to show when clicking on gear or edit icon.
    ///////////////////
    $(document).on('click', 'a[id*=settings-]', function(event) {
        $(this).parent().parent().parent().find('.box_settings').toggle();
    });

     
    
    ////////////////////
    // on select change show save guide
    ///////////////////
    $(document).on('change', 'select[id^=titlebar-styling]', function(event) {
	var pluslet_id = $(this).parent().parent().parent().parent().attr('id') ;

	if( $('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') === -1)
	{
	    $('#' + pluslet_id).attr('name', 'modified-pluslet-' + $('#' + pluslet_id).attr('name'));
	}

	$("#response").hide();
	$("#save_guide").fadeIn();
    });


    ////////////////////
    // Make titlebar options box clickable
    ///////////////////
    $(document).on('change', '.onoffswitch-checkbox', function() {

        var pluslet_id = $(this).parent().parent().parent().parent().attr('id') ;

    	if( $('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') == -1)
    	{
	    $('#' + pluslet_id).attr('name', 'modified-pluslet-' + $('#' + pluslet_id).attr('name'));
    	}

    	$("#response").hide();
        $("#save_guide").fadeIn();
    });

}
