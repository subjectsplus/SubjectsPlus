/**
 * 
 */
function getLayout() {

	var Layout = {
			settings : {

			},
			strings : {

			},
			bindUiActions : function () {

				$( "#col-single" ).click(function() {

					Layout.changeLayout(0, 14);
					Layout.checkDataLayout(Layout.layouts);
					Layout.moveColumnContent(0, 1);
					Layout.moveColumnContent(2, 1);
					Layout.selectedLayout();
					$(this).addClass("active-layout-icon");
				});

				$( "#col-double" ).click(function() {

					Layout.changeLayout(6, 12);
					Layout.checkDataLayout(Layout.layouts);
					Layout.moveColumnContent(2, 1);
					Layout.selectedLayout();
					$(this).addClass("active-layout-icon");
				});

				$( "#col-48" ).click(function() {
					Layout.changeLayout(4, 24);
					Layout.checkDataLayout(Layout.layouts);
					Layout.moveColumnContent(2, 1);
					Layout.selectedLayout();
					$(this).addClass("active-layout-icon");
				});

				$( "#col-84" ).click(function() {
					Layout.changeLayout(8, 12);
					Layout.checkDataLayout(Layout.layouts);
					Layout.moveColumnContent(2, 1);
					Layout.selectedLayout();
					$(this).addClass("active-layout-icon");
				});

				$( "#col-triple" ).click(function() {
					Layout.changeLayout(4, 8);
					Layout.checkDataLayout(Layout.layouts);
					Layout.selectedLayout();
					$(this).addClass("active-layout-icon");
				});

				$( "#col-363" ).click(function() {
					Layout.changeLayout(3, 9);
					Layout.checkDataLayout(Layout.layouts);
					Layout.selectedLayout();
					$(this).addClass("active-layout-icon");
				});

			},
			init : function() {
				Layout.bindUiActions();
				Layout.selectedLayout();
				Layout.checkDataLayout();
				Layout.makeSectionSlider();
			},
			layouts : {
				"0-12-0": "#col-single",
				"6-6-0":  "#col-double",
				"4-8-0":  "#col-48",
				"8-4-0":  "#col-84",
				"4-4-4":  "#col-triple",
				"3-6-3":  "#col-363"
			},
			layoutOffsets : {

			},

			moveColumnContent : function(source_column, target_column) {

				var current_tab = $('#tabs').tabs('option', 'selected');
				var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
				var content = $("#" + slider_section_id + " #container-" + source_column + " .portal-column").children();

				$("#" + slider_section_id + "  #container-" + target_column + " .portal-column").append(content);
			},
			changeLayout : function(first_column, second_column) {

				var current_tab = $('#tabs').tabs('option', 'selected');
				var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');

				$("#slider_" + slider_section_id).slider('values',0, first_column);
				$("#slider_" + slider_section_id).slider('values',1, second_column );
			},
			checkDataLayout : function(layouts) {
				// Check section data-layout on pageLoad and hide empty containers
				// Highlight "current/active" layout

				Layout.selectedLayout();

				var current_tab = $('#tabs').tabs('option', 'selected');
				var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
				var dataLayoutConfig = $("#" + slider_section_id).attr('data-layout');

				for (var layout in layouts) {
					if (dataLayoutConfig === layout) {
						$("#" + slider_section_id + " #container-2").hide();
						$(layouts[layout]).addClass("active-layout-icon");
					}
				}


			},
			selectedLayout : function () {
				$('.layout-icon').not(this).each(function () {
					$(this).removeClass("active-layout-icon");
				});
			},
			reLayout : function (lintSectionID, lc, cc, rc) {
				///////////////
				// function to correctly size layout of guide
				//////////////


				if (parseInt(lc) === 0) {
					$('div#section_' + lintSectionID + ' div#container-0').width(0);
					$('div#section_' + lintSectionID + ' div#container-0').hide();
				} else {
					$('div#section_' + lintSectionID + ' div#container-0').show();
					$('div#section_' + lintSectionID + ' div#container-0').width(lc.toString() - 2 + '%');
				}

				$('div#section_' + lintSectionID + ' div#container-1').width(cc.toString() - 2 + '%');

				if (parseInt(rc) === 0) {
					$('div#section_' + lintSectionID + ' div#container-2').width(0);
					$('div#section_' + lintSectionID + ' div#container-2').hide();
				} else {
					$('div#section_' + lintSectionID + ' div#container-2').show();
					$('div#section_' + lintSectionID + ' div#container-2').width(rc.toString() - 2 + '%');
				}


			}, layoutSections: function () {
				$('div[id^="section_"]').each(function () {
	                //section id
	                var sec_id = $(this).attr('id').split('section_')[1];
	                var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

	                var lw = parseInt(lobjLayout[0]) * 7;
	                var mw = parseInt(lobjLayout[1]) * 7;
	                var sw = parseInt(lobjLayout[2]) * 7;


	                try {
	                    Layout.reLayout(sec_id, lw, mw, sw);
	                } catch (e) {

	                	console.log("Error:" + e);

	                }


	            });
	        },
	        
	        makeSectionSlider : function() {
	        	 $( 'div[id^="slider_section"]' ).each(function()
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
	      				   change: function( event, ui ) {
	      				       // figure out our vals
	      				       var left_col = ui.values[0];
	      				       var right_col = 12 - ui.values[1];
	      				       var center_col = 12 - (left_col + right_col);
	      				       var extra_val = left_col + "-" + center_col + "-" + right_col;

	      				       var lw = parseInt(left_col) * 8;
	      	    			       var mw = parseInt(center_col) * 8;
	      	    			       var sw = parseInt(right_col) * 8 - 3;

	      				       $( "div#section_" + sec_id ).attr( 'data-layout', extra_val);

	      				       Layout.reLayout(sec_id, lw, mw, sw);
        	      				         				       
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
	}
	return Layout;
};