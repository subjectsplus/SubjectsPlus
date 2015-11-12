/**
 * 
 * This initializes the guide interface by calling the init method on the
 * modules in the setupFunctions list
 * 
 * @author little9 (Jamie Little)
 * 
 */
/* jslint browser: true */
/* global $, jQuery, alert */

function analytics() {

	var myAnalytics = {

		settings : {
			statsDataUrl : './helpers/stats_data.php?short_form='

		},
		strings : {
			
		
		},
		bindUiActions : function() {
		},
		init : function() {
			myAnalytics.displayAnalytics()
		},
		displayAnalytics : function() {

			$('.tab-clicks').empty();

			$.get(myAnalytics.settings.statsDataUrl
					+ $('#shortform').data().shortform, function(data) {

				if (data.total_views != null) {
					console.log(data.total_views)
					$('.no-analytics').hide();
					$('.total-views-count').show();
					$('.total-views-count').html(data.total_views);

				}

				if (data.tab_clicks != "") {
					$('.tab-click-header').show();
					for (key in data.tab_clicks) {

						$('.tab-clicks').append(
								'<li class="tab-click">' + key + ' : '
										+ data.tab_clicks[key] + '</li>');
					}

				}

			});

		}
	};

	return myAnalytics;
}