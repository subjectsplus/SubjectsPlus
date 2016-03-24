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

				myResourceList.loadLinkListEditModal();





			},

			addToList: function () {
				/** This function adds the selected result to the list of database tokens. */
				$('.databases-searchresults').on("click", '.add-to-list-button', function (e) {

					myResourceList.settings.dbListButtons.show();
					myResourceList.settings.dbListContent.show();

					var databaseToken = Object.create(myDatabaseToken);
					databaseToken.label = $(this).attr('data-label').trim();
					databaseToken.record_id = $(this).val();

					var tokenHtml = "<li class='db-list-item-draggable' value='" + databaseToken.record_id + "'><span class='db-list-label'>" + databaseToken.label +
					"</span>" + myResourceList.strings.displayToggles;

					myResourceList.settings.dbListResults.append(tokenHtml);
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
												    + "<div>" + data[i].location_url + "</div>"
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


					if($(this).attr('data-linklist-tmp-pluslet_id') > 0) {
						var pluslet_id = $.attr('data-linklist-tmp-pluslet_id');
					} else {
						var pluslet_id = $("div[name='new-pluslet-LinkList']").attr('id');
					}
					//console.log('pluslet_id: ' + $(this).attr('data-linklist-tmp-pluslet_id'));

					var linkList = "<div id='LinkList-body'>";
					linkList += '<div class="link-list-text-top"></div>';

					linkList += '<ul class="link-list" data-link-list-pluslet-id="' + pluslet_id + '">'


						$(".db-list-item-draggable").each(function (data) {
							//console.log('data: ' + data)

							var label = $(this).find('.db-list-label').text();
							//console.log('label: ' + label);

							var record_id = $(this).val();
							//console.log('record_id: ' + record_id);

							// Grab the options
							var display_options = $(this).data().display_options;
							//console.log('display_options: ' + display_options);

							// If these are undefined, make them 0
							display_options = (typeof display_options === 'undefined') ? "000" : display_options;

							if ($(this).text()) {
								linkList += "<li data-link-list-label='" + label + "' data-link-list-item-id='" + record_id + "' data-link-list-display-options='" + display_options + "'>" +
								"{{dab},{" + record_id + "},{placeholder},{" + display_options + "}}</li>";

							}
					});

					linkList += '</ul>';

					linkList += '</div>';

					myResourceList.settings.click_count++;
					myResourceList.settings.dbListResults.empty();

					var new_pluslet_id = $("div[name='new-pluslet-LinkList']").attr('id');

					console.log('pluslet_id: ' + $(this).attr('data-linklist-tmp-pluslet-id'));

					linkList += '<div class="link-list-text-bottom"></div>';


					if(new_pluslet_id) {
						$('#' + new_pluslet_id).find('.pluslet_body').append(linkList.trim());
					} else {
						$('#pluslet-' + pluslet_id).find('.pluslet_body').append(linkList.trim());
						//console.log(linkList.trim());
					}

					$.colorbox.close();
				});
			},

			loadLinkListEditModal: function() {

				if($(this).attr('data-linklist-tmp-pluslet_id') > 0) {
					var pluslet_id = $.attr('data-linklist-tmp-pluslet_id');
				} else {
					var pluslet_id = $("div[name='new-pluslet-LinkList']").attr('id');
				}

				myResourceList.settings.linkListEditBtn.on('click', function() {

					$.ajax({
						url: myResourceList.settings.plusletDataUrl,
						type: "GET",
						dataType: "json",
						data: { pluslet_id: pluslet_id },
						success: function (data) {
							console.log(data.body);
							var body = data.body;
							var ul = $(body).find('ul.link-list:first-child');

							var newlinkList = "";

							$('ul.link-list li').each(function() {

								var databaseToken = Object.create(myDatabaseToken);
								databaseToken.label = $(this).attr('data-link-list-label');
								databaseToken.record_id = $(this).attr('data-link-list-item-id');

								newlinkList += "<li class='db-list-item-draggable' value='" + databaseToken.record_id + "'><span class='db-list-label'>" + databaseToken.label +
									"</span></li>";


							});

							$('.db-list-results').append(newlinkList.trim());


						}
					});

					myResourceList.settings.dbListButtons.show();
					myResourceList.settings.dbListContent.show();



				});
				$('.linklist_edit_colorbox_btn').colorbox({inline:true, width:"90%", height:"90%"});

			}

	};
	return myResourceList; 
};
