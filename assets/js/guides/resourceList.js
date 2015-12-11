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
				searchTermMinimumLength: 3

			},

			strings: {
				/** This contains any long strings that need to be used in the object or bits of HTML markup. **/

				noResults: "<li><span class=\"no-box-results\">No Results</span></li>",
				displayToggles: "<div><span class='show-icons-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
				" Icons  </span><span class='show-description-toggle db-list-toggle'><i class='fa fa-minus'></i> <i class='fa fa-check'></i>" +
				" Description </span><span class='include-note-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
				" Note </span></div>"
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

				});
				
				return myResourceList;
			},


			bindUiActions: function () {
				/**  Used to bind the object's UI actions. Like 'click' or 'hover'. */

				myResourceList.addToList();
				myResourceList.resetList();
				myResourceList.toggleIcons();

				myResourceList.databaseSearch();
				myResourceList.addListToPage();




			},

			addToList: function () {
				/** This function adds the selected result to the list of database tokens. */
				$('body').on("click", '.add-to-list-button', function () {

					myResourceList.settings.dbListButtons.show();
					myResourceList.settings.dbListContent.show();

					var databaseToken = Object.create(myDatabaseToken);
					databaseToken.label = $(this).attr('data-label').trim();
					databaseToken.record_id = $(this).val();



					myResourceList.settings.dbListResults.append("<li class='db-list-item-draggable' value='" + databaseToken.record_id + "'><span class='db-list-label'>" + databaseToken.label +
							"</span>" + myResourceList.strings.displayToggles);
					myResourceList.settings.dbListResults.sortable();
					myResourceList.settings.dbListResults.disableSelection();
					$('.db-list-item-draggable').last().find('.fa-check').hide();


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
			},



			databaseSearch: function () {
				/** This function posts a string to the Autocomplete class to create a list of results. **/
				myResourceList.settings.dbSearchBox.keyup(function () {

					myResourceList.settings.dbSearchResults.empty();
					var search_url;
					var search_term = myResourceList.settings.dbSearchBox.val();
					var limit_az = myResourceList.settings.limitAz.prop("checked");

					if (limit_az) {
						search_url = myResourceList.settings.autoCompleteUrl;
					} else {
						search_url = myResourceList.settings.autoCompleteUrlAzList;
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
											
											myResourceList.settings.dbSearchResults.append("<li data-pluslet-id='" + data[i].id + "' class=\"db-list-item database-listing\">" +
													"<div class=\"pure-g\"><div class=\"pure-u-4-5 list-search-label\" title=\"" + data[i].label + "\">" + data[i].label + "</div>" +
													"<div class=\"pure-u-1-5\" style=\"text-align:right;\">" +
													"<button data-label='" + data[i].label + "' value='" + data[i].id + "' class=\"add-to-list-button pure-button pure-button-secondary\"><i class=\"fa fa-plus\"></i></button></div></div></li>");
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
				/** This function adds a CKEditor to the page with the resource list that the user has created. It has a interval atteched to wait for the CKEditor to show up before setting the contents.  **/
				myResourceList.settings.dbListButton.on("click", function () {
					var ps = pluslet();
					ps.dropPluslet('', 'Basic', '');
					var waitCKEDITOR = setInterval(function () {
						if (window.CKEDITOR) {
							clearInterval(waitCKEDITOR);

							var token_string = "<ul class='token-list'>";

							$(".db-list-item-draggable").each(function (data) {
								var title = $(this).find('.db-list-label').text();
								var record_id = $(this).val();

								// Grab the options
								var display_options = $(this).data().display_options;


								// If these are undefined, make them 0
								display_options = (typeof display_options === 'undefined') ? "000" : display_options;


								if ($(this).text()) {
									token_string += "<li class='token-list-item'>{{dab},{" + record_id + "},{" + title + "}" + ",{" + display_options + "}}</li>";
								}
							});

							token_string += "</ul>";



							var ck_index = Object.keys(CKEDITOR.instances).length - 1;
							CKEDITOR.instances[Object.keys(CKEDITOR.instances)[ck_index]].setData(token_string.trim());

							myResourceList.settings.click_count++;
							myResourceList.settings.dbListResults.empty();
						}
					}, 100);
				});
			}
	};
	return myResourceList; 
};