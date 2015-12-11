/**
 * Provides the click events for the the layouts section in the flyout. 
 *  
 *
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function layout() {
	"use strict";

	var myLayout = {
		settings : {
			singleColumnButton : $('#col-single'),
			twoColumnButton : $('#col-double'),
			fourEightColumnButton : $('#col-48'),
			threeColumnButton : $('#col-triple'),
			eightFourColumnButton : $('#col-84'),
			bigMiddleThreeColumnButton : $('#col-363'),
			sectionDataUrl : 'helpers/section_data.php'
		},
		strings : {

		},
		bindUiActions : function() {

			myLayout.activateLayoutButtons();

		},
		init : function() {


		
			document.addEventListener("DOMContentLoaded", function() {
				myLayout.initialLayout();
				myLayout.selectedLayout();
				myLayout.layoutSections();
				myLayout.bindUiActions();
				myLayout.activateLayoutButtons();
				myLayout.highlightLayout($('.sp_section'));
			});

		},

		initialLayout : function() {
			/**
			 * This appends an initial section when you are adding a new tab.
			 */
			if ($('[id^=section]').length == 0) {
				$.ajax({
					url : myLayout.settings.sectionDataUrl,
					type : 'POST',
					data : {
						action : 'create'
					},
					dataType : 'html',
					success : function(html) {
						$('div#tabs-0').append(html);
					}
				});

			}
		},
		moveColumnContent : function(moveColumns, sectionId) {
			/**
			 * 
			 * This function takes content from one column and moves it to another. 
			 * 
			 */
			for (var k in moveColumns) {
				
				var sourceColumn = moveColumns[k][0];
				var targetColumn = moveColumns[k][1];
				console.log(sourceColumn);
				
				var content = $('#section_' + sectionId + ' #container-' + sourceColumn + ' .portal-column').children();
				console.log('#' + sectionId + ' #container-' + sourceColumn + ' .portal-column');
				console.log('#' + sectionId + ' #container-' + targetColumn + ' .portal-column')
				$('#section_' + sectionId + '  #container-' + targetColumn + ' .portal-column').append(content);
				
			}
		
			
			


		},

		selectedLayout : function() {
			$('.layout-icon').not(this).each(function() {
				$(this).removeClass('active-layout-icon');
			});
		},
		
		layouts : {
			/** 
			 * This object stores the layouts as they stored in the database as keys,
			 * the selectors for the buttons that are used to change the layout,
			 * and the pure css classes that are appended to the columns. 
			 */
			
			'0-12-0' : {
				selector : '#col-single',
				pureClasses : [ 'hidden-column', 'pure-u-1', 'hidden-column' ],
				moveColumns : {firstToSecond : [0,1], thirdToSecond : [2,1]} 
				
			},
			'6-6-0' : {
				selector : '#col-double',
				pureClasses : [ 'pure-u-1-2', 'pure-u-1-2', 'hidden-column' ],
				moveColumns : {thirdToSecond : [2,1]}
			},
			'4-8-0' : {
				selector : '#col-48',
				pureClasses : [ 'pure-u-1-4', 'pure-u-3-4', 'hidden-column' ],
				moveColumns : {thirdToSecond : [2,1]}
			},
			'8-4-0' : {
				selector : '#col-84',
				pureClasses : [ 'pure-u-3-4', 'pure-u-1-4', 'hidden-column' ],
				moveColumns : {thirdToSecond : [2,1]}
			},
			'4-4-4' : {
				selector : '#col-triple',
				pureClasses : [ 'pure-u-1-3', 'pure-u-1-3', 'pure-u-1-3' ],
				moveColumns : {}
			},
			'3-6-3' : {
				selector : '#col-363',
				pureClasses : [ 'pure-u-1-4', 'pure-u-1-2', 'pure-u-1-4' ],
				moveColumns : {}
			}
		},
		highlightLayout : function(sectionSelector) {
			/**
			 * This function highlights the icon for the currently selected layout 
			 * 
			 */
			
			if (typeof sectionSelector.data() != 'undefined' ) {
				
				var dataLayout = sectionSelector.first().attr('data-layout');
			} else {
				var dataLayout = '6-6-0';


			}

			console.log(dataLayout);

			
			for (var k in myLayout.layouts) {	
				if (dataLayout === k) {
					console.log("Adding active...");
					$(myLayout.layouts[k].selector).addClass('active-layout-icon');
				} else {
					$(myLayout.layouts[k].selector).removeClass('active-layout-icon');

				} 
				
			  
			}
			
		},
		layoutSection : function(sectionId, layout) {
			/** 
			 * This function lays out the columns in a section.  
			 * It takes a section id and a layout (three digits seperated by a hyphen) that is used as a key for the 
			 * layouts object. That object stores the pure classes that need to 
			 * appended to change the layout. 
			 * 
			 */
			var firstColumn = 'div#section_' + sectionId + ' div#container-0';
			var secondColumn = 'div#section_' + sectionId + ' div#container-1';
			var thirdColumn = 'div#section_' + sectionId + ' div#container-2';
		
			var section = 'div#section_' + sectionId;

			for ( var k in myLayout.layouts) {
				if (layout === k) {


					$(firstColumn).attr('class', '');
					$(secondColumn).attr('class', '');
					$(thirdColumn).attr('class', '');

					$(firstColumn).addClass(myLayout.layouts[k].pureClasses[0]);
					$(secondColumn).addClass(myLayout.layouts[k].pureClasses[1]);
					$(thirdColumn).addClass(myLayout.layouts[k].pureClasses[2]);

					
					myLayout.moveColumnContent(myLayout.layouts[k].moveColumns, sectionId);
					
					$(section).data('layout', k)

				}
			}
		},
		layoutSections : function() {

			/**
			 * This function goes through each of the sections in 
			 * all of the tabs and applies the layoutSection function to them.
			 * The layout key/id is persisted in the database and available 
			 * from a data attribute.
			 */
			$('div[id^="section_"],div[id^="section_new"]').each(function() {
				var sectionId = $(this).attr('id').split('section_')[1];
				var layout = $('div#section_' + sectionId).data('layout');

				myLayout.layoutSection(sectionId, layout);

			});
		},

		
		
		
		activateLayoutButtons : function() {
			/**
			 *  
			 * This function goes through the layouts object and binds a click event to 
			 * each of the button selectors. Each button is also given a data attribute 
			 * with the layout selection that is passed to the layoutSection function and
			 * added to the section as a data attribute that is persisted in the database
			 */
		
			for ( var k in myLayout.layouts) {
				var selector = myLayout.layouts[k].selector;
				
				$(selector).data('layout', k);


				$(selector).on('click', function() {
					var selectedSection = $('#layout_options_content').data().selectedSection;

					$('.layout-icon').removeClass('active-layout-icon');
					$(this).addClass('active-layout-icon');

					myLayout.layoutSection(selectedSection,$(this).data().layout)
				    $("#save_guide").fadeIn();
			    });
			}
		}
	}
	return myLayout;
};