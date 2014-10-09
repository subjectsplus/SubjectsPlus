// @todo close new box drop down immediately upon dropping something
// @todo if you click on a choice from the dropdown, insert it into left col (i.e.
// make the drag optional--or will that work?)

jQuery(document).ready(function(){

    makeDropable(".dropspotty");
    makeDropable(".cke");

    makeSortable(".sort-column");
    makeSortable(".sptab", 'sections');

    makeDraggable(".draggable");

    setupSaveButton('#save_guide');

    makeEditable('a[id*=edit]');

    makeDeleteable('a[id*=delete]');
    makeDeleteable('.section_remove', 'sections');

    setupAllColorboxes();

    setupMiscLiveQueries();

    setupMiscClickEvents();

    makeHelpable("img[class*=help-]");

    setupTabs('a[id*=tab-]');

    makeAddSection('a[id="add_section"]');


    

    // Append an intital section

    if (jQuery('[id^=section]').length) {

        //  console.log("Looks like there is a section already.");

    } else {

        
	if (document.URL.indexOf('guide.php') > 0) {

	    jQuery.ajax({
		url: "helpers/section_data.php",
		type: "POST",
		data: { action : 'create' },
		dataType: "html",
		success: function(html) {
		    $('div#tabs-0').append(html);
		    //$(document).scrollTop($('body').height());
	            
		}
	    });
	}

    }


    makeSectionSlider('div[id^="slider_section"]');

    jQuery(".box-item").on('drag', function()
			   {
    			       jQuery('#box_options').hide();
			   });

    jQuery(".draggable").draggable({
	helper: 'clone', // Use a cloned helper
	appendTo: 'body', // Append helper to body so you can hide the parent
	start: function(){
	    // Make the original transparent to avoid re-flowing of the elements
	    // This makes the illusion of dragging the original item
	    jQuery(this).css({opacity:0});
	},
	stop: function(){
	    // Show original after dragging stops
	    jQuery(this).css({opacity:1});

	}
    });


    
    


}); // End jQuery within document.ready




function makeDropable( lstrSelector )
{
    ////////////////////////////////
    // SET UP DROP SPOTS
    // --makes divs with class of "dropspotty" droppables
    // accepts things with class of "draggable"
    //
    ////////////////////////////////

    jQuery(lstrSelector).livequery(function() {

	jQuery(this).droppable({

	    iframeFix: true,
	    accept: ".draggable, .pluslet",
	    drop: function(event, props) {

		jQuery(this).removeClass("drop_hover");
		jQuery(this).css("background", "");

		//do if droppable tab
		if(jQuery(this).children('a[href^="#tabs-"]').length > 0 )
		{
		    if( jQuery(props.draggable).hasClass('pluslet') )
		    {
			if( !jQuery(this).hasClass('ui-state-active') )
			{
			    var drop_tab = this;

			    //make sure to show all of pluslet before hiding
			    jQuery(props.draggable).children('.pluslet_body').show();
			    jQuery(props.draggable).children().children('.titlebar_text').show();
			    jQuery(props.draggable).children().children('.titlebar_options').show();


                            jQuery(props.draggable).hide('slow', function()
							 {
							     jQuery(this).remove();
							     jQuery(drop_tab).children('a[href^="#tabs-"]').click();
							     jQuery('.portal-column-1:visible').prepend(this);
							     jQuery(this).height("auto");
							     jQuery(this).width("auto");
							     jQuery(this).show("slow");

							     jQuery("#response").hide();
							     //Make save button appear, since there has been a change to the page
							     jQuery("#save_guide").fadeIn();
							 });
			}
		    }

		    return;
		}

		//only do for class draggable
		if( jQuery(props.draggable).hasClass('draggable') )
		{

		    var drop_id = jQuery(this).attr("id");
		    var drag_id = jQuery(props.draggable).attr("id");
		    //alert(drag_id);
		    var pluslet_title = jQuery(props.draggable).html();
		    if( jQuery(this).hasClass('cke') )
		    {
			if( jQuery(props.draggable).attr("ckclass") != "" )
			{
			    CKEDITOR.instances[jQuery(this).attr('id').replace('cke_', '')].openDialog(jQuery(props.draggable).attr("ckclass") + 'Dialog');
			}else
			{
			    alert( 'This pluslet isn\'t configured to drag into CKEditor!' );
			}
		    }else
		    {
			// if there can only be one, could remove from list items
			var drop_id = jQuery(this).attr("id");
			var drag_id = jQuery(props.draggable).attr("id");
			//alert(drag_id);
			var pluslet_title = jQuery(props.draggable).html();

			// Create new node below, using a random number

			var randomnumber=Math.floor(Math.random()*1000001);
			jQuery(this).next('div').prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");
			//alert (drag_id + " drop: " + drop_id);

			// Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory
			jQuery("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
			    from: drag_id,
			    to: drop_id,
			    pluslet_title: pluslet_title,
			    flag: 'drop',
			    this_subject_id:subject_id
			},
									   function() {

									       // 1.  remove the wrapper
									       // 2. put the contents of the div into a variable
									       // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
									       var cnt = jQuery("#new-" + randomnumber).contents();
									       jQuery("#new-" + randomnumber).replaceWith(cnt);
									       //alert(jQuery(this).attr("id"));
									       jQuery(this).addClass("unsortable");

									       jQuery("#response").hide();
									       //Make save button appear, since there has been a change to the page
									       jQuery("#save_guide").fadeIn();


									       jQuery("a[class*=showmedium]").colorbox({
										   iframe: true,
										   innerWidth:"90%",
										   innerHeight:"80%",
										   maxWidth: "1100px",
										   maxHeight: "800px"
									       });

									       makeHelpable("img[class*=help-]");

									   });
		    }
		}
	    },
	    over: function(event, ui)
	    {
		if(jQuery(this).children('a[href^="#tabs-"]').length > 0 && jQuery(ui.draggable).hasClass('pluslet')
		   && !jQuery(this).hasClass('ui-state-active'))
		{
		    jQuery(this).css("background", "none repeat scroll 0% 0% #C03957");
		}

		if(jQuery(this).children('a[href^="#tabs-"]').length < 1 && !jQuery(ui.draggable).hasClass('pluslet'))
		{
		    jQuery(this).addClass("drop_hover");
		}
	    },
	    out: function(event, ui)
	    {
		if(jQuery(this).children('a[href^="#tabs-"]').length > 0 && jQuery(ui.draggable).hasClass('pluslet'))
		{
		    jQuery(this).css("background", "");
		}

		if(jQuery(this).children('a[href^="#tabs-"]').length < 1 && !jQuery(ui.draggable).hasClass('pluslet'))
		{
		    jQuery(this).removeClass("drop_hover");
		}
	    }
	});

    });



}

