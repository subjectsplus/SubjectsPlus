$(document).ready(function () {
	
	var fdbx = getFindBoxSearch();
    fdbx.init();
    var tabs = getTabs();
    tabs.init();
    var guide = getGuideBase();
    guide.init();
    var rs = getResourceList();
    rs.init();
    var fly = getFlyout();
    fly.init();
    var layout = getLayout();
    layout.init();

    var clone_pluslet_id;

    var subjectId = $("#guide-parent-wrap").data().subjectId;
    
    makeDropable(".dropspotty");
    makeDropable(".cke");
    makeSortable(".sort-column");
    makeSortable(".sptab", 'sections');
    makeDraggable(".draggable");

    setupSaveButton('#save_guide');

    makeEditable('a[id*=edit]', subjectId);
    makeDeleteable('a[id*=delete]');
    makeDeleteable('.section_remove', 'sections');

    setupAllColorboxes();
    setupMiscEvents();
    setupMiscClickEvents();

    makeHelpable("img[class*=help-]");
    makeAddSection('a[id="add_section"]');


    mark_as_linked();

    mark_as_favorite();

    // Append an intital section

    if ($('[id^=section]').length) {

    } else {

        if (document.URL.indexOf('guide.php') > 0) {

            $.ajax({
                url: "helpers/section_data.php",
                type: "POST",
                data: { action: 'create' },
                dataType: "html",
                success: function (html) {
                    $('div#tabs-0').append(html);
                }
            });
        }

    }


    $(".box-item").on('drag', function () {
        //$('#box_options').hide();
    });

    $(".draggable").draggable({
        helper: 'clone', // Use a cloned helper
        appendTo: 'body', // Append helper to body so you can hide the parent
        start: function () {
            // Make the original transparent to avoid re-flowing of the elements
            // This makes the illusion of dragging the original item
            $(this).css({ opacity: 0 });
        },
        stop: function () {
            // Show original after dragging stops
            $(this).css({ opacity: 1 });

        }
    });


    $(".box-item")
    .mousedown(function () {
        makeDropable(".dropspotty");
        console.log('make droppable');
        makeDropable(".cke");
        makeSortable(".sort-column");
        makeSortable(".sptab", 'sections');
        makeDraggable(".draggable");
    });
    
    
     //close box settings panel
     $( ".close-settings" ).click(function() { 
         $(this).parent(".box_settings").hide();
     });



   //Expand/Collapse Trigger CSS for all Pluslets on a Tab

     $( "#expand_tab" ).click(function() { 
       $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
       $('.pluslet_body').toggle();
       $('.pluslet_body').toggleClass('pluslet_body_closed');
     });
  

});