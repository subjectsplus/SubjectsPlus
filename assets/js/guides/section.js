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
			mySection.chooseSectionForLayouts();
		},
		init: function () {
		     
		    mySection.bindUiActions();
		    
		    
		    $(window).bind("load", function() {
		    	// Click the first section after everything has loaded.
			    mySection.clickInitialSection();

		    });
			
		},
		makeAddSection : function(lstrSelector)
		///////////////
		//function to add section to current tab	
		//////////////
		{

			$(lstrSelector).on('click', function() {
				
				$(tabs).tabs();
				var selectedTab = $(tabs).tabs('option', 'selected');

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
						
						// When you add a section fade in the save button 
						$("#save_guide").fadeIn();
						$('.sp_section_controls').first().show();
						
						var newSection = $('#tabs-' + selectedTab + ' .sp_section_controls').last();
						newSection.trigger('click');

						var l = layout();
						l.highlightLayout(newSection.parent());
					    

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
				$('.sp_section_controls').removeClass('sp_section_selected');
				$('.sp_section').removeClass('section_selected_area');

				$('#layout_options_content').data('selected-section', '');

				$(this).toggleClass('sp_section_selected');
				$(this).parent().toggleClass('section_selected_area');
				var selectedSectionId = $(this).parent().attr('id').split('_')[1];
				$('#layout_options_content').data('selected-section', selectedSectionId);
				l.activateLayoutButtons();
				// Highlight the layout that is associated with the section. 
				l.highlightLayout($(this).parent())
				// Show the initial section. Now you are using sections so you will need the section contorls.
				$('.sp_section_controls').first().show();

				
			});
		},
		clickInitialSection : function() {
			
			// Click the first section to mark it as active 
			$('.sp_section_controls').first().trigger('click');
			
			if ($('.sp_section_controls').size() >= 1) {
				// If there are already sections on the page don't hide the first section
				// Hide the first section because the user may not use sections.

				$('.sp_section_controls').first().hide();
				$('.sp_section').first().removeClass('section_selected_area');
			
			}

			
		}
	};

	return mySection;
}