function makeSortable( lstrSelector, lstrType )
{

    ////////////////////////////
    // MAKE COLUMNS SORTABLE
    // Make "Save Changes" button appear on sorting
    ////////////////////////////

    jQuery(lstrSelector).livequery(function() {

	if( lstrType == 'sections' )
	{
	    jQuery(this).sortable({
		opacity: 0.7,
		cancel: '.unsortable',
		handle: 'img.section_sort',
		update: function(event, ui) {
		    jQuery("#response").hide();
		    jQuery("#save_guide").fadeIn();

		},
		start: function(event, ui)
		{
		    jQuery(ui.item).find('.dropspotty').hide();
		    jQuery(ui.item).find('.pluslet').hide();
		    jQuery(ui.item).height('2em');
		    jQuery(ui.item).width('auto');
		},
		stop: function(event, ui)
		{
		    jQuery(ui.item).find('.dropspotty').show();
		    jQuery(ui.item).find('.pluslet').show();
		}
	    });
	}else
	{
	    jQuery(this).sortable({

		connectWith :['.portal-column-0', '.portal-column-1', '.portal-column-2'],
		opacity: 0.7,
		tolerance: 'intersect',
		cancel: '.unsortable',
		handle: 'img.pluslet_sort',
		update: function(event, ui) {
		    jQuery("#response").hide();
		    jQuery("#save_guide").fadeIn();

		},
		start: function(event, ui)
		{
		    jQuery(ui.item).children('.pluslet_body').hide();
		    jQuery(ui.item).children().children('.titlebar_text').hide();
		    jQuery(ui.item).children().children('.titlebar_options').hide();
		    jQuery(ui.item).height('2em');
		    jQuery(ui.item).width('auto');
		},
		stop: function(event, ui)
		{
		    jQuery(ui.item).children('.pluslet_body').show();
		    jQuery(ui.item).children().children('.titlebar_text').show();
		    jQuery(ui.item).children().children('.titlebar_options').show();

		}
	    });
	}
    });
}

function makeDraggable( lstrSelector )
{
    ////////////////////////////////
    // SET UP DRAGGABLE
    // --makes anyting with class of "draggable" draggable
    ////////////////////////////////

    jQuery(lstrSelector).livequery(function() {

	jQuery(this).draggable({
	    ghosting:	true,
	    opacity:	0.5,
	    revert: true,
	    fx:			300,
	    cursor: 'pointer',
	    helper: 'clone',
	    zIndex: 350
	});
    });
}

