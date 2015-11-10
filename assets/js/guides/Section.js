/**
 * 
 * 
 * 
 * @constructor Section
 * 
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function Section() {
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
						var drop = new Drag();
						drop.makeDropable(".dropspotty");
						
						// When you add a section fade in the save button 
						$("#save_guide").fadeIn();
						$('.sp_section_controls').first().show();
						
						var newSection = $('#tabs-' + selectedTab + ' .sp_section_controls').last();
						newSection.trigger('click');

						Layout().highlightLayout(newSection.parent());
					    

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
				$('.sp_section_controls').removeClass('sp_section_selected');
				$('#layout_options_content').data('selected-section', '');

				$(this).toggleClass('sp_section_selected');
				var selectedSectionId = $(this).parent().attr('id').split('_')[1];
				$('#layout_options_content').data('selected-section', selectedSectionId);
				Layout().activateLayoutButtons();
				// Highlight the layout that is associated with the section. 
				Layout().highlightLayout($(this).parent())
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
			
			}

			
		}
	};

	return mySection;
}
