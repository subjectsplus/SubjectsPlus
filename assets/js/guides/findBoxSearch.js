/**
 * Object that encompasses the functionality of the find box search
 * in the 'Find Boxes' flyout.
 * 
 * findBoxSearch
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function findBoxSearch() {
	var myFindBoxSearch = {

		/**
		 * This contains configuration details like URLs and sets up any jQuery
		 * selectors that will be used in the object. *
		 */
		settings: {
			findBoxSearchBox: $('.findbox-search'),
			findBoxSearchResults: $('.findbox-searchresults'),
			autoCompleteUrl: '../includes/autocomplete_data.php?collection=pluslet&term=',
			closeButton : $(".close-settings"),
			allGuidesAutoCompleteUrl : "../includes/autocomplete_data.php?collection=all_guides&term="
		},

		/**
		 * This contains any long strings that need to be used in the object or
		 * bits of HTML markup. *
		 */
		strings: {

			noSearchResults: "<li><span class=\"no-box-results\">No Results</span></li>",
			findBoxSearchButtons: "<div class=\"pure-u-2-5\" style=\"text-align:right;\">"
			+ "<button class=\"clone-button pure-button pure-button-secondary\">Link</button>&nbsp; "
			+ "<button class=\"copy-button pure-button pure-button-secondary\">Copy</button></div></div></li>"

		},

		/**
		 * This function does inital setup for the object. It should call the
		 * bindUiActions functions
		 */
		init: function () {

			this.bindUiActions();

		},

		/**
		 * @member FindBoxSearch Used to bind the object's UI actions. Like
		 *         'click' or 'hover'.
		 */
		bindUiActions: function () {

			this.activateFindBoxSearch();
			this.loadCloneMenu();
		},

		/**
		 * @member {Object} This function posts a string to the Autocomplete
		 *         class and returns results or indicates that no results were
		 *         found.
		 */
		search: function (search_term) {
			$.get(
					myFindBoxSearch.settings.autoCompleteUrl
					+ search_term,
					function (data) {


						if (data.length != 0) {

							for (var i = 0; i < data.length; i++) {

								if (data[i]['content_type'] == "Pluslet") {

								    var listItem = "<li data-pluslet-type='" +data[i].type + "' data-pluslet-id='"
										+ data[i].id
										+ "' class=\"pluslet-listing\">"
										+ "<div class=\"pure-g\">"
										+ "<div class=\"pure-u-3-5 box-search-label\" title=\""
										+ data[i].label
										+ "\">"
										+ data[i].label
										+ "</div>" + myFindBoxSearch.strings.findBoxSearchButtons;
									
										myFindBoxSearch.settings.findBoxSearchResults.append(listItem);
										console.log();

								    
								}

							}
						} else {
							myFindBoxSearch.settings.findBoxSearchResults
								.html("<li><span class=\"no-box-results\">No Results</span></li>");
						}
					});

		},

		/** @member {Object} A function to bind the keyup event to the searchbox. * */
		activateFindBoxSearch: function () {

			myFindBoxSearch.settings.findBoxSearchBox
				.keyup(function (data) {
					myFindBoxSearch.settings.findBoxSearchResults.empty();
					var search_term = myFindBoxSearch.settings.findBoxSearchBox
						.val();
					myFindBoxSearch.search(search_term);

				});
		},
		activateBoxSettingsCloseButton: function () {
			//close box settings panel
			myFindBoxSearch.settings.closeButton.click(function () {
				$(this).parent(".box_settings").hide();
			});
		}, loadCloneMenu : function() {
			
			$.get(myFindBoxSearch.settings.allGuidesAutoCompleteUrl, function(data) { 

				for(var i = 0; i<data.length;i++) {
			        var subject_id = data[i].id;
					$('.guide-list').append("<option data-subject-id='" + subject_id + "' class=\"guide-listing\">" + data[i].label + "</li>");

				}

			});

			$('.guide-list').on('change', function(data) {
				var subject_id = $("option:selected", this).attr('data-subject-id');

				$('.pluslet-list').empty();

				$.get("../includes/autocomplete_data.php?collection=guide&subject_id=" + subject_id + " &term="
						,function(data) {

						for(var i = 0; i<data.length;i++) {
							$('.pluslet-list').append("<li data-pluslet-type='" +data[i].type + "' data-pluslet-id='" + data[i].id + "' class=\"pluslet-listing\"><div class=\"pure-g\"><div class=\"pure-u-3-5 box-search-label\" title=\""+ data[i].label + "\">"  + data[i].label + "</div><div class=\"pure-u-2-5\" style=\"text-align:right;\"><button class=\"clone-button pure-button pure-button-secondary\">Link</button>&nbsp;<button class=\"copy-button pure-button pure-button-secondary\">Copy</button></div></div></li>");
				
						}
				});	
				
			});
		}

		

	};



	return myFindBoxSearch;

};