function setupSaveButton( lstrSelector )
{
    ////////////////////////////
    // SAVE GUIDE'S LAYOUT
    // -- this saves everything on page
    ////////////////////////////

    jQuery(lstrSelector).livequery('click', function(event) {

	// make sure our required fields have values before continuing
	var test_req = checkRequired();

	if (test_req == 1) {
	    alert("You must complete all required form fields.");
	    return false;
	}

	// 1.  Look for new- or modified-pluslet
	// 2.  Check to make sure data is okay
	// 3.  Save to DB
	// 4.  Recreate pluslet with ID
	// 5.  Save layout

	////////////////////
	// modified-pluslet
	// loop through each pluslet
	///////////////////
	jQuery('div[name*=modified-pluslet]').each(function() {

	    var update_id = jQuery(this).attr("id").split("-");
	    var this_id = update_id[1];

	    //prepare the pluslets for saving
	    preparePluslets("modified", this_id, this);
	});

	////////////////////////
	// Now the new pluslets
	////////////////////////

	jQuery('div[name*=new-pluslet]').each(function() {

	    var insert_id = jQuery(this).attr("id"); // just a random gen number

	    //prepare pluslets for saving
	    preparePluslets("new", insert_id, this);
	});

	//////////////////////
	// We're good, save the guide layout
	// insert a pause so the new pluslet is found
	//////////////////////
	jQuery("#response").hide();
	jQuery("#save_guide").fadeOut();
	//jQuery("#savour").append('<span class="loader"><img src="images/loading_animated.gif" height="30" /></span>');
	saveGuide();

	return false;

    });

    ///////////////////////
    // preparePluslets FUNCTION
    // called to prepare all the pluslets before saving them
    ///////////////////////

    function preparePluslets( lstrType, lintID, lobjThis )
    {
    	var lboolSettingsOnly = false;

	//based on type set variables
	switch( lstrType.toLowerCase() )
	{
	case "modified":
	    {
		//used to get contents of CKeditor box
		var lstrInstance = "pluslet-update-body-" + lintID;
		//Title of item
		var lstrTitle = addslashes(jQuery("#pluslet-update-title-" + lintID).val());
		if (lstrTitle === "undefined") {
		    b = jQuery(".pluslet-" + lintID).find('.titlebar_text').clone();
		    b.children().remove();
		    lstrTitle = b.text().trim();
		    lboolSettingsOnly = true;
		}

		//Div Selector
		var lstrDiv = "#pluslet-" + lintID;
		//depending update_id
		var lintUID = lintID;
		break;
	    }
	case "new":
	    {
		//used to get contents of CKeditor box
		var lstrInstance = "pluslet-new-body-" + lintID;
		//Title of item
		var lstrTitle = addslashes(jQuery("#pluslet-new-title-" + lintID).val());
		//Div Selector
		var lstrDiv = "#" + lintID;
		//depending update_id
		var lintUID = '';
		break;
	    }
	}

	///////////////////////////////////////////////////////////////
	// The box settings  are available on all pluslets potentially
	// --they determine if titlebar shows, titlebar styling, if body
	// is collapsed by default, and if body is suppressed (for a header pluslet)
	////////////////////////////////////////////////////////////////

	var boxsetting_hide_titlebar = Number(jQuery('input[id=notitle-'+lintID+']').is(':checked'));
	var boxsetting_collapse_titlebar = Number(jQuery('input[id=start-collapsed-'+lintID+']').is(':checked'));
	var boxsetting_titlebar_styling = jQuery('select[id=titlebar-styling-'+lintID+']').val();




	//		console.log (boxsetting_hide_titlebar);
	//        console.log (boxsetting_collapse_titlebar);
	//        console.log(boxsetting_titlebar_styling);

	//alert(lintID);
	//alert(boxsetting_titlebar_styling);
	//////////////////////////////////////////////////////////////////
	// Check the pluslet's "name" value to see if there is a number
	// --If it is numeric, it's a "normal" item with a ckeditor instance
	// collecting the "body" information
	//////////////////////////////////////////////////////////////

	var item_type = jQuery(lobjThis).attr("name").split("-");

	// Loop through the box types
	switch (item_type[2]) {
	case "Basic":
            if (typeof CKEDITOR != 'undefined' && !lboolSettingsOnly) {

                var pbody = addslashes(CKEDITOR.instances[lstrInstance].getData());

            } else {

                var pbody = jQuery('#pluslet-' + lintID).find('.pluslet_body').html()
            }

	    var pitem_type = "Basic";
	    var pspecial = '';
	    break;
	case "Heading":
	    var pbody = ""; // headings have no body
	    var pitem_type = "Heading";
	    var pspecial = '';
	    break;
	case "TOC":
	    var pbody = "";
	    var pitem_type = "TOC";
	    var tickedBoxes = new Array();
	    jQuery('input[name=checkbox-'+lintID+']:checked').each(function() {
		// alert(this.value);
		tickedBoxes.push(this.value);

	    });
	    var pspecial = '{"ticked":"' + tickedBoxes + '"}';
	    //alert(pspecial);
	    break;
	case "Feed":
	    var pbody = jQuery('input[name=' + lstrInstance + ']').val();
	    var pfeed_type = jQuery('select[name=feed_type-'+lintID+']').val();
	    var pnum_items = jQuery('input[name=displaynum-'+lintID+']').val();
	    var pshow_desc = jQuery('input[name=showdesc-'+lintID+']:checked').val();
	    var pshow_feed = jQuery('input[name=showfeed-'+lintID+']:checked').val();

	    var pspecial = '{"num_items":'
		    + pnum_items +
		    ',  "show_desc":'
		    + pshow_desc +
		    ', "show_feed": '
		    + pshow_feed +
		    ', "feed_type": "'
		    + pfeed_type +
		    '"}';

	    var pitem_type = "Feed";
	    //pitems = " +pnum_items + " pshow_desc = " + pshow_desc + " pshow_feed = " +pshow_feed
	    //alert ("Feed update:" + pspecial );
	    break;
	default:
	    var pbody = jQuery('#' + item_type[2] + '-body').html();
	    pbody = pbody == undefined ? ""  : pbody;
	    var pitem_type = item_type[2];
	    var extra = {};

            //parse text inputs to create extra fields
	    jQuery(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=text]').each(function()
											     {
												 var name_split = jQuery(this).attr("name").split("-");

												 extra[name_split[2]] = jQuery(this).val();
											     });

            //parse textareas to create extra fields
	    jQuery(lobjThis).find('textarea[name^=' + item_type[2] + '-extra]').each(function()
										     {
											 var name_split = jQuery(this).attr("name").split("-");

											 extra[name_split[2]] = jQuery(this).val();
										     });

            //parse selectboxes to create extra fields
	    jQuery(lobjThis).find('select[name^=' + item_type[2] + '-extra]').each(function()
										   {
										       var name_split = jQuery(this).attr("name").split("-");

										       extra[name_split[2]] = jQuery(this).val();
										   });

            //parse radio inputs to create extra fields
            jQuery(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=radio]').each(function()
											      {
												  var name_split = jQuery(this).attr("name").split("-");
												  extra[name_split[2]] = typeof extra[name_split[2]] == 'undefined' ? '' : extra[name_split[2]];

												  if( $(this).is(':checked') )
												      extra[name_split[2]] = jQuery(this).val();
											      });

            //parse checkboxe inputs to create extra fields
            jQuery(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=checkbox]').each(function()
												 {
												     var name_split = jQuery(this).attr("name").split("-");
												     extra[name_split[2]] = typeof extra[name_split[2]] == 'undefined' ? [] : extra[name_split[2]];

												     if( $(this).is(':checked') )
													 extra[name_split[2]].push( jQuery(this).val() );
												 });

	    var pspecial = jQuery.isEmptyObject( extra ) ? "" : JSON.stringify(extra);

	    break;
	}

	//only check clone if modified pluslet
	if( lstrType == 'modified')
	{
	    //////////////////////
	    // Clone?
	    // If it's a clone, add a new entry to DB
	    /////////////////////

	    var clone = jQuery("#pluslet-" + lintID).attr("class");

	    if (clone.indexOf("clone")!=-1) {
		var ourflag = 'insert';
		var isclone = 1;
		//alert("it's a clone!");
	    } else {
		var ourflag = 'update';
		var isclone = 0;
	    }

	    //only settings update
	    if(lboolSettingsOnly)
	    {
		ourflag = 'settings';
	    }
	}else
	{
	    var ourflag = 'insert';
	    var isclone = 0;
	}

	////////////////////////
	// Post the data to guide_data.php
	// which will do an insert or update as appropriate
	//
	// **changed by dgonzalez 08/2013 so that request is not done
	// asynchronously so that setTimeout to save guide is no longer needed.
	////////////////////////

	jQuery.ajax({
	    url: "helpers/guide_data.php",
	    data: {
		update_id: lintUID,
		pluslet_title:lstrTitle,
		pluslet_body:pbody,
		flag: ourflag,
		staff_id: user_id,
		item_type: pitem_type,
		clone:isclone,
		special: pspecial,
		this_subject_id: subject_id,
		boxsetting_hide_titlebar: boxsetting_hide_titlebar,
		boxsetting_collapse_titlebar: boxsetting_collapse_titlebar,
		boxsetting_titlebar_styling: boxsetting_titlebar_styling

	    },
	    type: "POST",
	    success: function(response) {

		//load response into pluslet
		jQuery(lstrDiv).html(response);

		// check if it's an insert or an update, and name div accordingly
		if (ourflag == "update" || ourflag == "settings" || isclone == 1) {
		    var this_div = '#pluslet-'+lintID;
		} else {
		    var this_div = '#'+lintID;
		}

		// 1.  remove the wrapper
		// 2. put the contents of the div into a variable
		// 3.  replace parent div (i.e., id="xxxxxx") with the content made by loaded file
		var cnt = jQuery(this_div).contents();
		//alert(cnt);
		jQuery(this_div).replaceWith(cnt);
	    },
	    async: false
	});
    }

    ///////////////////////
    // saveGuide FUNCTION
    // called at end of previous section
    //////////////////////

    function saveGuide() {

	var lobjTabs = [];

	jQuery('a[href^="#tab"]').each(function(){
	    var lstrName = jQuery(this).text();
	    var lstrExternal = jQuery(this).parent('li').attr('data-external-link');
	    var lintVisibility = parseInt(jQuery(this).parent('li').attr('data-visibility'));
	    var tab_id = $(this).attr("href").split("tabs-")[1];

	    var lobjTab = {};
	    lobjTab.name = lstrName;
	    lobjTab.external = lstrExternal;
	    lobjTab.visibility = lintVisibility;
	    lobjTab.sections = [];

	    jQuery('div#tabs-' + tab_id + ' div[id^="section_"]').each(function()
								       {
									   var section_id = $(this).attr("id").split("section_")[1];
									   var lobjSection = {};
									   lobjSection.layout = $(this).attr("data-layout");

									   jQuery('div#section_' + section_id + ' div.portal-column-0').sortable();
									   jQuery('div#section_' + section_id + ' div.portal-column-1').sortable();
									   jQuery('div#section_' + section_id + ' div.portal-column-2').sortable();

									   lobjSection.left_data = jQuery('div#section_' + section_id +  ' div.portal-column-0').sortable('serialize');
									   lobjSection.center_data = jQuery('div#section_' + section_id +  ' div.portal-column-1').sortable('serialize');
									   lobjSection.sidebar_data = jQuery('div#section_' + section_id +  ' div.portal-column-2').sortable('serialize');

									   lobjTab.sections.push(lobjSection);
								       });

	    lobjTabs.push(lobjTab);
	});

	lstrTabs = JSON.stringify(lobjTabs);

	jQuery("#response").load("helpers/save_guide.php", {
	    this_subject_id: subject_id,
	    user_name: user_name,
	    tabs: lstrTabs
	},
				 function() {

				     jQuery("#response").fadeIn();
				     refreshFeeds();
				     //jQuery(".sort-column").sortable();

				 });

    }

    //make saveGuide global
    window.saveGuide = saveGuide;
}

