/**
 * 
 * Object that allows you to add new sections and handle events related to the sections.
 *
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function section() {
	"use strict";

	var mySection = {

		settings : {
			sectionDataPath : "helpers/section_data.php"
		},
		strings : {},
		bindUiActions : function() {

			$( document ).ready(function() {
				mySection.clickTabOnSwitch();
				mySection.clickDeleteSection();
				mySection.chooseSectionForLayouts();
				mySection.viewSectionControls();

			});

		},
		init: function () {

		    $( document ).ready(function() {
				mySection.bindUiActions();

				// Click the first section after everything has loaded.
			    mySection.clickInitialSection();


		    });
			
		},
		viewSectionControls : function() {


			$('.sptab').each(function () {
				if ($(this).children().size() > 1) {
					console.log("More than one?");
					$(this).children().find('.sp_section_controls').show();
					$(this).children().find('.section_remove').hide();
				} else {
					$(this).children().find('.sp_section_controls').hide();
					$(this).find('.sp_section').removeClass('section_selected_area');
				}
			});
			
		},
		makeAddSection : function(lstrSelector) {

			///////////////
			//function to add section to current tab
			//////////////
			$(lstrSelector).on('click', function() {
				//$(tabs).tabs();
				var selectedTab = $('#tabs').tabs('option', 'active');

				$.ajax({
					
					url : mySection.settings.sectionDataPath,
					type : "POST",
					data : {
					action : 'create'
					
					},
					dataType : "html",
					success : function(html) {

						$('div#tabs-' + selectedTab).append(html);
						$(document).scrollTop($('body').height());

						// Make sure that the new section can accept drops
						var drop = drag();
						drop.makeDropable(".dropspotty");

						// When you add a section autosave
						var save = saveSetup();
						save.saveGuide();
						$('#save_guide').fadeOut();
						//window.location.reload();

						$('div#tabs-' + selectedTab)
						var newSection = $('#tabs-' + selectedTab + ' .sp_section_controls').last();
						newSection.trigger('click');

						var l = layout();
						l.highlightLayout(newSection.parent());

						mySection.viewSectionControls();
					}
				});
			});
		},
		chooseSectionForLayouts : function () {
			
			/**
			 * If you click on the section controls, the section controls that you clicked on will be 
			 * hightlighted and the layouts control will effect that section. 
			 */
			$('body').on('click','.sp_section_controls', function() {
				var l = layout();
				
				// Removes existing highlights and controls
				$('.sp_section_controls').removeClass('sp_section_selected');
				$('.sp_section').removeClass('section_selected_area');
				$('.section_remove').hide();
				
				$('#layout_options_content').data('selected-section', '');

				// This adds the classes for highlighting
			 	$(this).toggleClass('sp_section_selected');
			    $(this).parent().toggleClass('section_selected_area');
			    $(this).children('.section_remove').show();
				
				var selectedSectionId = $(this).parent().attr('id').split('_')[1];
				$('#layout_options_content').data('selected-section', selectedSectionId);
				l.activateLayoutButtons();
				// Highlight the layout that is associated with the section. 
				l.highlightLayout($(this).parent())
				// Show the initial section. Now you are using sections so you will need the section contorls.

			});
		},
		clickInitialSection : function() {
			// Click the first section to mark it as active 
			$('.sp_section_controls').first().trigger('click');
		},

		clickTabOnSwitch : function () {
			$('.ui-tabs-nav > li.child-tab').on('click', function() {
				console.log('tab: ' + $(this).attr('aria-controls'));
				 var tabIndex = $(this).attr('aria-controls').split('-')[1];
				$('#tabs-' + tabIndex).children().first().find('.sp_section_controls').trigger('click');
				mySection.viewSectionControls();
			});
		},
		
		setupHighlights : function() {
			// Hide the first section control in each of the tabs:
			$('.sp_section:first-child .sp_section_controls').hide();
		},

		clickDeleteSection: function() {
			// get section id
			var section_id = "";
			$('.section_remove').on('click', function() {

				section_id = $(this).parent('.sp_section_selected').parent('.section_selected_area').attr('id').split('_')[1];
				//console.log( 'section_id: ' + section_id);

				var pluslets = [];
				pluslets = mySection.fetchPlusletsBySectionId(section_id);
				//console.log('pluslets: ' + pluslets);

				pluslets.then(function(data) {

					if(data.pluslets.length > 0) {

						var masterClones = [];
						$.each(data.pluslets, function(key, value) {
							var pluslet_id = value.pluslet_id;
							//console.log('pluslet_id: ' + pluslet_id);

							masterClones = mySection.hasMasterClones(pluslet_id);
							console.log(masterClones);

							masterClones.then(function(data) {
								//console.log(data.cloned_pluslets);


								if(data.cloned_pluslets.length > 0) {

									$('<div>This section cannot be deleted because it has linked boxes.</div>').dialog({
										autoOpen: true,
										modal: false,
										width: 'auto',
										height: 'auto',
										resizable: false,
										buttons: {
											Cancel: function () {
												$(this).dialog('close');
											}
										}
									});
									return false;
								} else {
									mySection.deleteSection(section_id);
								}

							});
						});

					} else {

						mySection.deleteSection(section_id);

					}


				});


			});

		},

		deleteSection: function(section_id) {

			$('<div id="dialog" class=\'delete_confirm\' title=\'Are you sure?\'>All content in this section will be deleted.</div>').dialog({
				autoOpen: false,
				modal: true,
				width: 'auto',
				height: 'auto',
				resizable: false,
				buttons: {
					Yes: function () {
						// Remove node
						$("#section_" + section_id).remove();
						$('#response').hide();

						var save = saveSetup();
						save.saveGuide();
						$('#save_guide').fadeOut();

						$(this).dialog('close');
						return false;

					},
					Cancel: function () {
						$(this).dialog('close');
					}
				}
			});

			$('.delete_confirm').first().dialog('open');
			return false;


		},

		fetchPlusletsBySectionId: function(section_id) {
			return $.ajax({
				url: "helpers/fetch_pluslets_by_section_id.php",
				type: "GET",
				data: 'section_id=' + section_id,
				dataType: "json"
			});
		},


		hasMasterClones: function (pluslet_id) {
			return $.ajax({
				url: "helpers/fetch_cloned_pluslets.php",
				type: "GET",
				data: 'master_id=' + pluslet_id,
				dataType: "json"
			});
		}
	};

	return mySection;
}
