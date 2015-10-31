/**
 * Provides the click events for the the layouts section in the flyout. 
 *  
 * @constructor Layout
 * 
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function Layout() {
	"use strict";

	var myLayout = {
	        settings: {
	            singleColumnButton: $('#col-single'),
	            twoColumnButton: $('#col-double'),
	            fourEightColumnButton: $('#col-48'),
	            threeColumnButton: $('#col-triple'),
	            eightFourColumnButton: $('#col-84'),
                bigMiddleThreeColumnButton : $('#col-363'), 
	            sectionDataUrl : 'helpers/section_data.php'
			},
			strings : {

			},
			bindUiActions : function () {

			    myLayout.activateLayoutButtons();

			},
			init : function() {
				
			    myLayout.initialLayout();
			    myLayout.selectedLayout();
			    myLayout.layoutSections();
			    myLayout.bindUiActions();
			    document.addEventListener("DOMContentLoaded", function () {
			        myLayout.checkDataLayout(myLayout.layouts);
			        myLayout.activateLayoutButtons();
			    });
				
                

			},
			initialLayout : function() {
			    // Append an intital section
			    if ($('[id^=section]').length == 0) {
			        console.log("append the section!");
			        $.ajax({
			            url: myLayout.settings.sectionDataUrl,
			            type: 'POST',
			            data: { action: 'create' },
			            dataType: 'html',
			            success: function (html) {
			                $('div#tabs-0').append(html);
			            }
			        });

			       
			    }
			},
			layouts : {
				'0-12-0': '#col-single',
				'6-6-0':  '#col-double',
				'4-8-0':  '#col-48',
				'8-4-0':  '#col-84',
				'4-4-4':  '#col-triple',
				'3-6-3':  '#col-363'
			},
		

			moveColumnContent : function(source_column, target_column) {

				var current_tab = $('#tabs').tabs('option', 'selected');
				var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
				var content = $('#' + slider_section_id + ' #container-' + source_column + ' .portal-column').children();

				$('#' + slider_section_id + '  #container-' + target_column + ' .portal-column').append(content);
			},
			changeLayout : function(first_column, second_column) {

				var current_tab = $('#tabs').tabs('option', 'selected');
				var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');

				console.log(current_tab);
				console.log(slider_section_id);
				$('#slider_' + slider_section_id).slider();
				$('#slider_' + slider_section_id).slider('values',0, first_column);
				$('#slider_' + slider_section_id).slider('values',1, second_column );
			},
			checkDataLayout : function(layouts) {
				// Check section data-layout on pageLoad and hide empty containers
				// Highlight 'current/active' layout

			
				var current_tab = $('#tabs').tabs('option', 'selected');
				var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
				console.log("Slider ID:" + slider_section_id)
				var dataLayoutConfig = $('#' + slider_section_id).data().layout;
				
				for (var layout in layouts) {
					if (dataLayoutConfig === layout) {
						$(layouts[layout]).addClass('active-layout-icon');
					}
				}

				myLayout.layoutSections();

			},
			selectedLayout : function () {
				$('.layout-icon').not(this).each(function () {
					$(this).removeClass('active-layout-icon');
				});
			},
			reLayout : function (lintSectionID, lc, cc, rc) {
				///////////////
				// function to correctly size layout of guide
				//////////////

			    console.log(lc + " " + cc + " " + rc);
				if (parseInt(lc) < 0) {
					$('div#section_' + lintSectionID + ' div#container-0').width(0);
					$('div#section_' + lintSectionID + ' div#container-0').hide();
				} else {
					$('div#section_' + lintSectionID + ' div#container-0').show();
					$('div#section_' + lintSectionID + ' div#container-0').width(lc.toString()  + '%');
				}

				$('div#section_' + lintSectionID + ' div#container-1').width(cc.toString()  + '%');

				if (parseInt(rc) < 0) {
					$('div#section_' + lintSectionID + ' div#container-2').width(0);
					$('div#section_' + lintSectionID + ' div#container-2').hide();
				} else {
					$('div#section_' + lintSectionID + ' div#container-2').show();
					$('div#section_' + lintSectionID + ' div#container-2').width(rc.toString()  + '%');
				}


			}, layoutSections: function () {

			    //layout each section
			    $('div[id^="section_"]').each(function () {
			        //section id
			        var sec_id = $(this).attr('id').split('section_')[1];
			        var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');
			        var lw = parseInt(lobjLayout[0]) * 8;
			        var mw = parseInt(lobjLayout[1]) * 8;
			        var sw = parseInt(lobjLayout[2]) * 8 - 3;
			        try {
			            myLayout.reLayout(sec_id, lw, mw, sw);
			        } catch (e) {
			        }


	            });
			},

			activateLayoutButtons: function () {
                 myLayout.settings.singleColumnButton.on('click',function () {
                    
					myLayout.changeLayout(0, 14);
					myLayout.checkDataLayout(myLayout.layouts);
					myLayout.moveColumnContent(0, 1);
					myLayout.moveColumnContent(2, 1);
					myLayout.selectedLayout();
					$(this).addClass('active-layout-icon');
					myLayout.layoutSections();
				});

				myLayout.settings.twoColumnButton.on('click',function() {
				
					myLayout.changeLayout(6, 12);
					myLayout.checkDataLayout(myLayout.layouts);
					myLayout.moveColumnContent(2, 1);
					myLayout.selectedLayout();
					$(this).addClass('active-layout-icon');
					myLayout.layoutSections();
				});

				myLayout.settings.fourEightColumnButton.on('click',function() {
					myLayout.changeLayout(4, 24);
					myLayout.checkDataLayout(myLayout.layouts);
					myLayout.moveColumnContent(2, 1);
					myLayout.selectedLayout();
					$(this).addClass('active-layout-icon');
					myLayout.layoutSections();
				});

				myLayout.settings.eightFourColumnButton.on('click',function () {
					myLayout.changeLayout(8, 12);
					myLayout.checkDataLayout(myLayout.layouts);
					myLayout.moveColumnContent(2, 1);
					myLayout.selectedLayout();
					$(this).addClass('active-layout-icon');
					myLayout.layoutSections();
				});

				myLayout.settings.threeColumnButton.on('click', function () {
					myLayout.changeLayout(4, 8);
					myLayout.checkDataLayout(myLayout.layouts);
					myLayout.selectedLayout();
					$(this).addClass('active-layout-icon');
					myLayout.layoutSections();
				});

				myLayout.settings.bigMiddleThreeColumnButton.on('click',function () {
					myLayout.changeLayout(3, 9);
					myLayout.checkDataLayout(myLayout.layouts);
					myLayout.selectedLayout();
					$(this).addClass('active-layout-icon');
					myLayout.layoutSections();
				});
			}
	        
	          
	}
	return myLayout;
};