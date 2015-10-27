function setupTabs( lstrSelector )
{
    ////////////////
    // Setup Tabs
    ////////////////

    $(lstrSelector).unbind('click');
    $(lstrSelector).on('click', function(){
        var tab_id = $(this).attr("id").split("-");
        // fade out all  tabs
        $("#tab_body-0").fadeOut("");
        $("#tab_body-" + tab_id[1]).fadeIn("slow");
        //fade in our tab


    });
}