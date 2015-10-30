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
		},
		init : function() {
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

		makeSectionSlider: function () {
		    $('div[id^=\'slider_section\']').each(function () {
		        //section id
		        var sec_id = $(this).attr('id').split('slider_section_')[1];
		        var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

		        $(this).slider({
		            range: true,
		            min: 0,
		            max: 12,
		            step: 1,
		            values: [lobjLayout[0], parseInt(lobjLayout[0]) + parseInt(lobjLayout[1])],
		            change: function (event, ui) {
		                // figure out our vals
		                var left_col = ui.values[0];
		                var right_col = 12 - ui.values[1];
		                var center_col = 12 - (left_col + right_col);
		                var extra_val = left_col + '-' + center_col + '-' + right_col;

		                var lw = parseInt(left_col) * 8;
		                var mw = parseInt(center_col) * 8;
		                var sw = parseInt(right_col) * 8 - 3;

		                $('div#section_' + sec_id).attr('data-layout', extra_val);


		                Layout().reLayout(sec_id, lw, mw, sw);

		                // Hide or show the third column if needed 

		                if (sw < 0) {
		                    $('#container-2').hide();
		                }

		                if (sw > 0) {
		                    $('#container-2').show();
		                }


		                //show save guide button
		                $('#response').hide();
		                $('#save_guide').fadeIn();
		            }
		        });
		    });
		}
	};

	return mySection;
}