function makeEditable( lstrSelector )
{
    ////////////////////////////////
    // MODIFY PLUSLET -- on click of edit (pencil) icon
    ////////////////////////////////

    // console.log(lstrSelector);
    jQuery(lstrSelector).livequery('click', function(event) {

        var edit_id = jQuery(this).attr("id").split("-");
        //  console.log(edit_id);
        //alert(edit_id[1]);
        ////////////
        // Clone?
        ////////////

        var clone = jQuery("#pluslet-" + edit_id[1]).attr("class");
        if (clone.indexOf("clone")!=-1) {
            var isclone = 1;
        } else {
            var isclone = 0;
        }

        /////////////////////////////////////
        // Load the form elements for editing
        /////////////////////////////////////

        jQuery('#' + 'pluslet-' + edit_id[1]).load("helpers/guide_data.php", {
            edit: edit_id[1],
            clone: isclone,
            flag: 'modify',
            type: edit_id[2],
            this_subject_id: subject_id
        },
						   function() {
						       ///////////////////////////////////////////////
						       // 1.  remove the wrapper
						       // 2. put the contents of the div into a variable
						       // 3.  replace parent div (i.e., id="xxxxxx") with the content made by loaded file
						       ///////////////////////////////////////////////

						       var cnt = jQuery('#' + 'pluslet-' + edit_id[1]).contents();
						       jQuery('#' + 'pluslet-' + edit_id[1]).replaceWith(cnt);

						       /////////////////////////////////////
						       // Make unsortable for the time being
						       /////////////////////////////////////

						       jQuery("#pluslet-" + edit_id[1]).addClass("unsortable");

						       //////////////////////////////////////
						       // We're changing the attribute here for the global save
						       //////////////////////////////////////

						       if (edit_id[2] != undefined) {
							   var new_name = "modified-pluslet-" + edit_id[2];
							   jQuery("#pluslet-" + edit_id[1]).attr("name", new_name);
						       } else {
							   jQuery("#pluslet-" + edit_id[1]).attr("name", "modified-pluslet");
						       }

    						       makeHelpable("img[class*=help-]");

						   });

        //Make save button appear, since there has been a change to the page
        jQuery("#response").hide();
        jQuery("#save_guide").fadeIn();

        return false;
    });
}

