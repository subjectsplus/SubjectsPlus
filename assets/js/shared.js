///////////////
// shared.js
// called by footer.php
// available to all files in admin
///////////////

$(document).ready(function($){
    //$("#maincontent").hide();
    // remove the feedback message once someone does something

    $('input, select').livequery('focus', function() {

        $(".feedback").fadeOut(400);

    });

    //////////////
    // Dropdown Menus
    // add hoverintent goodness
    ///////////////

    function addDroppy(){
        $(this).find("ul.subnav").slideDown('fast').show();
        return;

    }

    function removeDroppy(){
        $(this).find("ul.subnav").slideUp('slow');
        return;
    }

    var droppyConfig = {
        interval: 50,
        sensitivity: 4,
        over: addDroppy,
        timeout: 500,
        out: removeDroppy
    };

    $("li.zoom").hoverIntent(droppyConfig);

    ///////////////////////
    // load new photo
    ///////////////////////
    $('#load_photo').colorbox({
        iframe: true,
        innerWidth:"500",
        innerHeight:"400",

        onClosed:function() {

            $("#headshot").attr("src", headshot_location);

        }
    });

    ///////////////////////
    // Reset Password
    ///////////////////////

    $('#reset_password').colorbox({
        iframe: true,
        innerWidth:"500",
        innerHeight:"400",

        onClosed:function() {

            //$("#headshot").attr("src", headshot_location);

        }
    });

    /////////////////
    // Load small modal window
    ////////////////

    $("a[class*=showsmall]").colorbox({
        iframe: true,
        innerWidth:600,
        innerHeight:500
    });

    /////////////////
    // Load medium modal window
    ////////////////

    $("a[class*=showmedium]").colorbox({
        iframe: true,
        innerWidth:"90%",
        innerHeight:"80%",
        maxWidth: "1100px",
        maxHeight: "800px"
    });

    // This version reloads the page upon window close
    $("a[class*=showmedium-reloader]").colorbox({
        iframe: true,
        innerWidth:"90%",
        innerHeight:"80%",
        maxWidth: "1100px",
        maxHeight: "800px",

        onClosed:function() {
            window.location.href = window.location.href;
        }
    });

	//Tooltip javscript
	////////////////
	// on hover to add tooltip
	///////////////

	var config = {
		interval: 50,
		sensitivity: 4,
		over: function()
		{
			$(this).append("<div class=\"tooltip\" style=\"top: " + ( $(this).position().top - 105 ) + "px; left: " + 	( $(this).position().left + 10 )
			+ "px;\"><div align=\"center\">"
			+ $(this).children('img.tooltip').attr('data-notes') + "</div></div>");
		}, // function = onMouseOver callback (REQUIRED)
		timeout: 500, // number = milliseconds delay before onMouseOut
		out: function()
		{
			$(this).children('div.tooltip').remove();
		} // function = onMouseOut callback (REQUIRED)
	};

	$("span.tooltipcontainer").hoverIntent( config );

}); // end jquery

// Regular js functions

$.fn.defaultText = function(value){

    var element = this.eq(0);
    element.data('defaultText',value);

    element.focus(function(){
        if(element.val() == value){
            element.val('').removeClass('defaultText');
        }
    }).blur(function(){
        if(element.val() == '' || element.val() == value){
            element.addClass('defaultText').val(value);

        }
    });

    return element.blur();
}
