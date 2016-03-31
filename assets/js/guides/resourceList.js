/**
 * Object that encompasses the functionality of the custom list flyout.
 * 
 * 
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function resourceList() {

	"use strict";

	var myDatabaseToken = {

			"label": "",
			"record_id": "",
			"token_string": ""


	};	
	var myResourceList = {

			settings: {
				/** This contains configuration details like URLs and sets up any jQuery selectors that will be used in the object.  **/			

				autoCompleteUrl: "../includes/autocomplete_data.php?collection=records&term=",
				autoCompleteUrlAzList: "../includes/autocomplete_data.php?collection=azrecords&term=",
				plusletDataUrl: "helpers/fetch_pluslet_data.php",
				dbListButton: $(".dblist-button"),
				dbListButtons: $(".db-list-buttons"),
				dbListContent: $('.db-list-content'),
				dbListResults: $('.db-list-results'),
				dbListDraggable : $(".db-list-item-draggable"),
				dbListResetButton: $('.dblist-reset-button'),
				dbSearchResults: $('.databases-searchresults'),
				dbSearchBox: $('.databases-search'),
				listItem: $(".db-list-item-draggable"),
				resetButton: $(".dblist-reset-button"),
				listLabel: $(".db-list-label"),
				listResults: $(".db-list-results"),
				limitAz: $('#limit-az'),
				showDescriptionToggle: $(".show-description-toggle"),
				showIconsToggle: $(".show-icons-toggle"),
				showNoteToggle: $(".include-note-toggle"),
				click_count: 0,
				searchTermMinimumLength: 3,
				linkListEditBtn: $('#linklist_edit_colorbox_btn'),

			},

			strings: {
				/** This contains any long strings that need to be used in the object or bits of HTML markup. **/

				noResults: "<li><span class=\"no-box-results\">No Results</span></li>",
				displayToggles: "<div><span class='show-icons-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
				" Icons  </span><span class='show-description-toggle db-list-toggle'><i class='fa fa-minus'></i> <i class='fa fa-check'></i>" +
				" Description </span><span class='include-note-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
				" Note </span></div>",
				removeListItemBtn: "<span class='db-list-remove-item' style='float:right; cursor:'><i class='fa fa-remove'></i></span>"
			},

			init: function () {
				/**
				 * @memberof ResourceList
				 */
				/** @method
				 * This function does inital setup for the object. It should call the bindUiActions function 
				 **/

				document.addEventListener("DOMContentLoaded", function(event) { 

				myResourceList.bindUiActions();
			    myResourceList.editLinkList();

				});


				if($('.link-list')) {
				//	myResourceList.editLinkList($('.link-list').html());
				}


				return myResourceList;
			},


			bindUiActions: function () {
				/**  Used to bind the object's UI actions. Like 'click' or 'hover'. */

				myResourceList.addToList();
				myResourceList.resetList();
				myResourceList.toggleIcons();

				myResourceList.databaseSearch();
				myResourceList.addListToPage();
				myResourceList.removeFromList();
				
			},

			addToList: function () {
				/** This function adds the selected result to the list of database tokens. */
				$('.databases-searchresults').on("click", '.add-to-list-button', function (e) {

					myResourceList.settings.dbListButtons.show();
					myResourceList.settings.dbListContent.show();

					var databaseToken = Object.create(myDatabaseToken);
					databaseToken.label = $(this).attr('data-label').trim();
					databaseToken.record_id = $(this).val();

					var tokenHtml = "<li class='db-list-item-draggable' value='" + databaseToken.record_id + "'>" +
						"<span class='db-list-label'>" + databaseToken.label + "</span>" +
						myResourceList.strings.removeListItemBtn +
						myResourceList.strings.displayToggles;

					myResourceList.settings.dbListResults.append(tokenHtml);
					myResourceList.settings.dbListResults.sortable();


					myResourceList.settings.dbListResults.disableSelection();
					$('.db-list-item-draggable').last().find('.fa-check').hide();

				});
			},

			removeFromList: function() {
				$('body').on('click', '.fa-remove', function() {

					$(this).parent().parent('li').remove();
				});
			},

			resetList: function () {
				/** This function resets the list of database tokens. **/


				myResourceList.settings.dbListResetButton.on("click", function () {
					myResourceList.settings.dbListResults.empty();
					myResourceList.settings.dbSearchBox.val("");
				});
			},

			toggleOptions: function (toggleElement) {
				{

					/** This function toggles the display options for a database token . **/

					toggleElement.find('.fa-minus').toggle();
					toggleElement.find('.fa-check').toggle();

					toggleElement.toggleClass("active");

					toggleElement.children().find('.fa-minus').toggle();


					var include_icons = toggleElement.parent().find('.show-icons-toggle').hasClass('active') | 0;
					var include_description = toggleElement.parent().find('.show-description-toggle').hasClass('active') | 0;
					var display_note = toggleElement.parent().find('.include-note-toggle').hasClass('active') | 0;

					var display_options = '' + include_icons + '' + include_description + '' + display_note + "";
					toggleElement.parent().parent().data({ 'display_options': display_options });

				}
			},

			toggleIcons: function () {

				/** This function toggles the icons for the toggle options **/

				$('body').on("click", ".show-description-toggle", function () {
					myResourceList.toggleOptions($(this));
				});
				$('body').on("click", ".show-icons-toggle", function () {

					myResourceList.toggleOptions($(this));
				});
				$('body').on("click", ".include-note-toggle", function () {
					myResourceList.toggleOptions($(this));
				});

				$('#show_all_icons_input').on('click', function() {
					myResourceList.toggleOptions($(".show-icons-toggle"));
				});

				$('#show_all_desc_input').on('click', function() {
					myResourceList.toggleOptions($(".show-description-toggle"));
				});

				$('#show_all_notes_input').on('click', function() {
					myResourceList.toggleOptions($(".include-note-toggle"));
				});
			},



			databaseSearch: function () {
				/** This function posts a string to the Autocomplete class to create a list of results. **/
				myResourceList.settings.dbSearchBox.keyup(function () {

					myResourceList.settings.dbSearchResults.empty();
					var search_url;
					var search_term = myResourceList.settings.dbSearchBox.val();

					if ($('#limit-az').prop("checked")) {
						search_url = myResourceList.settings.autoCompleteUrlAzList;
					} else {
						search_url = myResourceList.settings.autoCompleteUrl;
					}

					if ($(this).val() === "") {
						myResourceList.settings.dbSearchResults.html(myResourceList.strings.noResults);

					}


					if (search_term.length > myResourceList.settings.searchTermMinimumLength) {

						$.get(search_url + search_term, function (data) {
							if (data.length !== 0) {

								for (var i = 0; i < 10; i++) {
									try {
										if (data[i]['content_type'] == "Record") {
											
											myResourceList.settings.dbSearchResults.append("<li data-pluslet-id='" + data[i].id + "' class=\"db-list-item database-listing\">"
													+ "<div class=\"pure-g\"><div class=\"pure-u-4-5 list-search-label\" title=\"" + data[i].label + "\">" + data[i].label + "</div>"
													+ "<div class=\"pure-u-1-5\" style=\"text-align:right;\">"
													+ "<button data-label='" + data[i].label + "' value='" + data[i].id + "' class=\"add-to-list-button pure-button pure-button-secondary\"><i class=\"fa fa-plus\"></i></button></div></div>"
													+ "<div><a href='" + data[i].location_url + "' target='_blank'>" + data[i].location_url + "</a></div>"
												    + "</li>");
										}

									} catch (e) {

									}
								}
							} else {
								myResourceList.settings.dbSearchResults.html(myResourceList.strings.noResults);
							}
						});

					} else {
						myResourceList.settings.dbSearchResults.html(myResourceList.strings.noResults);

					}

				});
			},

			addListToPage: function () {
				myResourceList.settings.dbListButton.on("click", function () {

					$('#save_guide').show();

					// Create a container to append everything to
					var linkListContainer = document.createElement("div");
					linkListContainer.setAttribute("class","link-list-container");


					// Create the top and bottom elements
					var linkListTextTop = document.createElement("div");
					linkListTextTop.setAttribute("class","link-list-text-top");
					var linkListTextTopText = document.createTextNode("");
					linkListTextTop.appendChild(linkListTextTopText);

					var linkListTextBottom = document.createElement("div");
					linkListTextBottom.setAttribute("class","link-list-text-bottom");
					var linkListTextBottomText = document.createTextNode("");
					linkListTextTop.appendChild(linkListTextBottomText);
					linkListContainer.appendChild(linkListTextBottom);

					// Create the token list ul
					var tokenList = document.createElement("ul");
					tokenList.setAttribute("class","link-list");


					// Go through the draggables and turn them into <li>s with the token
					$(".db-list-item-draggable").each(function (data) {
						var title = $(this).find('.db-list-label').text();
						var record_id = $(this).val();

						// Grab the options
						var display_options = $(this).data().display_options;


						// If these are undefined, make them 0
						display_options = (typeof display_options === 'undefined') ? "000" : display_options;


						if ($(this).text()) {
							var linkListItem =  document.createElement("li");
							linkListItem.setAttribute("class","token-list-item");
							var tokenText = document.createTextNode("{{dab},{" + record_id + "},{" + title + "}" + ",{" + display_options + "}}");
							linkListItem.appendChild(tokenText);
							tokenList.appendChild(linkListItem);
							linkListContainer.appendChild(tokenList);
						}
					});

					linkListContainer.appendChild(linkListTextBottom);

					// append the token list to the body of the pluslet
					$('#LinkList-body').html(linkListContainer);

				});
			},
			editLinkList: function() {

				$('.link-list-container .link-list .token-list-item').each(function() {
					var tokenArray = myResourceList.tokenToArray($(this).text());

					var dbListItem = document.createElement("li");
					dbListItem.setAttribute("class", "db-list-item-draggable");
					dbListItem.setAttribute("value", tokenArray[1]);

					var toggleDiv = document.createElement("div");

					var dbListSpan = document.createElement("span");
					dbListSpan.setAttribute("class", "db-list-label");

					var dbListLabel = document.createTextNode(tokenArray[2]);
					dbListSpan.appendChild(dbListLabel);

					dbListItem.appendChild(dbListSpan);

					

					var showIcons = document.createElement("span");
					var showDescription = document.createElement("span");
					var includeNote = document.createElement("span");

					var faMinus = document.createElement("i");
					var faCheck = document.createElement("i");

					var iconsLabel = document.createTextNode("Icons");
					var descLabel = document.createTextNode("Description");
					var noteLabel = document.createTextNode("Label");

					$('.db-list-results').append(dbListItem);
				});

				// Take the token list and turn into draggable markup when in edit view

			},
			tokenToArray : function(token) {

				var tokenArray = token.split("{").join('').split("}").join('').split(',');

				return tokenArray;
			}

	};
	return myResourceList; 
}
