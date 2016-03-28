/**
 * Sets up drag & drop features across the guide interface.
 *
 * @constructor Tabs
 * @author little9 (Jamie Little)
 *
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function drag() {
    "use strict";

    var myDrag = {
        init: function () {


            myDrag.makeDropable(".dropspotty");
            myDrag.makeDropable(".cke");
            myDrag.makeSortable(".sort-column");
            myDrag.makeSortable('.sptab','sections');
            myDrag.makeDraggable(".draggable");

            $(".box-item").mousedown(function () {
                myDrag.makeDropable(".dropspotty");
                myDrag.makeDropable(".cke");
                myDrag.makeSortable(".sort-column");
                myDrag.makeSortable('.sptab','sections');
                myDrag.makeDraggable(".draggable");


                $(".box-item").on('drag', function () {
                    //$('#box_options').hide();
                });

                $(".draggable").draggable({
                    helper: 'clone', // Use a cloned helper
                    appendTo: 'body', // Append helper to body so you can hide the parent
                    start: function () {
                        // Make the original transparent to avoid re-flowing of the elements
                        // This makes the illusion of dragging the original item
                        $(this).css({opacity: 0});
                    },
                    stop: function () {
                        // Show original after dragging stops
                        $(this).css({opacity: 1});

                    }
                });


            });

        },
        makeDraggable: function (lstrSelector) {
            ////////////////////////////////
            // SET UP DRAGGABLE
            // --makes anyting with class of "draggable" draggable
            ////////////////////////////////

            var draggable_element = $(lstrSelector);

            draggable_element.draggable({
                ghosting: true,
                opacity: 0.5,
                revert: true,
                fx: 300,
                cursor: 'pointer',
                helper: 'clone',
                zIndex: 350
            });

        },

        makeDropable: function (lstrSelector) {
            // SET UP DROP SPOTS
            // --makes divs with class of "dropspotty" droppables
            // accepts things with class of "draggable"
            //
            ////////////////////////////////

            var dropspot = $(lstrSelector);
            var drop_id;
            var drag_id;
            var drop_tab;
            var pluslet_title;
            var subject_id = $('#guide-parent-wrap').data().subjectId;

            dropspot.droppable({

                iframeFix: true,
                accept: ".draggable, .pluslet",
                drop: function (event, props) {

                    $(this).removeClass("drop_hover");
                    $(this).css("background", "");

                    //do if droppable tab
                    if ($(this).children('a[href^="#tabs-"]').length > 0) {
                        if ($(props.draggable).hasClass('pluslet')) {
                            if (!$(this).hasClass('ui-state-active')) {
                                drop_tab = this;

                                //make sure to show all of pluslet before hiding
                                $(props.draggable).children('.pluslet_body').show();
                                $(props.draggable).children().children('.titlebar_text').show();
                                $(props.draggable).children().children('.titlebar_options').show();


                                $(props.draggable).hide('slow', function () {
                                    $(this).remove();
                                    $(drop_tab).children('a[href^="#tabs-"]').click();
                                    $('.portal-column-1:visible').first().prepend(this);

                                    $(this).height("auto");
                                    $(this).width("auto");
                                    $(this).show("slow");

                                    $("#response").hide();
                                    //Make save button appear, since there has been a change to the page
                                    $("#save_guide").fadeIn();
                                });
                            }
                        }

                        return;
                    }

                    //only do for class draggable
                    if ($(props.draggable).hasClass('draggable')) {

                        drop_id = $(this).attr("id");
                        drag_id = $(props.draggable).attr("id");

                        pluslet_title = $(props.draggable).html();
                        if ($(this).hasClass('cke')) {
                            if ($(props.draggable).attr("ckclass") !== "") {
                                CKEDITOR.instances[$(this).attr('id').replace('cke_', '')].openDialog($(props.draggable).attr("ckclass") + 'Dialog');
                            } else {
                                alert('This pluslet isn\'t configured to drag into CKEditor!');
                            }
                        } else {
                            // if there can only be one, could remove from list items
                            drop_id = $(this).attr("id");
                            drag_id = $(props.draggable).attr("id");

                            pluslet_title = $(props.draggable).html();

                            // Create new node below, using a random number

                            var randomnumber = Math.floor(Math.random() * 1000001);
                            $(this).next('div').prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");


                            // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory
                            $("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
                                    from: drag_id,
                                    to: drop_id,
                                    pluslet_title: pluslet_title,
                                    flag: 'drop',
                                    this_subject_id: subject_id
                                },
                                function () {

                                    // 1.  remove the wrapper
                                    // 2. put the contents of the div into a variable
                                    // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
                                    var cnt = $("#new-" + randomnumber).contents();
                                    $("#new-" + randomnumber).replaceWith(cnt);

                                    $(this).addClass("unsortable");

                                    $("#response").hide();
                                    //Make save button appear, since there has been a change to the page
                                    $("#save_guide").fadeIn();


                                    $("a[class*=showmedium]").colorbox({
                                        iframe: true,
                                        innerWidth: "90%",
                                        innerHeight: "80%",
                                        maxWidth: "1100px",
                                        maxHeight: "800px"
                                    });

                                    var h = help();
                                    h.makeHelpable("img[class*=help-]");

                                    //Close main flyout when a pluslet is dropped
                                    $('#main-options').slideReveal("hide");

                                });
                        }
                    }
                },
                over: function (event, ui) {
                    if ($(this).children('a[href^="#tabs-"]').length > 0 && $(ui.draggable).hasClass('pluslet')
                        && !$(this).hasClass('ui-state-active')) {
                        $(this).css("background", "none repeat scroll 0% 0% #C03957");
                    }

                    if ($(this).children('a[href^="#tabs-"]').length < 1 && !$(ui.draggable).hasClass('pluslet')) {
                        $(this).addClass("drop_hover");
                    }
                },
                out: function (event, ui) {
                    if ($(this).children('a[href^="#tabs-"]').length > 0 && $(ui.draggable).hasClass('pluslet')) {
                        $(this).css("background", "");
                    }

                    if ($(this).children('a[href^="#tabs-"]').length < 1 && !$(ui.draggable).hasClass('pluslet')) {
                        $(this).removeClass("drop_hover");
                    }
                }
            });
        },
        makeSortable: function (lstrSelector, lstrType) {
////////////////////////////
// MAKE COLUMNS SORTABLE
// Make "Save Changes" button appear on sorting
////////////////////////////
            var sortable_element = $(lstrSelector);

            if (lstrType === 'sections') {
                sortable_element.sortable({
                    opacity: 0.7,
                    cancel: '.unsortable',
                    handle: '.section_sort',
                    update: function (event, ui) {
                        $("#response").hide();
                        $("#save_guide").fadeIn();

                    },
                    start: function (event, ui) {
                        $(ui.item).find('.dropspotty').hide();
                        $(ui.item).find('.pluslet').hide();
                        $(ui.item).height('2em');
                        $(ui.item).width('auto');
                    },
                    stop: function (event, ui) {
                        $(ui.item).find('.dropspotty').show();
                        $(ui.item).find('.pluslet').show();
                    }
                });
            } else {
                sortable_element.sortable({

                    connectWith: ['.portal-column-0', '.portal-column-1',
                        '.portal-column-2'],
                    opacity: 0.7,
                    tolerance: 'pointer',
                    cancel: '.unsortable',
                    handle: 'div.pluslet_sort',
                    update: function (event, ui) {
                        $("#response").hide();
                        $("#save_guide").fadeIn();

                    },
                    start: function (event, ui) {
                        $(ui.item).children('.pluslet_body').hide();
                        $(ui.item).children().children('.titlebar_text').show();
                        $(ui.item).children().children('.titlebar_options').hide();
                        $(ui.item).height('2em');
                        $(ui.item).width('90%');
                    },
                    stop: function (event, ui) {

                        if ($('div').hasClass('pluslet_body_closed')) {
                            $(ui.item).children('.pluslet_body').hide();
                        } else {
                            $(ui.item).children('.pluslet_body').show();
                        }
                        $(ui.item).children().children('.titlebar_text').show();
                        $(ui.item).children().children('.titlebar_options').show();

                    }
                });
            }
        }
    }


    return myDrag;
}
