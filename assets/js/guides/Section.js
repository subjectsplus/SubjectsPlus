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
		    
		    mySection.makeSectionSlider();
		    mySection.bindUiActions();
		},
		makeAddSection : function(lstrSelector)
		///////////////
		//function to add section to current tab	
		//////////////
		{

			$(lstrSelector).on('click', function() {

				var lintSelected = $(tabs).tabs('option', 'selected');

				$.ajax({
					
					url : mySection.settings.sectionDataPath,
					type : "POST",
					data : {
					action : 'create'
					
					},
					dataType : "html",
					success : function(html) {

						$('div#tabs-' + lintSelected).append(html);
						$(document).scrollTop($('body').height());

						// Make sure that the new section can accept drops
						var drop = new Drag();
						drop.makeDropable(".dropspotty");
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
				console.log(selectedSectionId);
				$('#layout_options_content').data('selected-section', selectedSectionId);
				Layout().activateLayoutButtons();
				Layout().highlightLayout($(this).parent())
				
			});
		},
		makeSectionSlider : function () {
			
		}
	};

	return mySection;
}
