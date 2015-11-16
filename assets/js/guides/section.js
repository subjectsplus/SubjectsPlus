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
			    mySection.viewSectionControls();
		    });
			
		},
		viewSectionControls : function() {
			
			$('.sptab').each(function() { 
				 if($(this).children().size() > 1) {
					 $(this).children().find('.sp_section_controls').show();
					 $(this).children().find('.sp_section_controls').parent().addClass('section_selected_area');
		
				 } 	
				});
		},
		makeAddSection : function(lstrSelector)
		///////////////
		//function to add section to current tab	
		//////////////
		{

			$(lstrSelector).on('click', function() {
				
				//$(tabs).tabs();
				var selectedTab = $('#tabs').tabs('option', 'selected');
				console.log(selectedTab);
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
						
						$('div#tabs-' + selectedTab)
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
				
				// Removes existing highlights and controls 
				$('.sp_section_controls').removeClass('sp_section_selected');
				$('.sp_section').removeClass('section_selected_area');

				$('#layout_options_content').data('selected-section', '');

				// This adds the classes for highlighting 
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
			
			// Hide the first section control in each of the tabs:
			$('.sp_section:first-child .sp_section_controls').hide();
			
				$('.ui-tabs-nav li').on('click', function() {
			    // When you click another tab, hide the controls for the first section 
			    // and mark that tab as active
				var tab = $(this).attr('aria-controls');
				var tabChildren = $('#' + tab).children();
				
				
			
				tabChildren.find('.sp_section_controls').first().trigger('click');
				$('.sp_section').removeClass('section_selected_area');
				tabChildren.find('.sp_section_controls').parent().removeClass('section_selected_area');
				

			});
			
			
		}
	};

	return mySection;
}
