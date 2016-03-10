/**
 * Object that sets up the click events and options associated with pluslets.
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function pluslet() {
	"use strict";

	var myPluslet = {
			settings : {
				hideBodyContent : ['.pluslet.type-heading','.pluslet.type-worldcat' ,'.pluslet.type-catalog','.pluslet.type-relguide', '.pluslet.type-articleplus','.pluslet.type-toc','.pluslet.type-googlebooks','.pluslet.type-googlescholar','.pluslet.type-googlesearch','.pluslet.type-guideselect','.pluslet.type-newdbs','.pluslet.type-guidesearch','.pluslet.type-newguides','.pluslet.type-experts', '.pluslet.type-guidelist', '.pluslet.type-collectionlist', '.pluslet.type-card'],
				hideLinksNewTab : ['.pluslet.type-heading','.pluslet.type-video','.pluslet.type-worldcat','.pluslet.type-catalog','.pluslet.type-relguide','.pluslet.type-articleplus','.pluslet.type-toc','.pluslet.type-googlebooks','.pluslet.type-googlescholar','.pluslet.type-googlesearch','.pluslet.type-guideselect','.pluslet.type-guidesearch', '.pluslet.type-experts', '.pluslet.type-collectionlist'],
				hideTitleBar :    ['.pluslet.type-heading','.pluslet.type-toc', '.pluslet.type-card'],
				hideMarkFavorite :['.pluslet.type-worldcat','.pluslet.type-catalog','.pluslet.type-articleplus','.pluslet.type-relguide',
				'.pluslet.type-googlebooks','.pluslet.type-googlescholar','.pluslet.type-googlesearch','.pluslet.type-guideselect','.pluslet.type-newdbs','.pluslet.type-guidesearch','.pluslet.type-newguides','.pluslet.type-experts', '.pluslet.type-guidelist', '.pluslet.type-collectionlist']
	
			}, 
			strings : {

			},
			init : function() {
				
				var g = guide();
				var subjectId = g.getSubjectId();
				
				myPluslet.bindUiActions();
				myPluslet.makeEditable('a[id*=edit]', subjectId);
				myPluslet.makeDeleteable('a[id*=delete]');
				myPluslet.makeDeleteable('.section_remove', 'sections');

			},
			bindUiActions : function() {
				myPluslet.expandPluslet();
				myPluslet.expandCollapseIndividualPluslet();
				myPluslet.boxItemDropPluslet();
				
				////////////////////
			    // Make titlebar options box clickable
			    ///////////////////
			    $(document).on('change', '.onoffswitch-checkbox', function() {

			        var pluslet_id = $(this).parent().parent().parent().parent().attr('id') ;

			    	if( $('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') == -1)
			    	{
				    $('#' + pluslet_id).attr('name', 'modified-pluslet-' + $('#' + pluslet_id).attr('name'));
			    	}

			    	$('#response').hide();
			        $('#save_guide').fadeIn();
			    });
			    
			    
			    ///////////////////////////////
			    // Draw attention to TOC linked item
			    ///////////////////////////////

			    $(document.body).on('click','a[id*=boxid-]', function(event) {
			    	var tab_id = $(this).attr('id').split('-')[1];
			    	var box_id = $(this).attr('id').split('-')[2];

			        var selected_box = '.pluslet-' + box_id;

			    	$('#tabs').tabs('select', tab_id);

			        $(selected_box).effect('pulsate', {
			            times:1
			        }, 2000);
				//$(selected_box).animateHighlight('#dd0000', 1000);

			    });

			    
			    ////////////////////
			    // box-settings bind to show when clicking on gear or edit icon.
			    ///////////////////
			    $(document).on('click', 'a[id*=settings-]', function(event) {
			        $(this).parent().parent().parent().find('.box_settings').toggle();
			    });

			},
			dropPluslet : function(clone_id, item_type, origin_id, clone_title) {
				var g = guide();
				var subjectId = g.getSubjectId();
			    // Create new node below, using a random number

				var randomnumber=Math.floor(Math.random()*1000001);
				$('.portal-column-1:visible').prepend('<div class=\'dropspotty\' id=\'new-' + randomnumber + '\'></div>');

				// cloneid is used to tell us this is a clone
				var new_id = 'pluslet-cloneid-' + clone_id;
				// Load new data, on success (or failure!) change class of container to 'pluslet', and thus draggable in theory

				$('#new-' + randomnumber).fadeIn('slow').load('helpers/guide_data.php', {
					from: new_id,
					flag: 'drop',
					this_subject_id:  subjectId,
					item_type: item_type
				},
				function() {
					// 1.  remove the wrapper
					// 2. put the contents of the div into a variable
					// 3.  replace parent div (i.e., id='new-xxxxxx') with the content made by loaded file
					var content = $('#new-' + randomnumber).contents();

					if (content.find('input.clone-input')) {

						content.find('input.clone-input').val(origin_id);
					}

					if (clone_title) {
						content.find('[id^=pluslet-new-title]').val(clone_title);
					}

					$('#new-' + randomnumber).replaceWith(content);
					$('#response').hide();
					//Make save button appear, since there has been a change to the page
					$('#save_guide').fadeIn();
					//Close main flyout when a pluslet is dropped
					$('#main-options').slideReveal('hide');

					
				});
			}, 
			getParameterByName : function(name) {
				name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
				var regex = new RegExp('[\\?&]' + name + '=([^&#]*)'),
				results = regex.exec(location.search);
				return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
			},
			

			expandPluslet : function() {
				//Expand/Collapse Trigger CSS for all Pluslets on a Tab
				document.addEventListener("DOMContentLoaded", function() {

					$( '#expand_tab' ).click(function() {

						$(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
						$('.pluslet_body').toggle();
						$('.pluslet_body').toggleClass('pluslet_body_closed');
					});

				});

			},


			makeEditable : function(lstrSelector, subjectId) {
				////////////////////////////////
				// MODIFY PLUSLET -- on click of edit (gear) icon
				////////////////////////////////

				$(document.body).on('click', lstrSelector, function(event) {
					var isclone;
					var edit_id = $(this).attr('id').split('-');

					////////////
					// Clone?
					////////////

					var clone = $('#pluslet-' + edit_id[1]).attr('class');
					if (clone.indexOf('clone') !== -1) {
						isclone = 1;
					} else {
						isclone = 0;
					}

					/////////////////////////////////////
					// Load the form elements for editing
					/////////////////////////////////////

					$('#' + 'pluslet-' + edit_id[1]).load('helpers/guide_data.php', {
						edit : edit_id[1],
						clone : isclone,
						flag : 'modify',
						type : edit_id[2],
						this_subject_id : subjectId
					}, function() {
						///////////////////////////////////////////////
						// 1.  remove the wrapper
						// 2. put the contents of the div into a variable
						// 3.  replace parent div (i.e., id='xxxxxx') with the content made by loaded file
						///////////////////////////////////////////////

						var cnt = $('#' + 'pluslet-' + edit_id[1]).contents();
						$('#' + 'pluslet-' + edit_id[1]).replaceWith(cnt);

						/////////////////////////////////////
						// Make unsortable for the time being
						/////////////////////////////////////

						$('#pluslet-' + edit_id[1]).addClass('unsortable');

						//////////////////////////////////////
						// We're changing the attribute here for the global save
						//////////////////////////////////////

						if (edit_id[2] !== undefined) {
							var new_name = 'modified-pluslet-' + edit_id[2];
							$('#pluslet-' + edit_id[1]).attr('name', new_name);
						} else {
							$('#pluslet-' + edit_id[1]).attr('name', 'modified-pluslet');
						}

						var h = help();
						h.makeHelpable('img[class*=help-]');

						//display box_settings for editable pluslet
						$('#' + 'pluslet-' + edit_id[1]).find('.box_settings').delay(500).show();

						//close box settings panel
						$( '.close-settings' ).click(function() {      
							$('#' + 'pluslet-' + edit_id[1]).find('.box_settings').hide();
						});

						// Hide body-content option from box settings options based on TYPE

						for (var key in myPluslet.settings.hideBodyContent) {
							var plusletType = myPluslet.settings.hideBodyContent[key];
							$(plusletType).find('.body_set').addClass('hide-settings');
						}

						for (var key in myPluslet.settings.hideLinksNewTab) {
							var plusletType = myPluslet.settings.hideLinksNewTab[key];
							$(plusletType).find('.links_set').addClass('hide-settings');
						}

						for (var key in myPluslet.settings.hideTitleBar) {
							var plusletType = myPluslet.settings.hideTitleBar[key];
							$(plusletType).find('.titlebar_set').addClass('hide-settings');
						}

						// Hide make favorite option from box settings options based on TYPE

						for (var key in myPluslet.settings.hideMarkFavorite) {
							var plusletType = myPluslet.settings.hideMarkFavorite[key];
							$(plusletType).find('.fav_set').addClass('hide-settings');
						}

					});

					//Make save button appear, since there has been a change to the page
					$('#response').hide();
					$('#save_guide').fadeIn();

					return false;
				});
			}, 
			boxItemDropPluslet : function () {
			    $('.box-item').dblclick('click', function(event) {
			    	
			    	
			        var edit_id = $(this).attr('id').split('-');
			        myPluslet.dropPluslet('', edit_id[2], '');

			    });
			},

			makeDeleteable : function( lstrSelector, lstrType )
			/////////////////////////////
			//DELETE SECTION
			/////////////////////////////

			{
				if( lstrType === 'sections' )
				{

					$('.guidewrapper').on('click', lstrSelector ,function(event) {

						var delete_id = $(this).parent().parent().attr('id').split('_')[1];
						var element_deletion = this;

						//get all pluslets in a section
						var this_sections_pluslets = $('#section_' + delete_id).children().find('.pluslet');
						
						if(this_sections_pluslets.length == 0) {

							$('<div id="dialog" class=\'delete_confirm\' title=\'Are you sure?\'>All content in this section will be deleted.</div>').dialog({
								autoOpen: false,
								modal: true,
								width: 'auto',
								height: 'auto',
								resizable: false,
								buttons: {
									'Yes': function() {
										// Remove node
										$(element_deletion).parent().parent().remove();
										$('#response').hide();
										var save = saveSetup();
										save.saveGuide();
										$('#save_guide').fadeOut();
										$( this ).dialog( 'close' );
										return false;
									},
									Cancel: function() {
										$( this ).dialog( 'close' );
									}
								}
							});

							$('.delete_confirm').first().dialog('open');
							return false;

						} else {

							//check for child pluslets and delete pluslet if none exist, otherwise
							//display dialog box indicating child pluslets exist
							myPluslet.fetchAllClones(this_sections_pluslets, element_deletion);

						}


					});
				}else
				{
					////////////////////////////
					// DELETE PLUSLET
					// removes pluslet from DOM; change must be saved to persist
					/////////////////////////////

					$('.guidewrapper').on('click', lstrSelector ,function(event) {
						var g = guide();
						var subjectId = g.getSubjectId();
						var deleteId = $(this).attr('id').split('-')[1];
						var elementDeletion = this;

						//pluslet has been saved
						if(deleteId > 0) {
							//check for child pluslets and delete pluslet if none exist, otherwise
							//display dialog box indicating child pluslets exist
							//console.log(subjectId);
							myPluslet.fetchAllClonesByPlusletId(deleteId, subjectId, elementDeletion);
						} else {
							//pluslet has not been saved so just remove the node
							$('<div class=\'delete_confirm\' title=\'Are you sure?\'></div>').dialog({
								autoOpen: true,
								modal: false,
								width: 'auto',
								height: 'auto',
								resizable: false,
								dialogClass: 'topindex',
								buttons: {
									'Yes': function() {

										// Remove node
										$(elementDeletion).parents('.pluslet').remove();
										$( this ).dialog( 'close' );
										return false;
									},
									Cancel: function() {
										$( this ).dialog( 'close' );
									}
								}
							});


						}


					});
				}
			}, 
			
			
			expandCollapseCSS: function () {
			    //Expand/Collapse Trigger CSS for all Pluslets on a Tab
				document.addEventListener("DOMContentLoaded", function() {
					$("#expand_tab").click(function () {
						$(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
						$('.pluslet_body').toggle();
						$('.pluslet_body').toggleClass('pluslet_body_closed');
					});
				});
			},

		    expandCollapseIndividualPluslet: function () {
				// expand collapse individual pluslets
				document.addEventListener("DOMContentLoaded", function() {
					// add font-awesome icon to pluslet
					$('.pluslet_body').after('<div class="expand_collapse"><i class="fa fa-angle-up" title="Expand Collapse Box"></i></div>');

					//remove copy of expand_collapse trigger when pluslet is a clone
					$('.pluslet_body .pluslet_body').next('.expand_collapse').remove();

					$('.expand_collapse i').click(function () {
						console.log($(this));
						if ($(this).hasClass('fa-angle-up')) {

							$(this).removeClass('fa-angle-up');
							$(this).addClass('fa-angle-down');
						} else if ($(this).hasClass('fa-angle-down')) {

							$(this).removeClass('fa-angle-down');
							$(this).addClass('fa-angle-up');
						}

						$(this).parent().prev('div.pluslet_body').slideToggle('slow');

					});
				});
			},

			fetchAllClones: function(this_sections_pluslets, element_deletion) {
				//get all cloned pluslets
				var i = 0;
				$(this_sections_pluslets).each(function() {


					var section_pluslet_id = $(this).find('id').context.id.split('-')[1];

					var req = $.ajax({
						url: "helpers/fetch_cloned_pluslets.php",
						type: "GET",
						data: 'master_id=' + section_pluslet_id,
						dataType: "json"
					});

					var success = function(resp) {
						var clones = [];
						$.each(resp.cloned_pluslets, function( key, value ) {
							clones.push(value);
						});

						if(clones.length > 0) {
							var titles = [];
							$.each(clones, function( key, value ) {
								titles.push(value.title);
							});

							$('<div>This section cannot be deleted because it has linked boxes.<br>' + titles + '</div>').dialog({
								autoOpen: true,
								modal: false,
								width: 'auto',
								height: 'auto',
								resizable: false,
								buttons: {
									Cancel: function () {
										$(this).dialog('close');
									}
								}
							});
							return false;

						}
					};

					var err = function(req, status, err) {
						return err;
					};

					req.then(success, err);

				});

			},

			fetchAllClonesByPlusletId: function(pluslet_id, subjectId, elementDeletion) {

				var req = $.ajax({
					url: "helpers/fetch_cloned_pluslets.php",
					type: "GET",
					data: 'master_id=' + pluslet_id,
					dataType: "json"
				});

				var success = function(resp) {
					var responses = [];
					$.each(resp, function( key, value ){
						responses.push(value);
					});

					var objs = [];
					$.each(responses, function( key, value ) {
						objs.push(value);
					});
					var clones = [];
					$.each(objs, function( key, value ) {
						clones = value;
					});


					if(clones.length > 0) {
						var titles = [];

						$.each(clones, function( key, value ){
							titles.push(value.title);
						});

						$('<div>This box cannot be deleted because it has linked boxes.' + titles + '</div>').dialog({
							autoOpen: true,
							modal: false,
							width: 'auto',
							height: 'auto',
							resizable: false,
							buttons: {
								Cancel: function () {
									$(this).dialog('close');
								}
							}
						});
						return false;
					} else {
						$('<div class=\'delete_confirm\' title=\'Are you sure?\'></div>').dialog({
							autoOpen: true,
							modal: false,
							width: 'auto',
							height: 'auto',
							resizable: false,
							dialogClass: 'topindex',
							buttons: {
								'Yes': function() {
									// Delete pluslet from database
									$('#response').load('helpers/guide_data.php', {
												delete_id: pluslet_id,
												subject_id: subjectId,
												flag: 'delete'
											},
											function() {
												$('#response').fadeIn();
												$('#save_guide').fadeIn();

											});

									// Remove node
									$(elementDeletion).parents('.pluslet').remove();
									$( this ).dialog( 'close' );
									return false;
								},
								Cancel: function() {
									$( this ).dialog( 'close' );
								}
							}
						});
						return false;
					}



				};

				var err = function(req, status, err) {
					return err;
				};

				req.then(success, err);
			}
	}

	return myPluslet;   
}