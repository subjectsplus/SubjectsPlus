/**
 * Provides the click events for the the layouts section in the flyout. 
 *  
 * @constructor Layout
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function Layout() {
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

			myLayout.initialLayout();
			myLayout.selectedLayout();
			myLayout.layoutSections();
			myLayout.bindUiActions();

			document.addEventListener("DOMContentLoaded", function() {
				myLayout.checkDataLayout(myLayout.layouts);
				myLayout.activateLayoutButtons();
			});

		},

		initialLayout : function() {
			/**
			 * This append an initial section when you are adding a new tab.
			 */
			if ($('[id^=section]').length == 0) {
				console.log("append the section!");
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
		moveColumnContent : function(sourceColumn, targetColumn) {
			var currentTab = $('#tabs').tabs('option', 'selected');
			var SectionId = $('#tabs-' + parseInt(currentTab)).children().attr(
					'id');
			var content = $(
					'#' + slider_section_id + ' #container-' + source_column
							+ ' .portal-column').children();
			$(
					'#' + SectionId + '  #container-' + target_column
							+ ' .portal-column').append(content);

		},

		selectedLayout : function() {
			$('.layout-icon').not(this).each(function() {
				$(this).removeClass('active-layout-icon');
			});
		},
		layouts : {
			'0-12-0' : {
				selector : '#col-single',
				pureClasses : [ 'hidden-column', 'pure-u-1', 'hidden-column' ]
			},
			'6-6-0' : {
				selector : '#col-double',
				pureClasses : [ 'pure-u-1-2', 'pure-u-1-2', 'hidden-column' ]
			},
			'4-8-0' : {
				selector : '#col-48',
				pureClasses : [ 'pure-u-1-4', 'pure-u-3-4', 'hidden-column' ]
			},
			'8-4-0' : {
				selector : '#col-84',
				pureClasses : [ 'pure-u-1-2', 'pure-u-1-2', 'hidden-column' ]
			},
			'4-4-4' : {
				selector : '#col-triple',
				pureClasses : [ 'pure-u-1-3', 'pure-u-1-3', 'pure-u-1-3' ]
			},
			'3-6-3' : {
				selector : '#col-363',
				pureClasses : [ 'pure-u-1-3', 'pure-u-1-2', 'pure-u-1-3' ]
			}
		},
		layoutSection : function(sectionId, layout) {
			/** This function lays out the columns in a section.  
			 * It takes a section id and a layout (three digits seperated by a hyphen) that is used as a key for the 
			 * layouts object. That object stores the pure classes that need to 
			 * appended to change the layout. 
			 * 
			 * */
			var firstColumn = 'div#section_' + sectionId + ' div#container-0';
			var secondColumn = 'div#section_' + sectionId + ' div#container-1';
			var thirdColumn = 'div#section_' + sectionId + ' div#container-2';
			;
			var section = 'div#section_' + sectionId;

			for ( var k in myLayout.layouts) {
				if (layout === k) {
					console.log(firstColumn);

					$(firstColumn).attr('class', '');
					$(secondColumn).attr('class', '');
					$(thirdColumn).attr('class', '');

					$(firstColumn).addClass(myLayout.layouts[k].pureClasses[0]);
					$(secondColumn)
							.addClass(myLayout.layouts[k].pureClasses[1]);
					$(thirdColumn).addClass(myLayout.layouts[k].pureClasses[2]);

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
			$('div[id^="section_"]').each(function() {
				var sectionId = $(this).attr('id').split('section_')[1];
				var layout = $('div#section_' + sectionId).data('layout');

				myLayout.layoutSection(sectionId, layout);

			});
		},

		activateLayoutButtons : function() {

			for ( var k in myLayout.layouts) {
				var selector = myLayout.layouts[k].selector;
				$(selector).data('layout', k);
				console.log($(selector).data());

				$(selector)
						.on(
								'click',
								function() {

									$('.layout-icon').removeClass(
											'active-layout-icon');
									$(this).addClass('active-layout-icon');
									console.log($(this).data().layout);
									// Just doing the first section during initial testing, before buttons have been added.
									myLayout.layoutSection($('.sp_section')
											.first().attr('id').split('_')[1],
											$(this).data().layout)
									$("#save_guide").fadeIn();

								});
			}
		}
	}
	return myLayout;
};