$(document).ready(function() {

var FindBoxSearch = {
		/**
		 * Object literal that encompasses the functionality of the find box search in the 'Find Boxes' flyout.
		 *
		 * 
		 * @author little9 (Jamie Little)
		 * 
		 */

		settings : {
			/** This contains configuration details like URLs and sets up any jQuery selectors that will be used in the object.  **/			
			findBoxSearchBox : $('.findbox-search'),
			findBoxSearchResults : $('.findbox-searchresults'),
			autoCompleteUrl : '../includes/autocomplete_data.php?collection=pluslet&term='

		},

		strings : {
			/** This contains any long strings that need to be used in the object or bits of HTML markup. **/
			
			noSearchResults : "<li><span class=\"no-box-results\">No Results</span></li>",
			findBoxSearchButtons : "<div class=\"pure-u-2-5\" style=\"text-align:right;\">"
				+ "<button class=\"clone-button pure-button pure-button-secondary\">Link</button>&nbsp; "
				+ "<button class=\"copy-button pure-button pure-button-secondary\">Copy</button></div></div></li>"

		},

		init : function() {
		/** This function does inital setup for the object. It should call the bindUiActions function */

			this.bindUiActions();

		},

		bindUiActions : function() {
		/**  Used to bind the object's UI actions. Like 'click' or 'hover'. */

			this.activateFindBoxSearch();

		},

		search : function(search_term) {
	    /** This function posts a string to the Autocomplete class and returns results or indicates that no results were found. */
			$.get(
					FindBoxSearch.settings.autoCompleteUrl + search_term,
					function(data) {

						console.log(data);

						if (data.length != 0) {

							for (var i = 0; i < data.length; i++) {

								if (data[i]['content_type'] == "Pluslet") {

									FindBoxSearch.settings.findBoxSearchResults
									.append("<li data-pluslet-id='"
											+ data[i].id
											+ "' class=\"pluslet-listing\">"
											+ "<div class=\"pure-g\">"
											+ "<div class=\"pure-u-3-5 box-search-label\" title=\""
											+ data[i].label
											+ "\">"
											+ data[i].label
											+ "</div>"
											+ FindBoxSearch.strings.findBoxSearchButtons);

								}

							}
						} else {
							FindBoxSearch.settings.findBoxSearchResults
							.html("<li><span class=\"no-box-results\">No Results</span></li>");
						}
					});

		},

		activateFindBoxSearch : function() {
		    /** A function to bind the keyup event to the searchbox.  **/

			FindBoxSearch.settings.findBoxSearchBox.keyup(function(data) {
				FindBoxSearch.settings.findBoxSearchResults.empty();
				var search_term = FindBoxSearch.settings.findBoxSearchBox.val();
				FindBoxSearch.search(search_term);

			});
		}

};


	FindBoxSearch.init();

});