function makeDeleteable( lstrSelector, lstrType )
{
    if( lstrType == 'sections' )
    {
	/////////////////////////////
	// DELETE SECTION
	/////////////////////////////
	jQuery('.guidewrapper').on('click', lstrSelector ,function(event) {

	    var delete_id = jQuery(this).parent().parent().attr("id").split("_")[1];
	    var element_deletion = this;
	    jQuery('<div class="delete_confirm" title="Are you sure?">All content in this section will be deleted.</div>').dialog({
		autoOpen: true,
		modal: true,
		width: "auto",
		height: "auto",
		resizable: false,
		buttons: {
		    "Yes": function() {
			// Remove node
			jQuery(element_deletion).parent().parent().remove();
			jQuery("#response").hide();
			window.saveGuide();
			jQuery("#save_guide").fadeOut();
			$( this ).dialog( "close" );
			return false;
		    },
		    Cancel: function() {
			$( this ).dialog( "close" );
		    }
		}
	    });
	    return false;
	});
    }else
    {
	////////////////////////////
	// DELETE PLUSLET
	// removes pluslet from DOM; change must be saved to persist
	/////////////////////////////

	jQuery('.guidewrapper').on('click', lstrSelector ,function(event) {

	    var delete_id = jQuery(this).attr("id").split("-")[1];
	    var element_deletion = this;
	    jQuery('<div class="delete_confirm" title="Are you sure?"></div>').dialog({
		autoOpen: true,
		modal: true,
		width: "auto",
		height: "auto",
		resizable: false,
		buttons: {
		    "Yes": function() {
			// Delete pluslet from database
			jQuery('#response').load("helpers/guide_data.php", {
			    delete_id: delete_id,
			    subject_id: subject_id,
			    flag: 'delete'
			},
						 function() {
						     jQuery("#response").fadeIn();

						 });

			// Remove node
			jQuery(element_deletion).parent().parent().parent().remove();
			$( this ).dialog( "close" );
			return false;
		    },
		    Cancel: function() {
			$( this ).dialog( "close" );
		    }
		}
	    });
	    return false;
	});
    }
}

