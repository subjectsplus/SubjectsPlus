/**
 * Sets up the click events for saving a guide and functions for actually saving the guide to the database.
 *
 *
 */

function saveSetup() {
    "use strict";

    var mySaveSetup = {

        settings: {
            fetchTabIdsUrl: "helpers/fetch_tab_ids.php?",
            fetchSectionIdsUrl: "helpers/fetch_section_ids_by_subject_id.php?"


        },
        strings: {},
        bindUiActions: function () {

            ////////////////////
            // on select change show save guide
            ///////////////////
            $(document).on('change', 'select[id^=titlebar-styling]', function (event) {
                var pluslet_id = $(this).parent().parent().parent().parent().attr('id');

                if ($('#' + pluslet_id).attr('name').indexOf('modified-pluslet-') === -1) {
                    $('#' + pluslet_id).attr('name', 'modified-pluslet-' + $('#' + pluslet_id).attr('name'));
                }

                $("#response").hide();
                $("#save_guide").fadeIn();
            });
        },
        init: function () {
            mySaveSetup.setupSaveButton('#save_guide');
        },

        autoSave: function() {
            mySaveSetup.saveGuide();
            console.log('autosave Called');
        },

        setupSaveButton: function (lstrSelector) {
            // //////////////////////////
            // SAVE GUIDE'S LAYOUT
            // -- this saves everything on page
            // //////////////////////////

            $(document.body)
                .on(
                    'click',
                    lstrSelector,
                    function (event) {
                        mySaveSetup.saveGuide();
                        return false;
                    });
        },
        checkRequired: function () {
            // If a required field is empty, set req_field to 1, and change the
            // bg colour of the offending field
            var req_field = 0;

            $("*[class=required_field]").each(function () {
                var check_this_field = $(this).val();

                if (check_this_field === '' || check_this_field === null) {
                    $(this).attr("style", "background-color:#FFDFDF");
                    req_field = 1;
                } else {
                    $(this).attr("style", "background-color:none");
                }

            });

            return req_field;

        },
        preparePluslets: function (lstrType, lintID, lobjThis, staffId, subjectId) {
            var lboolSettingsOnly = false;
            var lstrInstance;
            var lstrTitle;
            var lstrDiv;
            var lintUID;
            var pbody;
            var pitem_type;
            var pspecial;
            var ourflag;
            var isclone;
            var staff_id = staffId;
            var subject_id = subjectId;

            //based on type set variables
            ///////////////////////
            // preparePluslets FUNCTION
            // called to prepare all the pluslets before saving them
            ///////////////////////

            switch (lstrType.toLowerCase()) {
                case "modified": {
                    //used to get contents of CKeditor box
                    lstrInstance = "pluslet-update-body-" + lintID;
                    //Title of item
                    if ($("#pluslet-update-title-" + lintID).val() == null) {
                        var b = $(".pluslet-" + lintID).find('.titlebar_text').clone();
                        b.children().remove();
                        lstrTitle = b.text().trim();
                    } else {
                        lstrTitle = mySaveSetup.addslashes($("#pluslet-update-title-" + lintID).val());
                    }


                    //console.log(lintID);
                    //console.log(lstrTitle);
                    //console.log("Title modified!");
                    if (lstrTitle === undefined) {
                        b = $(".pluslet-" + lintID).find('.titlebar_text').clone();
                        b.children().remove();
                        lstrTitle = b.text().trim();
                        lboolSettingsOnly = true;
                    }

                    //Div Selector
                    lstrDiv = "#pluslet-" + lintID;
                    //depending update_id
                    lintUID = lintID;
                    break;
                }
                case "new": {
                    //used to get contents of CKeditor box
                    lstrInstance = "pluslet-new-body-" + lintID;
                    //Title of item
                    lstrTitle = mySaveSetup.addslashes($("#pluslet-new-title-" + lintID).val());
                    //Div Selector
                    lstrDiv = "#" + lintID;
                    //depending update_id
                    lintUID = '';
                    break;
                }
            }

            ///////////////////////////////////////////////////////////////
            // The box settings  are available on all pluslets potentially
            // --they determine if titlebar shows, titlebar styling, if body
            // is collapsed by default, and if body is suppressed (for a header pluslet)
            ////////////////////////////////////////////////////////////////

            var boxsetting_hide_titlebar = Number($('input[id=notitle-' + lintID + ']')
                .is(':checked'));
            var boxsetting_collapse_titlebar = Number($(
                'input[id=start-collapsed-' + lintID + ']').is(':checked'));
            var boxsetting_titlebar_styling = $(
                'select[id=titlebar-styling-' + lintID + ']').val();

            var favorite_box = Number($('input[id=favorite_box-' + lintID + ']')
                .is(':checked'));

            var boxsetting_target_blank_links = Number($('input[id=target_blank_links-' + lintID + ']')
                .is(':checked'));

            //////////////////////////////////////////////////////////////////
            // Check the pluslet's "name" value to see if there is a number
            // --If it is numeric, it's a "normal" item with a ckeditor instance
            // collecting the "body" information
            //////////////////////////////////////////////////////////////

            var item_type = $(lobjThis).attr("name").split("-");

            // Loop through the box types
            switch (item_type[2]) {
                case "Basic":
                    if (typeof CKEDITOR !== 'undefined' && !lboolSettingsOnly) {

                        pbody = mySaveSetup.addslashes(CKEDITOR.instances[lstrInstance].getData());

                    } else {

                        pbody = $('#pluslet-' + lintID).find('.pluslet_body').html();
                    }

                    pitem_type = "Basic";
                    pspecial = '';
                    break;
                case "Heading":
                    pbody = ""; // headings have no body
                    pitem_type = "Heading";

                    break;
                case "TOC":
                    pbody = "";
                    pitem_type = "TOC";
                    var tickedBoxes = [];
                    $('input[name=checkbox-' + lintID + ']:checked').each(function () {

                        tickedBoxes.push(this.value);

                    });

                    pspecial = '{"ticked":"' + tickedBoxes + '"}';

                    break;
                case "Feed":
                    pbody = $('input[name=' + lstrInstance + ']').val();
                    var pfeed_type = $('select[name=feed_type-' + lintID + ']').val();
                    var pnum_items = $('input[name=displaynum-' + lintID + ']').val();
                    var pshow_desc = $('input[name=showdesc-' + lintID + ']:checked').val();
                    var pshow_feed = $('input[name=showfeed-' + lintID + ']:checked').val();

                    pspecial = '{"num_items":' + pnum_items + ',  "show_desc":'
                        + pshow_desc + ', "show_feed": ' + pshow_feed
                        + ', "feed_type": "' + pfeed_type + '"}';

                    pitem_type = "Feed";
                    break;


                case "SubjectSpecialist":

                    pbody = CKEDITOR.instances['editor-specialist'].getData();

                    pitem_type = item_type[2];
                    var extra = {};

                    //parse checkboxe inputs to create extra fields
                    $(lobjThis)
                        .find('input[name^=' + item_type[2] + '-extra][type=checkbox]')
                        .each(
                            function () {
                                var name_split = $(this).attr("name").split("-");
                                extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? []
                                    : extra[name_split[2]];

                                if ($(this).is(':checked'))
                                    extra[name_split[2]].push($(this).val());
                            });

                    pspecial = $.isEmptyObject(extra) ? "" : JSON.stringify(extra);

                    break;

                case "Card":

                    pbody = CKEDITOR.instances.cardEditor.getData();

                    pitem_type = item_type[2];
                    var extra = {};

                    //parse text inputs to create extra fields
                    $(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=url]')
                        .each(function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse text inputs to create extra fields
                    $(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=text]')
                        .each(function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse textareas to create extra fields
                    $(lobjThis).find('textarea[name^=' + item_type[2] + '-extra]').each(
                        function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse selectboxes to create extra fields
                    $(lobjThis).find('select[name^=' + item_type[2] + '-extra]').each(
                        function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse radio inputs to create extra fields
                    $(lobjThis)
                        .find('input[name^=' + item_type[2] + '-extra][type=radio]')
                        .each(
                            function () {
                                var name_split = $(this).attr("name").split("-");
                                extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? ''
                                    : extra[name_split[2]];

                                if ($(this).is(':checked'))
                                    extra[name_split[2]] = $(this).val();
                            });

                    //parse checkboxe inputs to create extra fields
                    $(lobjThis)
                        .find('input[name^=' + item_type[2] + '-extra][type=checkbox]')
                        .each(
                            function () {
                                var name_split = $(this).attr("name").split("-");
                                extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? []
                                    : extra[name_split[2]];

                                if ($(this).is(':checked'))
                                    extra[name_split[2]].push($(this).val());
                            });

                    pspecial = $.isEmptyObject(extra) ? "" : JSON.stringify(extra);

                    break;
                default:

                    /**
                     *
                     * This needs to be fixed. Dupe IDs are bad.
                     *
                     *
                     */

                    pbody = $('#' + item_type[2] + '-body').html();
                    pbody = pbody === undefined ? "" : pbody;
                    pitem_type = item_type[2];
                    var extra = {};

                    //parse text inputs to create extra fields
                    $(lobjThis).find('input[name^=' + item_type[2] + '-extra][type=text]')
                        .each(function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse textareas to create extra fields
                    $(lobjThis).find('textarea[name^=' + item_type[2] + '-extra]').each(
                        function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse selectboxes to create extra fields
                    $(lobjThis).find('select[name^=' + item_type[2] + '-extra]').each(
                        function () {
                            var name_split = $(this).attr("name").split("-");
                            extra[name_split[2]] = $(this).val();
                        });

                    //parse radio inputs to create extra fields
                    $(lobjThis)
                        .find('input[name^=' + item_type[2] + '-extra][type=radio]')
                        .each(
                            function () {
                                var name_split = $(this).attr("name").split("-");
                                extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? ''
                                    : extra[name_split[2]];

                                if ($(this).is(':checked'))
                                    extra[name_split[2]] = $(this).val();
                            });

                    //parse checkboxe inputs to create extra fields
                    $(lobjThis)
                        .find('input[name^=' + item_type[2] + '-extra][type=checkbox]')
                        .each(
                            function () {
                                var name_split = $(this).attr("name").split("-");
                                extra[name_split[2]] = typeof extra[name_split[2]] === 'undefined' ? []
                                    : extra[name_split[2]];

                                if ($(this).is(':checked'))
                                    extra[name_split[2]].push($(this).val());
                            });

                    pspecial = $.isEmptyObject(extra) ? "" : JSON.stringify(extra);

                    break;
            }

            //only check clone if modified pluslet
            if (lstrType === 'modified') {
                //////////////////////
                // Clone?
                // If it's a clone, add a new entry to DB
                /////////////////////

                //console.log(lintID);
                var clone = $("#pluslet-" + lintID).attr("class");

                //console.log(clone);
                if (clone.indexOf("clone") !== -1) {
                    ourflag = 'insert';
                    isclone = 1;

                } else {
                    ourflag = 'update';
                    isclone = 0;
                }

                //only settings update
                if (lboolSettingsOnly) {
                    ourflag = 'settings';
                }
            } else {
                ourflag = 'insert';
                isclone = 0;
            }

            ////////////////////////
            // Post the data to guide_data.php
            // which will do an insert or update as appropriate
            //
            // **changed by dgonzalez 08/2013 so that request is not done
            // asynchronously so that setTimeout to save guide is no longer needed.
            ////////////////////////

            $.ajax({
                url: "helpers/guide_data.php",
                data: {
                    update_id: lintUID,
                    pluslet_title: lstrTitle,
                    pluslet_body: pbody,
                    flag: ourflag,
                    staff_id: staff_id,
                    item_type: pitem_type,
                    clone: isclone,
                    special: pspecial,
                    this_subject_id: guide().getSubjectId(),
                    boxsetting_hide_titlebar: boxsetting_hide_titlebar,
                    boxsetting_collapse_titlebar: boxsetting_collapse_titlebar,
                    boxsetting_titlebar_styling: boxsetting_titlebar_styling,
                    favorite_box: favorite_box,
                    boxsetting_target_blank_links: boxsetting_target_blank_links

                },
                type: "POST",
                success: function (response) {
                    var this_div;

                    //load response into pluslet
                    $(lstrDiv).html(response);

                    // check if it's an insert or an update, and name div accordingly
                    if (ourflag === "update" || ourflag === "settings"
                        || isclone === 1) {
                        this_div = '#pluslet-' + lintID;
                    } else {
                        this_div = '#' + lintID;
                    }

                    // 1.  remove the wrapper
                    // 2. put the contents of the div into a variable
                    // 3.  replace parent div (i.e., id="xxxxxx") with the content made by loaded file
                    var cnt = $(this_div).contents();

                    $(this_div).replaceWith(cnt);
                },
                async: false
            });
        },
        addslashes: function (string) {
            //////////////////
            // addslashes called inside guide save, above
            // ///////////////
            return (string + '').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
        },
        saveGuide: function () {

            $("#autosave-spinner").show();
            //console.log('saveGuide start');

            var staff_id = $('#guide-parent-wrap').data.staffId;
            var subject_id = $('#guide-parent-wrap').data.SubjectId;

            // make sure our required fields have values
            // before continuing
            var test_req = mySaveSetup.checkRequired();

            if (test_req === 1) {
                alert("You must complete all required form fields.");
                return false;
            };

            // 1. Look for new- or modified-pluslet
            // 2. Check to make sure data is okay
            // 3. Save to DB
            // 4. Recreate pluslet with ID
            // 5. Save layout

            // //////////////////
            // modified-pluslet
            // loop through each pluslet
            // /////////////////

            $('div[name*=modified-pluslet]').each(
                function () {

                    var update_id = $(this).attr("id")
                        .split("-");
                    var this_id = update_id[1];

                    // prepare the pluslets for saving
                    mySaveSetup.preparePluslets("modified",
                        this_id, this, staff_id,
                        subject_id);
                });

            // //////////////////////
            // Now the new pluslets
            // //////////////////////

            $('div[name*=new-pluslet]')
                .each(
                    function () {

                        var insert_id = $(this)
                            .attr("id"); // just
                        // a
                        // random
                        // gen
                        // number

                        // prepare pluslets for
                        // saving
                        mySaveSetup.preparePluslets("new",
                            insert_id, this,
                            staff_id,
                            subject_id);
                    });

            // ////////////////////
            // We're good, save the guide layout
            // insert a pause so the new pluslet is found
            // ////////////////////
            $("#response").hide();
            $("#save_guide").fadeOut();


            ///////////////////////
            // saveGuide FUNCTION
            // called at end of previous section
            //////////////////////
            var lobjTabs = [];
            var lstrTabs;

            $('a[href^="#tab"]').each(function () {
                var lstrName = $(this).text();
                var lstrExternal = $(this).parent('li').attr('data-external-link');
                var lintVisibility = parseInt($(this).parent('li').attr('data-visibility'));
                var tab_id = $(this).attr("href").split("tabs-")[1];
                //console.log("Tab ids:" + tab_id);
                var lobjTab = {};
                lobjTab.name = lstrName;
                lobjTab.external = lstrExternal;
                lobjTab.visibility = lintVisibility;
                lobjTab.sections = [];

                $('div#tabs-' + tab_id + ' div[id^="section_"]').each(function () {
                    //console.log("Selector:" + 'div#tabs-' + tab_id + ' div[id^="section_"]');
                    var section_id = $(this).attr("id").split("section_")[1];
                    //console.log("Section ID:" + section_id);
                    var lobjSection = {};
                    lobjSection.center_data = "";
                    lobjSection.left_data = "";
                    lobjSection.sidebar_data = "";

                    lobjSection.layout = $(this).data('layout');

                    $('div#section_' + section_id + ' div.portal-column-0').sortable();
                    $('div#section_' + section_id + ' div.portal-column-1').sortable();
                    $('div#section_' + section_id + ' div.portal-column-2').sortable();

                    lobjSection.left_data = $('div#section_' + section_id + ' div.portal-column-0').sortable('serialize');


                    lobjSection.center_data = $('div#section_' + section_id + ' div.portal-column-1').sortable('serialize');


                    lobjSection.sidebar_data = $('div#section_' + section_id + ' div.portal-column-2').sortable('serialize');

                    lobjTab.sections.push(lobjSection);
                });

                lobjTabs.push(lobjTab);
            });

            lstrTabs = JSON.stringify(lobjTabs);
            $("#response").load("helpers/save_guide.php", {
                    this_subject_id: $('#guide-parent-wrap').data().subjectId,
                    user_name: $('#guide-parent-wrap').data().staffId,
                    tabs: lstrTabs
                }, function (response, status) {

                    if (status == "success"){
                        // update tab and section ids with data from db
                        var myGuideData = guideData();
                        myGuideData.bindNewIds();
                    }
                });

            var containers = $(".booklist-content");
            $.each(containers, function () {
                var container = this;
                if ($(container).parent().parent().attr('name') == 'Clone') {
                    container = $("#" + $(container).parent().parent().attr('id')).find('.booklist-content')[0];
                }

                if ($(container).attr('rendered') == '0') {
                    var b = bookList();
                    b.init(container);
                    $(container).attr('rendered', '1');
                    setTimer();
                }

                function setTimer() {
                    setTimeout(showContainer, 2000);
                }

                function showContainer() {
                    var loader = $(container).prev();
                    $(loader).hide();
                    $(container).show("fade");
                }
            });

            // debugger;

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




    return mySaveSetup;
}