/**
 * Set ups click events and tabs functionality on the guide page.
 *  
 * @constructor Tabs
 * 
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function Tabs() {
	"use strict";
    var myTabs = {
        settings: {
            tabTitle: $('#tab_title'),
            tabContent: $('#tab_content'),
            tabCounter: $('#tabs').data().tabCount,
            tabs: $('#tabs').tabs(),
            tabsDialog : $("#dialog"),
            dialog: $('#dialog').dialog,
            editTabDialog : $("#dialog_edit"),
            externalLink: 'input[name=\'tab_external_link\']',
            dataExternalLink: 'li[data-external-link]',
            saveButton: $('#save_guide'),
            tabExternalUrl: 'input[name=\'tab_external_url\']',
            findBoxTabs: $('#find-box-tabs')
        },
        strings: {
            tabTemplate: "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",
            confirmPrompt: "Are you sure you want to remove all boxes?"
        },
        bindUiActions: function () {
            myTabs.removePlusletsFromCurrentTab();
            myTabs.makeTabsClickable();
       
        },
        init: function () {
            myTabs.setupTabs();
            myTabs.hideTabsFirstSectionSlider();
            myTabs.bindUiActions();
            myTabs.targetBlankLinks();
            //Find Box Tabs - Browse and Search
           

            //Find Box Tabs - Browse and Search
            myTabs.settings.findBoxTabs.tabs();
            
            $(tabs).tabs('select', 0);

			var sec = Section();
			sec.makeAddSection('a[id="add_section"]');

        
        },
        setupTabs: function () {

            var myDialog = myTabs.settings.tabsDialog.dialog({
                autoOpen: false,
                modal: true,
                buttons: {
                    Add: function () {
                        addTab();
                        $(this).dialog("close");
                    },
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                },
                open: function () {
                    $(this).find(myTabs.settings.externalLink).hide();
                    $(this).find(myTabs.settings.externalLink).prev().hide();
                    if (myTabs.settings.tabCounter > 0) {
                        $(this).find(myTabs.settings.externalLink).show();
                        $(this).find(myTabs.settings.externalLink).prev().show();
                    }
                },
                close: function () {
                    form[0].reset();
                }
            });

            //setup dialog to edit tab
            var editTabDialog = myTabs.settings.editTabDialog.dialog({
                autoOpen: false,
                modal: true,
                width: "auto",
                height: "auto",
                buttons: {
                    "Save": function () {
                        var id = window.lastClickedTab.replace("#tabs-", "");
                        console.log(window.lastClickedTab);
                        $('a[href="#tabs-' + id + '"]').text($('input[name="rename_tab_title"]').val());
                        $('a[href="#tabs-' + id + '"]').parent('li').attr('data-visibility', $('select[name="visibility"]').val());

                        if ($('a[href="#tabs-' + id + '"]').parent('li').attr('data-external-link') !== '') {
                            $('a[href="#tabs-' + id + '"]').each(function () {
                                var elementData = $._data(this),
                                    events = elementData.events;

                                var onClickHandlers = events['click'];
                                console.log(onClickHandlers);
                                // Only one handler. Nothing to change.
                                if (onClickHandlers.length === 1) {
                                    return;
                                }

                                onClickHandlers.splice(0, 1);
                            });
                        }

                        $('a[href="#tabs-' + id + '"]').parent('li').attr('data-external-link', $(myTabs.settings.tabExternalUrl).val());

                        if ($(myTabs.settings.tabExternalUrl).val() !== '') {
                            $('a[href="#tabs-' + id + '"]').on('click', function (evt) {
                                window.open($(this).parent('li').attr('data-external-link'), '_blank');
                                evt.stopImmediatePropagation();
                            });

                            $('a[href="#tabs-' + id + '"]').each(function () {
                                var elementData = $._data(this),
                                    events = elementData.events;

                                var onClickHandlers = events['click'];

                                // Only one handler. Nothing to change.
                                if (onClickHandlers.length === 1) {
                                    return;
                                }

                                onClickHandlers.splice(0, 0, onClickHandlers.pop());
                            });
                        }

                        //add/remove class based on tab visibility
                        if ($('select[name="visibility"]').val() === 1) {
                            $('a[href="#tabs-' + id + '"]').parent('li').removeClass('hidden_tab');
                        } else {
                            $('a[href="#tabs-' + id + '"]').parent('li').addClass('hidden_tab');
                        }

                        $(this).dialog("close");
                        $("#response").hide();
                        console.log('save guide fade in');
                        $('#save_guide').fadeIn();
                        
                    },
                    "Delete": function () {
                        var id = window.lastClickedTab.replace("#tabs-", "");

                        $('a[href="#tabs-' + id + '"]').parent().remove();
                        $('div#tabs-' + id).remove();
                        myTabs.settings.tabs.tabs("destroy");
                        myTabs.settings.tabs.tabs();
                        myTabs.settings.tabCounter--;
                        $(this).dialog("close");
                        $("#response").hide();
                        $('#save_guide').fadeIn();
                        //$('#save_template').fadeIn();
                    },
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                },
                open: function (event, ui) {
                    var id = window.lastClickedTab.replace("#tabs-", "");
                    $(this).find('input[name="rename_tab_title"]').val($('a[href="#tabs-' + id + '"]').text());
                    $(this).find('select[name="visibility"]').val($('a[href="#tabs-' + id + '"]').parent('li').attr('data-visibility'));

                    //external url add text input unless first tab
                    $(this).find(myTabs.settings.tabExternalUrl).val('');
                    $(this).find(myTabs.settings.tabExternalUrl).hide();
                    $(this).find(myTabs.settings.tabExternalUrl).prev().hide();
                    $(this).find(myTabs.settings.tabExternalUrl).val($('a[href="#tabs-' + id + '"]').parent('li').attr('data-external-link'));
                    if (id !== '0') {
                        $(this).find(myTabs.settings.tabExternalUrl).show();
                        $(this).find(myTabs.settings.tabExternalUrl).prev().show();
                    }
                },
                close: function () {
                    form[0].reset();
                }
            });

            //add click event for external url tabs
            $(myTabs.settings.dataExternalLink).each(function () {
                if ($(this).attr('data-external-link') !== "") {
                    $(this).children('a[href^="#tabs-"]').on('click', function (evt) {
                        window.open($(this).parent('li').attr('data-external-link'), '_blank');
                        evt.stopImmediatePropagation();
                    });

                    $(this).children('a[href^="#tabs-"]').each(function () {
                        var elementData = $._data(this),
                            events = elementData.events;

                        var onClickHandlers = events['click'];

                        // Only one handler. Nothing to change.
                        if (onClickHandlers.length === 1) {
                            return;
                        }

                        onClickHandlers.splice(0, 0, onClickHandlers.pop());
                    });
                }
            });

            // edit icon: removing or renaming tab on click
            myTabs.settings.tabs.delegate("span.alter_tab", "click", function (lobjClicked) {
                var List = $(this).parent().children("a");
                var Tab = List[0];
                window.lastClickedTab = $(Tab).attr("href");
                editTabDialog.dialog("open");
            });

            // addTab button: just opens the dialog
            $("#add_tab").button().click(function () {
                myDialog.dialog("open");
            });


            // addTab form: calls addTab function on submit and closes the dialog
            var form = myDialog.find("form").submit(function (event) {
                addTab();
                myDialog.dialog("close");
                event.preventDefault();
            });

            // actual addTab function: adds new tab using the input from the form above
            function addTab() {
                var tabTemplate = "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i><span></li>";

                var label = myTabs.settings.tabTitle.val() || "Tab " + $('#tabs').data().tabCount,
                    external_link = $('input#tab_external_link').val(),
                    id = "tabs-" + $('#tabs').data().tabCount,
                   
                    li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, label)),
                     
                    tabContentHtml = myTabs.settings.tabContent.val() || "Tab " + myTabs.settings.tabCounter + " content.";
                $(li).attr('data-external-link', external_link);
                console.log(id);
                $(li).attr('data-visibility', 1);
                console.log(id);
                myTabs.settings.tabs.find(".ui-tabs-nav").append(li);
                console.log($(li));
                
                //make tabs sortable
                $(function () {
                    $(tabs).find(".ui-tabs-nav").sortable({
                        axis: "x",
                        stop: function (event, ui) {
                            if ($(ui.item).attr("id") === 'add_tab' || $(ui.item).parent().children(':first').attr("id") !== 'add_tab' || $(ui.item).attr('data-external-link') !== '')
                                $(tabs).find(".ui-tabs-nav").sortable("cancel");
                            else {
                                $(tabs).tabs( "refresh" );
                                $(tabs).tabs("destroy");
                                $(tabs).tabs();
                                $(tabs).tabs('select', 0);
                                $("#response").hide();
                                $("#save_guide").fadeIn();
                            }
                        }
                    });
                });


                $.ajax
                    ({
                        url: "helpers/section_data.php",
                        type: "POST",
                        data: { action: 'create' },
                        dataType: "html",
                        success: function (html) {
                            

                            myTabs.settings.tabs.append("<div id='" + id + "' class=\"sptab\">" + html
                                + "</div>");

                            
                            myTabs.settings.saveButton.fadeIn();


                            myTabs.settings.tabs.tabs();

                            if (external_link === '') {
                                $(tabs).tabs("refresh");
                                $(tabs).tabs('select', $('#tabs').data().tabCount);
                            } else {
                             
                                myTabs.settings.tabs.tabs('select', 0);
                            }

                            if ($(li).attr('data-external-link') !== '') {
                                $(li).children('a[href^="#tabs-"]').on('click', function (evt) {
                                    window.open($(this).parent('li').attr('data-external-link'), '_blank');
                                    evt.stopImmediatePropagation();
                                });
                            }

                            $(li).children('a[href^="#tabs-"]').each(function (data) {
                                var events = $._data(data, "events");
                                  
                                if (events) {
                                    console.log(events);
                                    var onClickHandlers = events['click'];

                                    // Only one handler. Nothing to change.
                                    if (onClickHandlers.length === 1) {
                                        return;
                                    }

                                    onClickHandlers.splice(0, 0, onClickHandlers.pop());
                                }
                                });
                            
                            $('#tabs').data().tabCount++;
                        }
                    });



                //override submit for form in edit tab dialog to click rename button
                $("#dialog_edit").find("form").submit(function (event) {
                    $(this).parent().parent().find('span:contains("Rename")').click();
                    event.preventDefault();
                });
                
                // Move the expand tab to the end
                $('#expand_tab').appendTo('.ui-tabs-nav')
            }
        },
            hideTabsFirstSectionSlider: function() {
                var current_tab = $('#tabs').tabs('option', 'selected');
                var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
                $('#tabs-' + parseInt(current_tab) + ' div#' + slider_section_id + ' .sp_section_controls').first().hide();

                $('#tabs').on('click', function () {
                    // Hide the first section's controls
                    var current_tab = $('#tabs').tabs('option', 'selected');
                    var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
                    $('#tabs-' + parseInt(current_tab) + ' div#' + slider_section_id + ' .sp_section_controls').first().hide();
                });
            },

   
        removePlusletsFromCurrentTab: function () {
            //remove all pluslets from current tab
            $('a.remove_pluslets').on('click', function () {
                var currPanel = $("#tabs").tabs('option', 'active');
                if (confirm(myTabs.strings.confirmPrompt)) {
                    $("#tabs-" + currPanel).find('.pluslet').remove();
                    $("#save_guide").fadeIn();
                }
            });
        },
        targetBlankLinks: function () {
            // open links in new tab if box_setting target_blank_links is checked.
            //this is for admin side, user view also has function in /subjects/guide.php
            var $target_blank_links = $(".target_blank_links");
            $target_blank_links.each(function () {
                if ($("input:checked")) {
                    $(this).find('a').attr('target', '_blank');
                }
            });
        }, 
        makeTabsClickable : function() {
            ////////////////////
            // Make page tabs clickable
            ///////////////////
            $(document.body).on('click','a[id*=tab-]', function(event) {
                var tab_id = $(this).attr("id").split("-");
               var selected_tab = "#pluslet-" + box_id[1];
               myTabs.setupTabs(tab_id[1]);

            });
        }
    }
    return myTabs;
}