function setupAllColorboxes()
{
    /////////////////////////
    // SHOW PLUSLETS VIA SEARCH (Colorbox)
    // load colorbox file: discover.php (set in href of item)
    // when colorbox closes, it looks for the window.addItem variable
    // if it's a number other than 0, it adds the clone, and resets to 0
    /////////////////////////

    jQuery(".showdisco").colorbox({
        iframe: true,
        innerWidth:800,
        innerHeight:500,

        onClosed:function() {
            //alert("parent says item = " + window.addItem);
            // add item to page

            if (window.addItem != 0) {
                plantClone(window.addItem, window.addItemType);
                window.addItem = 0;
            }
        }
    });

    /////////////////
    // Load metadata window in modal window
    // maybe they need to have saved all changes before loading?
    ////////////////

    jQuery(".showmeta").colorbox({
        iframe: true,
        innerWidth:960,
        innerHeight:600,

        onClosed:function() {
            //reload window to show changes

            //window.location.href = window.location.href;

        }
    });


    /////////////////
    // Load new Record window in modal window
    // maybe they need to have saved all changes before loading?
    ////////////////

    jQuery(".showrecord").colorbox({
        iframe: true,
        innerWidth:"80%",
        innerHeight:"90%",

        onClosed:function() {
            //change title potentially & shortform for link


        }
    });


    /////////////////
    // Load metadata.php in modal window--to organize All Items by Source
    // called by ticking the pencil button
    ////////////////

    jQuery(".arrange_records").colorbox({
        iframe: true,
        innerWidth:"80%",
        innerHeight:"90%",

        onClosed:function() {
            //reload window to show changes
            //window.location.href = window.location.href;
        }
    });
}

