/**
 * Sets up some basic functonality related to the guide interface. If you see something that would be more appropriate 
 * in its own module feel free to move it.
 * 
 * @constructor GuideBase
 * 
 * 
 */

function GuideBase() {
	"use strict";

    var myGuideBase = {
        settings: {
            globalHeader: $("#header, #subnavcontainer"),
            sections: $('div[id^="section_"]'),
            newBox: $('#newbox'),
            sliderOptions: $("#slider_options"),
            layoutBox: $("#layoutbox"),
            slider: jQuery("#slider"),
            extra: jQuery("#extra"),
            mainColumnWidth: jQuery("#main_col_width"),
            saveLayout: jQuery("#save_layout"),
            tabs: $('#tabs'),
            tabTitle: $("#tab_title"),
            tabContent: $("#tab_content"),
            tabExteralDataLink: $('li[data-external-link]'),
            autoCompletebox: $('.find-guide-input'),
            startURL: '../guides/guide.php?subject_id=',
            spPath: document.URL.split('/')[3],
            subjectId: $('#guide-parent-wrap').data().subjectId,
            autoCompleteUrl: "../includes/autocomplete_data.php?collection=guide&subject_id=",
            favoritesUrl : "helpers/favorite_pluslets_data.php?staff_id="
        },
        strings: {
            tabTemplate: "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",
            noFavoritesText : "<li>No boxes have been marked as a favorite. To do so, click the gears button on the box you wish to mark as a Favorite and activate the Favorite toggle switch.</li>"

        },
        bindUiActions: function () {

        },
        init: function () {
            // Hides the global nav on load
            myGuideBase.settings.globalHeader.hide();
            myGuideBase.settings.newBox.hoverIntent(myGuideBase.hoverIntentConfig);
            myGuideBase.hoverIntentLayoutBox();
            myGuideBase.fixFlashFOUC();
            myGuideBase.loadGuideSearch();
            myGuideBase.expandCollapseCSS();
            myGuideBase.getUserFavoriteBoxes();
            myGuideBase.refreshFeeds();

        },
        
        hoverIntentConfig: {
            interval: 50,
            sensitivity: 4,
            timeout: 500
        },
        hoverIntentSliderConfig: {
            interval: 50,
            sensitivity: 4,
            over: this.addSlider,
            timeout: 500,
            out: this.removeSlider
        },
        hoverIntentLayoutBox: function () {
            myGuideBase.settings.layoutBox.hoverIntent(this.hoverIntentSliderConfig);
        },
        addSlider: function () {
            myGuideBase.settings.sliderOptions.show();
        },
        removeSlider: function () {
            myGuideBase.settings.sliderOptions.hide();


        },
        moveToHash: function () {

            if (window.location.hash) {
                setTimeout(function () {
                    if (window.location.hash.split('-').length == 3) {
                        var tab_id = window.location.hash.split('-')[1];
                        var box_id = window.location.hash.split('-')[2];
                        var selected_box = ".pluslet-" + box_id;

                        myGuideBase.settings.tabs('select', tab_id);

                        $('html, body').animate({ scrollTop: jQuery('a[name="box-' + box_id + '"]').offset().top }, 'slow');

                        $(selected_box).effect("pulsate", {
                            times: 1
                        }, 2000);
                    }
                }, 500);
            }
        },
        setupModalWindows: function () {

        },
        setupAutoCompleteBox: function () {

        },
       
        fixFlashFOUC: function () {
            $(".guidewrapper").css("display", "block");
            $("#main-options").css("display", "block");
        },
        autoCompleteSettings: {
            minLength: 3,
            source: "", //myGuideBase.settings.autoCompleteUrl  + myGuideBase.settings.subjectId, 
            focus: function (event, ui) {

                event.preventDefault();
                jQuery(".find-guide-input").val(ui.item.label);
            },
            select: function (event, ui) {
                var tab_id = ui.item.hash.split('-')[1];
                var box_id = ui.item.hash.split('-')[2];
                var selected_box = ".pluslet-" + box_id;

                $('#tabs').tabs('select', tab_id);

                jQuery(selected_box).effect("pulsate", {
                    times: 1
                }, 2000);

                window.location.hash = 'box-' + box_id;
            }


        },
        loadGuideSearch: function () {
            $('.find-guide-input').autocomplete(myGuideBase.autoCompleteSettings);
        },
        expandCollapseCSS: function () {
            //Expand/Collapse Trigger CSS for all Pluslets on a Tab

            $("#expand_tab").click(function () {
                $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
                $('.pluslet_body').toggle();
                $('.pluslet_body').toggleClass('pluslet_body_closed');
        		  });


        },
        getUserFavoriteBoxes: function () {
        	
        	var g = Guide();
        	var staffId = g.getStaffId();
        	
            $(".fav-boxes-list").empty();
            $.ajax({
                url: myGuideBase.settings.favoritesUrl + staffId,
                type: "GET",
                dataType: "json",
                data: { staff_id: staffId },
                success: function (data) {

                    if (!data.length) {
                        $(".fav-boxes-list").append(myGuideBase.strings.noFavoritesText);
                    }

                    $.each(data, function (idx, obj) {
                        $(".fav-boxes-list").append("<li data-pluslet-id='" + obj.id + "'><div class='pure-g'><div class='pure-u-3-5 fav-box-item' title='" + obj.title + "'>" + obj.title + "</div><div class='pure-u-2-5' style='text-align:right;'><button class='clone-button pure-button pure-button-secondary'>Link</button>&nbsp;<button class='copy-button pure-button pure-button-secondary'>Copy</button></div></div></li>");

                    });
                }
            });

        },
        refreshFeeds: function () {
            /////////////////////
            // refreshFeeds
            // --loads the various feeds after the page has loaded
            /////////////////////

            $(".find_feed").each(function (n) {
                var feed = $(this).attr("name").split("|");
                $(this).load("../../subjects/includes/feedme.php", {
                    type: feed[4],
                    feed: feed[0],
                    count: feed[1],
                    show_desc: feed[2],
                    show_feed: feed[3]
                });
            });

        }

    };
    return myGuideBase;
}