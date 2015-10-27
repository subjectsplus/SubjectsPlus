$(document).ready(function() {

		
		
		
		var clone_pluslet_id;
		
	    makeDropable(".dropspotty");
	    console.log('make droppable');
	    makeDropable(".cke");
	    makeSortable(".sort-column");
	    makeSortable(".sptab", 'sections');
	    makeDraggable(".draggable");

	    setupSaveButton('#save_guide');

	    makeEditable('a[id*=edit]');
	    makeDeleteable('a[id*=delete]');
	    makeDeleteable('.section_remove', 'sections');

	    setupAllColorboxes();
	    setupMiscEvents();
	    setupMiscClickEvents();

	    makeHelpable("img[class*=help-]");
	    setupTabs('a[id*=tab-]');
	    makeAddSection('a[id="add_section"]');


	    mark_as_linked();

	    mark_as_favorite();

	  //  activateFindboxSearch();
	    
	    var rs = getResourceList();
	    rs.init();
	    var fdbx = getFindBoxSearch();
	    fdbx.init();
	    var guide = getGuideBase();
	    guide.init();
	    

	    
	    // Append an intital section

	    if ($('[id^=section]').length) {

	    } else {
	        
		if (document.URL.indexOf('guide.php') > 0) {

		    $.ajax({
			url: "helpers/section_data.php",
			type: "POST",
			data: { action : 'create' },
			dataType: "html",
			success: function(html) {
			    $('div#tabs-0').append(html);
			}
		    });
		}

	    }

	    makeSectionSlider('div[id^="slider_section"]');

	    $(".box-item").on('drag', function()
			      {
	    			  //$('#box_options').hide();
			      });

	    $(".draggable").draggable({
		helper: 'clone', // Use a cloned helper
		appendTo: 'body', // Append helper to body so you can hide the parent
		start: function(){
		    // Make the original transparent to avoid re-flowing of the elements
		    // This makes the illusion of dragging the original item
		    $(this).css({opacity:0});
		},
		stop: function(){
		    // Show original after dragging stops
		    $(this).css({opacity:1});

		}
	    });
	    

	    $(".box-item")
	    .mousedown(function() {
	    	makeDropable(".dropspotty");
		    console.log('make droppable');
		    makeDropable(".cke");
		    makeSortable(".sort-column");
		    makeSortable(".sptab", 'sections');
		    makeDraggable(".draggable");
	    });
	    
});
	


// End $ within document.ready