function setupMiscLiveQueries()
{
    /////////////////////////
    // HIDE NEW PLUSLETS DRAGGABLE SECTION
    /////////////////////////

    jQuery('#closer').livequery('click', function(event) {
        jQuery("#all-results").fadeOut("slow");
        return false;
    });

    /////////////////////////
    // HIDE FIND PLUSLETS DRAGGABLE SECTION
    /////////////////////////

    jQuery('#closer2').livequery('click', function(event) {
        jQuery("#find-results").fadeOut("slow");
        return false;
    });
    //////////////////////////////
    // .togglebody makes the body of a pluslet show or disappear
    //////////////////////////////

    jQuery('.togglebody').livequery('click', function(event) {

        jQuery(this).parent().parent().next('.pluslet_body').toggle('slow');
    });

    //////////////////////////////
    // .togglenew causes the all-results div to fade in
    // -- loads get_pluslets.php
    // mod agd OCT 2010
    //////////////////////////////

    jQuery('.togglenew').livequery('click', function(event) {
        //jQuery('#find-results').hide();
        //jQuery('#all-results').fadeIn();
        //jQuery("#all-results").load("get_pluslets.php", {base: 1 });
        jQuery("#all-results").fadeIn(2000);

    });

    ///////////////////////////////
    // Draw attention to TOC linked item
    ///////////////////////////////

    jQuery('a[id*=boxid-]').livequery('click', function(event) {
    	var tab_id = $(this).attr('id').split('-')[1];
    	var box_id = $(this).attr('id').split('-')[2];
        var selected_box = ".pluslet-" + box_id;

    	$('#tabs').tabs('select', tab_id);

        jQuery(selected_box).effect("pulsate", {
            times:1
        }, 2000);
	//jQuery(selected_box).animateHighlight("#dd0000", 1000);

    });

    ////////////////////
    // Make page tabs clickable
    ///////////////////
    jQuery('a[id*=tab-]').livequery('click', function(event) {
        var tab_id = jQuery(this).attr("id").split("-");
        //var selected_tab = "#pluslet-" + box_id[1];
        setupTabs(tab_id[1]);

    });

    ////////////////////
    // box-settings bind to show
    ///////////////////
    jQuery(document).on('click', 'a[id*=settings-]', function(event) {
        jQuery(this).parent().next('.box_settings').toggle('slow');
    });

    ////////////////////
    // on select change show save guide
    ///////////////////
    jQuery(document).on('change', 'select[id^=titlebar-styling]', function(event) {
	var pluslet_id = jQuery(this).parent().parent().parent().parent().attr('id') ;

	if( jQuery('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') == -1)
	{
	    jQuery('#' + pluslet_id).attr('name', 'modified-pluslet-' + jQuery('#' + pluslet_id).attr('name'));
	}

	jQuery("#response").hide();
	jQuery("#save_guide").fadeIn();
    });


    ////////////////////
    // Make titlebar options box clickable
    ///////////////////
    jQuery(document).on('change', '.pure-checkbox', function() {

        var pluslet_id = jQuery(this).parent().parent().parent().parent().attr('id') ;

    	if( jQuery('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') == -1)
    	{
	    jQuery('#' + pluslet_id).attr('name', 'modified-pluslet-' + jQuery('#' + pluslet_id).attr('name'));
    	}

    	jQuery("#response").hide();
        jQuery("#save_guide").fadeIn();


    });

}

function setupMiscClickEvents()
{
    //////////////////////////////
    // .togglenew causes the all-results div to fade in
    // -- loads get_pluslets.php
    // mod agd OCT 2010
    //////////////////////////////

    jQuery('#newbox1').click(function(event) {
        jQuery("#all-results").toggle('slow');
    });

    ///////////////////////////////////
    // #hide_header makes the SubsPlus header appear/disappear; it's hidden onload
    ///////////////////////////////////

    jQuery('#hide_header').click(function(event) {
        jQuery("#header, #subnavcontainer").toggle('slow');
    });
}

function makeHelpable( lstrSelector )
{
    ////////////////
    // Help Buttons
    // unbind click events from class and redeclare click event
    ////////////////

    jQuery(lstrSelector).unbind('click');
    jQuery(lstrSelector).on('click', function(){
        var help_type = jQuery(this).attr("class").split("-");
        var popurl = "helpers/popup_help.php?type=" + help_type[1];

        jQuery(this).colorbox({
            href: popurl,
            iframe: true,
            innerWidth:"600px",
            innerHeight:"60%",
            maxWidth: "1100px",
            maxHeight: "800px"
        });
    });
}

function setupTabs( lstrSelector )
{
    ////////////////
    // Setup Tabs
    ////////////////

    jQuery(lstrSelector).unbind('click');
    jQuery(lstrSelector).on('click', function(){
        var tab_id = jQuery(this).attr("id").split("-");
        // fade out all  tabs
        jQuery("#tab_body-0").fadeOut("");
        jQuery("#tab_body-" + tab_id[1]).fadeIn("slow");
        //fade in our tab


    });
}

///////////////////////
// checks whether all required inputs are not blank
///////////////////////

function checkRequired() {
    // If a required field is empty, set req_field to 1, and change the bg colour of the offending field
    var req_field = 0;

    jQuery("*[class=required_field]").each(function() {
	var check_this_field = jQuery(this).val();

	if (check_this_field == '' || check_this_field == null) {
	    jQuery(this).attr("style", "background-color:#FFDFDF");
	    req_field = 1;
	} else {
	    jQuery(this).attr("style", "background-color:none");
	}

    });

    return req_field;

}

