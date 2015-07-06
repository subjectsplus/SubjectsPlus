$(document).ready(function(){

	var clone_pluslet_id;
	
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
    setupMiscEvents();
    setupMiscClickEvents();

    makeHelpable("img[class*=help-]");
    setupTabs('a[id*=tab-]');
    makeAddSection('a[id="add_section"]');

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
    			  $('#box_options').hide();
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
}); 
// End $ within document.ready

function makeDropable( lstrSelector )
{
    ////////////////////////////////
    // SET UP DROP SPOTS
    // --makes divs with class of "dropspotty" droppables
    // accepts things with class of "draggable"
    //
    ////////////////////////////////

    var dropspot = $(lstrSelector);
    var drop_id;
    var drag_id;
    var drop_tab;
    var pluslet_title;
    
    dropspot.droppable({

	iframeFix: true,
	accept: ".draggable, .pluslet",
	drop: function(event, props) {

	    $(this).removeClass("drop_hover");
	    $(this).css("background", "");

	    //do if droppable tab
	    if($(this).children('a[href^="#tabs-"]').length > 0 )
	    {
		if( $(props.draggable).hasClass('pluslet') )
		{
		    if( !$(this).hasClass('ui-state-active') )
		    {
			drop_tab = this;

			//make sure to show all of pluslet before hiding
			$(props.draggable).children('.pluslet_body').show();
			$(props.draggable).children().children('.titlebar_text').show();
			$(props.draggable).children().children('.titlebar_options').show();


                        $(props.draggable).hide('slow', function()
						{
						    $(this).remove();
                                                    console.log($(this));
                                                    console.log(drop_tab);
						    $(drop_tab).children('a[href^="#tabs-"]').click();
						    $('.portal-column-1:visible').first().prepend(this);

                                                    console.log("Drop tab drop tab!");
						    $(this).height("auto");
						    $(this).width("auto");
						    $(this).show("slow");

						    $("#response").hide();
						    //Make save button appear, since there has been a change to the page
						    $("#save_guide").fadeIn();
						});
		    }
		}

		return;
	    }

	    //only do for class draggable
	    if( $(props.draggable).hasClass('draggable') )
	    {

		drop_id = $(this).attr("id");
		drag_id = $(props.draggable).attr("id");
		
		pluslet_title = $(props.draggable).html();
		if( $(this).hasClass('cke') )
		{
		    if( $(props.draggable).attr("ckclass") !== "" )
		    {
			CKEDITOR.instances[$(this).attr('id').replace('cke_', '')].openDialog($(props.draggable).attr("ckclass") + 'Dialog');
		    }else
		    {
			alert( 'This pluslet isn\'t configured to drag into CKEditor!' );
		    }
		}else
		{
		    // if there can only be one, could remove from list items
		    drop_id = $(this).attr("id");
		    drag_id = $(props.draggable).attr("id");
		    
		    pluslet_title = $(props.draggable).html();

		    // Create new node below, using a random number

		    var randomnumber=Math.floor(Math.random()*1000001);
		    $(this).next('div').prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");
		    

		    // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory
		    $("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
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
								      var cnt = $("#new-" + randomnumber).contents();
								      $("#new-" + randomnumber).replaceWith(cnt);
								      
								      $(this).addClass("unsortable");

								      $("#response").hide();
								      //Make save button appear, since there has been a change to the page
								      $("#save_guide").fadeIn();


								      $("a[class*=showmedium]").colorbox({
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
	    if($(this).children('a[href^="#tabs-"]').length > 0 && $(ui.draggable).hasClass('pluslet')
	       && !$(this).hasClass('ui-state-active'))
	    {
		$(this).css("background", "none repeat scroll 0% 0% #C03957");
	    }

	    if($(this).children('a[href^="#tabs-"]').length < 1 && !$(ui.draggable).hasClass('pluslet'))
	    {
		$(this).addClass("drop_hover");
	    }
	},
	out: function(event, ui)
	{
	    if($(this).children('a[href^="#tabs-"]').length > 0 && $(ui.draggable).hasClass('pluslet'))
	    {
		$(this).css("background", "");
	    }

	    if($(this).children('a[href^="#tabs-"]').length < 1 && !$(ui.draggable).hasClass('pluslet'))
	    {
		$(this).removeClass("drop_hover");
	    }
	}
    });
}

function makeSortable( lstrSelector, lstrType )
{

    ////////////////////////////
    // MAKE COLUMNS SORTABLE
    // Make "Save Changes" button appear on sorting
    ////////////////////////////

    var sortable_element  = $(lstrSelector);

    if( lstrType === 'sections' )
    {
	sortable_element.sortable({
	    opacity: 0.7,
	    cancel: '.unsortable',
	    handle: 'img.section_sort',
	    update: function(event, ui) {
		$("#response").hide();
		$("#save_guide").fadeIn();

	    },
	    start: function(event, ui)
	    {
		$(ui.item).find('.dropspotty').hide();
		$(ui.item).find('.pluslet').hide();
		$(ui.item).height('2em');
		$(ui.item).width('auto');
	    },
	    stop: function(event, ui)
	    {
		$(ui.item).find('.dropspotty').show();
		$(ui.item).find('.pluslet').show();
	    }
	});
    }else
    {
	sortable_element.sortable({

	    connectWith :['.portal-column-0', '.portal-column-1', '.portal-column-2'],
	    opacity: 0.7,
	    tolerance: 'intersect',
	    cancel: '.unsortable',
	    handle: 'img.pluslet_sort',
	    update: function(event, ui) {
		$("#response").hide();
		$("#save_guide").fadeIn();

	    },
	    start: function(event, ui)
	    {
		$(ui.item).children('.pluslet_body').hide();
		$(ui.item).children().children('.titlebar_text').hide();
		$(ui.item).children().children('.titlebar_options').hide();
		$(ui.item).height('2em');
		$(ui.item).width('auto');
	    },
	    stop: function(event, ui)
	    {
		$(ui.item).children('.pluslet_body').show();
		$(ui.item).children().children('.titlebar_text').show();
		$(ui.item).children().children('.titlebar_options').show();

	    }
	});
    }
}

function makeDraggable( lstrSelector )
{
    ////////////////////////////////
    // SET UP DRAGGABLE
    // --makes anyting with class of "draggable" draggable
    ////////////////////////////////

    var draggable_element = $(lstrSelector);

    draggable_element.draggable({
	ghosting:	true,
	opacity:	0.5,
	revert: true,
	fx: 300,
	cursor: 'pointer',
	helper: 'clone',
	zIndex: 350
    });
    
}

function setupSaveButton( lstrSelector )
{
    ////////////////////////////
    // SAVE GUIDE'S LAYOUT
    // -- this saves everything on page
    ////////////////////////////

    $(document.body).on('click',lstrSelector, function(event) {

	// make sure our required fields have values before continuing
	var test_req = checkRequired();

	if (test_req === 1) {
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
	$('div[name*=modified-pluslet]').each(function() {

	    var update_id = $(this).attr("id").split("-");
	    var this_id = update_id[1];

	    //prepare the pluslets for saving
	    preparePluslets("modified", this_id, this);
	});

	////////////////////////
	// Now the new pluslets
	////////////////////////

	$('div[name*=new-pluslet]').each(function() {

	    var insert_id = $(this).attr("id"); // just a random gen number

	    //prepare pluslets for saving
	    preparePluslets("new", insert_id, this);
	});

	//////////////////////
	// We're good, save the guide layout
	// insert a pause so the new pluslet is found
	//////////////////////
	$("#response").hide();
	$("#save_guide").fadeOut();

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
        var lstrInstance;
        var lstrTitle;
        var lstrDiv;
        var lintUID;
        var pbody;
	var pitem_type;
        var pspecial; 
        var ourflag;
        var isclone; 
        
	//based on type set variables
	switch( lstrType.toLowerCase() )
	{
	case "modified":
	    {
		//used to get contents of CKeditor box
		lstrInstance = "pluslet-update-body-" + lintID;
		//Title of item
		lstrTitle = addslashes($("#pluslet-update-title-" + lintID).val());
                console.log(lstrTitle);
                console.log("Title modified!");
		if (lstrTitle === undefined) {
		    b = $(".pluslet-" + lintID).find('.titlebar_text').clone();
		    b.children().remove();
		    lstrTitle = b.text().trim();
		    lboolSettingsOnly = true;
		}

		//Div Selector
		lstrDiv = "#pluslet-" + lintID;
		//depending update_id
		lintUID = lintID;
		break;
	    }
	case "new":
	    {
		//used to get contents of CKeditor box
		lstrInstance = "pluslet-new-body-" + lintID;
		//Title of item
		lstrTitle = addslashes($("#pluslet-new-title-" + lintID).val());
		//Div Selector
		lstrDiv = "#" + lintID;
		//depending update_id
		lintUID = '';
		break;
	    }
	}

	///////////////////////////////////////////////////////////////
	// The box settings  are available on all pluslets potentially
	// --they determine if titlebar shows, titlebar styling, if body
	// is collapsed by default, and if body is suppressed (for a header pluslet)
	////////////////////////////////////////////////////////////////

	var boxsetting_hide_titlebar = Number($('input[id=notitle-'+lintID+']').is(':checked'));
	var boxsetting_collapse_titlebar = Number($('input[id=start-collapsed-'+lintID+']').is(':checked'));
	var boxsetting_titlebar_styling = $('select[id=titlebar-styling-'+lintID+']').val();


	//////////////////////////////////////////////////////////////////
	// Check the pluslet's "name" value to see if there is a number
	// --If it is numeric, it's a "normal" item with a ckeditor instance
	// collecting the "body" information
	//////////////////////////////////////////////////////////////

	var item_type = $(lobjThis).attr("name").split("-");

	// Loop through the box types
	switch (item_type[2]) {
	case "Basic":
            if (typeof CKEDITOR !== 'undefined' && !lboolSettingsOnly) {

                pbody = addslashes(CKEDITOR.instances[lstrInstance].getData());

            } else {

                pbody = $('#pluslet-' + lintID).find('.pluslet_body').html();
            }

	    pitem_type = "Basic";
	    pspecial = '';
	    break;
	case "Heading":
	    pbody = ""; // headings have no body
	    pitem_type = "Heading";

	    break;
	case "TOC":
	    pbody = "";
	    pitem_type = "TOC";
	    var tickedBoxes = [];
	    $('input[name=checkbox-'+lintID+']:checked').each(function() {
	        
		tickedBoxes.push(this.value);

	    });

	    pspecial = '{"ticked":"' + tickedBoxes + '"}';
	    
	    break;
	case "Feed":
	    pbody = $('input[name=' + lstrInstance + ']').val();
	    var pfeed_type = $('select[name=feed_type-'+lintID+']').val();
	    var pnum_items = $('input[name=displaynum-'+lintID+']').val();
	    var pshow_desc = $('input[name=showdesc-'+lintID+']:checked').val();
	    var pshow_feed = $('input[name=showfeed-'+lintID+']:checked').val();

	    pspecial = '{"num_items":'
		+ pnum_items +
		',  "show_desc":'
		+ pshow_desc +
		', "show_feed": '
		+ pshow_feed +
		', "feed_type": "'
		+ pfeed_type +
		'"}';

	    pitem_type = "Feed";
	    break;

	default:

	    pbody = $('#' + item_type[2] + '-body').html();
	    pbody = pbody === undefined ? ""  : pbody;
	    pitem_type = item_type[2];
	    var extra = {};

            //parse text inputs to create extra fields
	    $(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=text]').each(function()
											{
											    var name_split = $(this).attr("name").split("-");
											    extra[name_split[2]] = $(this).val();
											});

            //parse textareas to create extra fields
	    $(lobjThis).find('textarea[name^=' + item_type[2] + '-extra]').each(function()
										{
										    var name_split = $(this).attr("name").split("-");
										    extra[name_split[2]] = $(this).val();
										});

            //parse selectboxes to create extra fields
	    $(lobjThis).find('select[name^=' + item_type[2] + '-extra]').each(function()
									      {
										  var name_split = $(this).attr("name").split("-");
										  extra[name_split[2]] = $(this).val();
									      });

            //parse radio inputs to create extra fields
            $(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=radio]').each(function()
											 {
											     var name_split = $(this).attr("name").split("-");
											     extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? '' : extra[name_split[2]];
                                                                                             
											     if( $(this).is(':checked') )
												 extra[name_split[2]] = $(this).val();
											 });

            //parse checkboxe inputs to create extra fields
            $(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=checkbox]').each(function()
											    {
												var name_split = $(this).attr("name").split("-");
												extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? [] : extra[name_split[2]];

												if( $(this).is(':checked') )
												    extra[name_split[2]].push( $(this).val() );
											    });

	    pspecial = $.isEmptyObject( extra ) ? "" : JSON.stringify(extra);

	    break;
	}

	//only check clone if modified pluslet
	if( lstrType === 'modified')
	{
	    //////////////////////
	    // Clone?
	    // If it's a clone, add a new entry to DB
	    /////////////////////

	    var clone = $("#pluslet-" + lintID).attr("class");

	    if (clone.indexOf("clone") !== -1) {
		ourflag = 'insert';
		isclone = 1;
	        
	    } else {
		ourflag = 'update';
		isclone = 0;
	    }

	    //only settings update
	    if(lboolSettingsOnly)
	    {
		ourflag = 'settings';
	    }
	}else
	{
	    ourflag = 'insert';
	    isclone = 0;
	}

	////////////////////////
	// Post the data to guide_data.php
	// which will do an insert or update as appropriate
	//
	// **changed by dgonzalez 08/2013 so that request is not done
	// asynchronously so that setTimeout to save guide is no longer needed.
	////////////////////////

	$.ajax({
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
                var this_div;

		//load response into pluslet
		$(lstrDiv).html(response);

		// check if it's an insert or an update, and name div accordingly
		if (ourflag === "update" || ourflag === "settings" || isclone === 1) {
		    this_div = '#pluslet-'+lintID;
		} else {
		    this_div = '#'+lintID;
		}

		// 1.  remove the wrapper
		// 2. put the contents of the div into a variable
		// 3.  replace parent div (i.e., id="xxxxxx") with the content made by loaded file
		var cnt = $(this_div).contents();
		
		$(this_div).replaceWith(cnt);
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

	$('a[href^="#tab"]').each(function(){
	    var lstrName = $(this).text();
	    var lstrExternal = $(this).parent('li').attr('data-external-link');
	    var lintVisibility = parseInt($(this).parent('li').attr('data-visibility'));
	    var tab_id = $(this).attr("href").split("tabs-")[1];
            var subject_id;
            var user_name;
            var lstrTabs;
            
            
	    var lobjTab = {};
	    lobjTab.name = lstrName;
	    lobjTab.external = lstrExternal;
	    lobjTab.visibility = lintVisibility;
	    lobjTab.sections = [];

	    $('div#tabs-' + tab_id + ' div[id^="section_"]').each(function()
								  {
								      var section_id = $(this).attr("id").split("section_")[1];
								      var lobjSection = {};
                                                                      lobjSection.center_data = "";
                                                                      lobjSection.left_data = "";
                                                                      lobjSection.sidebar_data = "";

								      lobjSection.layout = $(this).attr("data-layout");

								      $('div#section_' + section_id + ' div.portal-column-0').sortable();
								      $('div#section_' + section_id + ' div.portal-column-1').sortable();
								      $('div#section_' + section_id + ' div.portal-column-2').sortable();

								      lobjSection.left_data = $('div#section_' + section_id +  ' div.portal-column-0').sortable('serialize');


								      lobjSection.center_data = $('div#section_' + section_id +  ' div.portal-column-1').sortable('serialize');
                                                                      console.log(section_id);
                                                                      console.log(lobjSection.center_data);

								      lobjSection.sidebar_data = $('div#section_' + section_id +  ' div.portal-column-2').sortable('serialize');

								      lobjTab.sections.push(lobjSection);
								  });

	    lobjTabs.push(lobjTab);
	});

	lstrTabs = JSON.stringify(lobjTabs);
        console.log(lstrTabs);
	$("#response").load("helpers/save_guide.php", {
	    this_subject_id: subject_id,
	    user_name: user_name,
	    tabs: lstrTabs
	},
			    function() {

				$("#response").fadeIn();
				refreshFeeds();

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

    
    $(document.body).on('click', lstrSelector, function(event) {
        var isclone;
        var edit_id = $(this).attr("id").split("-");
        
        ////////////
        // Clone?
        ////////////

        var clone = $("#pluslet-" + edit_id[1]).attr("class");
        if (clone.indexOf("clone") !== -1) {
            isclone = 1;
        } else {
            isclone = 0;
        }

        /////////////////////////////////////
        // Load the form elements for editing
        /////////////////////////////////////

        $('#' + 'pluslet-' + edit_id[1]).load("helpers/guide_data.php", {
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

						  var cnt = $('#' + 'pluslet-' + edit_id[1]).contents();
						  $('#' + 'pluslet-' + edit_id[1]).replaceWith(cnt);

						  /////////////////////////////////////
						  // Make unsortable for the time being
						  /////////////////////////////////////

						  $("#pluslet-" + edit_id[1]).addClass("unsortable");

						  //////////////////////////////////////
						  // We're changing the attribute here for the global save
						  //////////////////////////////////////

						  if (edit_id[2] !== undefined) {
						      var new_name = "modified-pluslet-" + edit_id[2];
						      $("#pluslet-" + edit_id[1]).attr("name", new_name);
						  } else {
						      $("#pluslet-" + edit_id[1]).attr("name", "modified-pluslet");
						  }

    						  makeHelpable("img[class*=help-]");

					      });

        //Make save button appear, since there has been a change to the page
        $("#response").hide();
        $("#save_guide").fadeIn();

        return false;
    });
}

function makeDeleteable( lstrSelector, lstrType )
{
    if( lstrType === 'sections' )
    {
	/////////////////////////////
	// DELETE SECTION
	/////////////////////////////
	$('.guidewrapper').on('click', lstrSelector ,function(event) {

	    var delete_id = $(this).parent().parent().attr("id").split("_")[1];
	    var element_deletion = this;
	    $('<div class="delete_confirm" title="Are you sure?">All content in this section will be deleted.</div>').dialog({
		autoOpen: true,
		modal: true,
		width: "auto",
		height: "auto",
		resizable: false,
		buttons: {
		    "Yes": function() {
			// Remove node
			$(element_deletion).parent().parent().remove();
			$("#response").hide();
			window.saveGuide();
			$("#save_guide").fadeOut();
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

	$('.guidewrapper').on('click', lstrSelector ,function(event) {

	    var delete_id = $(this).attr("id").split("-")[1];
	    var element_deletion = this;
	    $('<div class="delete_confirm" title="Are you sure?"></div>').dialog({
		autoOpen: true,
		modal: true,
		width: "auto",
		height: "auto",
		resizable: false,
		buttons: {
		    "Yes": function() {
			// Delete pluslet from database
			$('#response').load("helpers/guide_data.php", {
			    delete_id: delete_id,
			    subject_id: subject_id,
			    flag: 'delete'
			},
					    function() {
						$("#response").fadeIn();

					    });

			// Remove node
			$(element_deletion).parent().parent().parent().remove();
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

    $(".showdisco").colorbox({
        iframe: true,
        innerWidth:800,
        innerHeight:500,

        onClosed:function() {
            
            // add item to page

            if (window.addItem !== 0) {
                plantClone(window.addItem, window.addItemType);
                window.addItem = 0;
            }
        }
    });

    /////////////////
    // Load metadata window in modal window
    // maybe they need to have saved all changes before loading?
    ////////////////

    $(".showmeta").colorbox({
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

    $(".showrecord").colorbox({
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

    $(".arrange_records").colorbox({
        iframe: true,
        innerWidth:"80%",
        innerHeight:"90%",

        onClosed:function() {
            //reload window to show changes
            //window.location.href = window.location.href;
        }
    });
}

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
    // box-settings bind to show
    ///////////////////
    $(document).on('click', 'a[id*=settings-]', function(event) {
        $(this).parent().next('.box_settings').toggle('slow');
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
    $(document).on('change', '.pure-checkbox', function() {

        var pluslet_id = $(this).parent().parent().parent().parent().attr('id') ;

    	if( $('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') == -1)
    	{
	    $('#' + pluslet_id).attr('name', 'modified-pluslet-' + $('#' + pluslet_id).attr('name'));
    	}

    	$("#response").hide();
        $("#save_guide").fadeIn();


    });

}

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
        $("#header, #subnavcontainer").toggle('slow');
    });
    
    $(".box-item").click(function(event) {
        var edit_id = $(this).attr("id").split("-");
        plantClone('', edit_id[2], '');

    });
    
    $('body').on('click', '.clone-button',function() {
    	
   // 	var clone_id = Math.floor(Math.random()*1000001);
    	var origin_id = $(this).parent().attr('data-pluslet-id');   	
    	
    	plantClone('','Clone',origin_id);
    	
   
    });
    
    $('body').on('click', '.copy-button',function() {
    	
    	   // 	var clone_id = Math.floor(Math.random()*1000001);
    	    	var origin_id = $(this).parent().attr('data-pluslet-id');   	
    	    	
    	    	plantClone(origin_id,'Basic');
    	    
    	});
    
    
    
    
}

function setCloneValue(input) {
	$(input).val("testing");
	
}

function makeHelpable( lstrSelector )
{
    ////////////////
    // Help Buttons
    // unbind click events from class and redeclare click event
    ////////////////

    $(lstrSelector).unbind('click');
    $(lstrSelector).on('click', function(){
        var help_type = $(this).attr("class").split("-");
        var popurl = "helpers/popup_help.php?type=" + help_type[1];

        $(this).colorbox({
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

    $(lstrSelector).unbind('click');
    $(lstrSelector).on('click', function(){
        var tab_id = $(this).attr("id").split("-");
        // fade out all  tabs
        $("#tab_body-0").fadeOut("");
        $("#tab_body-" + tab_id[1]).fadeIn("slow");
        //fade in our tab


    });
}

///////////////////////
// checks whether all required inputs are not blank
///////////////////////

function checkRequired() {
    // If a required field is empty, set req_field to 1, and change the bg colour of the offending field
    var req_field = 0;

    $("*[class=required_field]").each(function() {
	var check_this_field = $(this).val();

	if (check_this_field === '' || check_this_field === null) {
	    $(this).attr("style", "background-color:#FFDFDF");
	    req_field = 1;
	} else {
	    $(this).attr("style", "background-color:none");
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

    $(".find_feed").each(function(n) {
        var feed = $(this).attr("name").split("|");
        $(this).load("../../subjects/includes/feedme.php", {
            type: feed[4],
            feed: feed[0],
            count: feed[1],
            show_desc: feed[2],
            show_feed: feed[3]
        });
    });

}

///////

function loadCloneMenu() {
	
		$.get("http://development.library.miami.edu/dev-non-svn/rails_projects/SubjectsPlus/control/includes/autocomplete_data.php?collection=guides&term=", function(data) { 

			for(var i = 0; i<data.length;i++) {
		        var subject_id = data[i].id;
				$('.guide-list').append("<option data-subject-id='" + subject_id + "' class=\"guide-listing\">" + data[i].label + "</li>");

			}

		});

		$('.guide-list').on('change', function(data) {
			var subject_id = $("option:selected", this).attr('data-subject-id');

			$('.pluslet-list').empty();

			$.get("http://development.library.miami.edu/dev-non-svn/rails_projects/SubjectsPlus/control/includes/autocomplete_data.php?collection=guide&subject_id=" + subject_id + " &term="
					,function(data) {

					for(var i = 0; i<data.length;i++) {
						$('.pluslet-list').append("<li data-pluslet-id='" + data[i].id + "' class=\"pluslet-listing\"><button class=\"clone-button\">Clone</button><button class=\"copy-button\">Copy</button>" + data[i].label + "</li>");
			
					}
			});	
			
		});

}

function plantClone(clone_id, item_type, origin_id) {

    // Create new node below, using a random number

    var randomnumber=Math.floor(Math.random()*1000001);
    $(".portal-column-1:visible").prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");
    

    // cloneid is used to tell us this is a clone
    var new_id = "pluslet-cloneid-" + clone_id;
    // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory

    $("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
        from: new_id,
        flag: 'drop',
        item_type: item_type
    },
						  function() {

						      // 1.  remove the wrapper
						      // 2. put the contents of the div into a variable
						      // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
						      var cnt = $("#new-" + randomnumber).contents();
						      		      
						      cnt.find('input.clone-input').val(origin_id);

						      $("#new-" + randomnumber).replaceWith(cnt);

						      
						      
						      $("#response").hide();
						      //Make save button appear, since there has been a change to the page
						      $("#save_guide").fadeIn();

						  });
}

///////////////
// function to correctly size layout of guide
//////////////
function reLayout( lintSectionID, lc, cc, rc )
{
    if (parseInt(lc) === 0) {
        $('div#section_' + lintSectionID + ' div#container-0').width(0);
        $('div#section_' + lintSectionID + ' div#container-0').hide();
    } else {
        $('div#section_' + lintSectionID + ' div#container-0').show();
        $('div#section_' + lintSectionID + ' div#container-0').width(lc.toString() + '%');
    }

    $('div#section_' + lintSectionID + ' div#container-1').width(cc.toString() + '%');

    if (parseInt(rc) === 0) {
        $('div#section_' + lintSectionID + ' div#container-2').width(0);
        $('div#section_' + lintSectionID + ' div#container-2').hide();
    } else {
        $('div#section_' + lintSectionID + ' div#container-2').show();
        $('div#section_' + lintSectionID + ' div#container-2').width(rc.toString() + '%');
    }

}




///////////////
// function to add section to current tab
//////////////
function makeAddSection( lstrSelector )
{


    $( lstrSelector ).on( 'click', function()
			  {

                              
			      var lintSelected = $(tabs).tabs('option', 'selected');

			      $.ajax({
				  url: "helpers/section_data.php",
				  type: "POST",
				  data: { action : 'create' },
				  dataType: "html",
				  success: function(html) {
				      
				      $('div#tabs-' + lintSelected).append(html);
				      $(document).scrollTop($('body').height());

                                      // Make sure that the new section can accept drops
                                      makeDropable(".dropspotty");
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
					   $('#container-2').hide();
				       }

				       if (sw > 0) {
					   $('#container-2').show();
				       }
				       

				       //show save guide button
				       $("#response").hide();
				       $("#save_guide").fadeIn();
				   }
			       });
			   });



    

}

window.onload = function() { 
    // Wait until everything on the page has loaded then... 
    // Hide the third column if it doesn't have any width
    if ($('#container-2').width() === 0) {	
	$('#container-2').hide();
	
    }

};