//////////////////
// addslashes called inside guide save, above
// ///////////////

function addslashes(string) {

    return (string+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
}

/////////////////////
// refreshFeeds
// --loads the various feeds after the page has loaded
/////////////////////

function refreshFeeds() {

    jQuery(".find_feed").each(function(n) {
        var feed = jQuery(this).attr("name").split("|");
        jQuery(this).load("../../subjects/includes/feedme.php", {
            type: feed[4],
            feed: feed[0],
            count: feed[1],
            show_desc: feed[2],
            show_feed: feed[3]
        });
    });

}

///////

function plantClone(clone_id, item_type) {

    // Create new node below, using a random number

    var randomnumber=Math.floor(Math.random()*1000001);
    jQuery(".portal-column-1:visible").prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");
    //alert (drag_id + " drop: " + drop_id);
    //alert(clone_id);

    // cloneid is used to tell us this is a clone
    var new_id = "pluslet-cloneid-" + clone_id;
    // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory

    jQuery("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
        from: new_id,
        flag: 'drop',
        item_type: item_type
    },
						       function() {

							   // 1.  remove the wrapper
							   // 2. put the contents of the div into a variable
							   // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
							   var cnt = jQuery("#new-" + randomnumber).contents();
							   jQuery("#new-" + randomnumber).replaceWith(cnt);

							   jQuery("#response").hide();
							   //Make save button appear, since there has been a change to the page
							   jQuery("#save_guide").fadeIn();

						       });
}

///////////////
// function to correctly size layout of guide
//////////////
function reLayout( lintSectionID, lc, cc, rc )
{
    if (parseInt(lc) == 0) {
        jQuery('div#section_' + lintSectionID + ' div#container-0').width(0);
        jQuery('div#section_' + lintSectionID + ' div#container-0').hide();
    } else {
        jQuery('div#section_' + lintSectionID + ' div#container-0').show();
        jQuery('div#section_' + lintSectionID + ' div#container-0').width(lc.toString() + '%');
    }

    jQuery('div#section_' + lintSectionID + ' div#container-1').width(cc.toString() + '%');

    if (parseInt(rc) == 0) {
        jQuery('div#section_' + lintSectionID + ' div#container-2').width(0);
        jQuery('div#section_' + lintSectionID + ' div#container-2').hide();
    } else {
        jQuery('div#section_' + lintSectionID + ' div#container-2').show();
        jQuery('div#section_' + lintSectionID + ' div#container-2').width(rc.toString() + '%');
    }

}




///////////////
// function to add section to current tab
//////////////
function makeAddSection( lstrSelector )
{
    jQuery( lstrSelector ).on( 'click', function()
			       {
				   var lintSelected = $(tabs).tabs('option', 'selected');

				   jQuery.ajax({
				       url: "helpers/section_data.php",
				       type: "POST",
				       data: { action : 'create' },
				       dataType: "html",
				       success: function(html) {
					   console.log("response:");
					   console.log(html);
					   console.log(lintSelected);
					   $('div#tabs-' + lintSelected).append(html);
					   $(document).scrollTop($('body').height());
				       }
				   });
			       });
}

////////////////
// function to make section sliders
///////////////
function makeSectionSlider( lstrSelector )
{
    $( lstrSelector ).each(function()
			   {
			       //section id
			       var sec_id = $(this).attr('id').split('slider_section_')[1];
			       var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

			       $( this ).slider({
				   range: true,
				   min: 0,
				   max: 12,
				   step: 1,
				   values: [lobjLayout[0], parseInt(lobjLayout[0]) + parseInt(lobjLayout[1])],
				   slide: function( event, ui ) {
				       // figure out our vals
				       var left_col = ui.values[0];
				       var right_col = 12 - ui.values[1];
				       var center_col = 12 - (left_col + right_col);
				       var extra_val = left_col + "-" + center_col + "-" + right_col;

				       var lw = parseInt(left_col) * 8;
	    			       var mw = parseInt(center_col) * 8;
	    			       var sw = parseInt(right_col) * 8 - 3;

				       $( "div#section_" + sec_id ).attr( 'data-layout', extra_val);

				       reLayout(sec_id, lw, mw, sw);
				       
			
				       
				       
				       // Hide or show the third column if needed 

				       if (sw < 0) {
					   jQuery('#container-2').hide();
				       }

				       if (sw > 0) {
					   jQuery('#container-2').show();
				       }
				       

				       //show save guide button
				       jQuery("#response").hide();
				       jQuery("#save_guide").fadeIn();
				   }
			       });
			   });



    

}

window.onload = function() { 
    // Wait until everything on the page has loaded then... 
    // Hide the third column if it doesn't have any width
    if (jQuery('#container-2').width() === 0) {	
	jQuery('#container-2').hide();
	
    }

};
