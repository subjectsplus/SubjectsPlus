/**
* Autocomplete search triggered by the magnifying glass icon on the guide page. 
* 
* 
* guideSearch
* 
*  
**/
/*jslint browser: true*/
/*global $, jQuery, alert*/

function guideSearch() {

    var myGuideSearch = {

        settings: {
            subjectId: $('#guide-parent-wrap').data().subjectId,
            autoCompleteUrl: "../includes/autocomplete_data.php?collection=guide&subject_id="
        },
        strings: {
        },
        bindUiActions: function () {
            $("#find-trigger").click(function () {
                $("#guide_search").toggle("fade", 700);
            });

        },
        init: function () {
            myGuideSearch.loadGuideSearch();
            myGuideSearch.bindUiActions();
        },
        moveToHash: function () {

            if (window.location.hash) {
                console.log(window.location.hash);
                setTimeout(function () {
                    if (window.location.hash.split('-').length == 3) {
                        var tab_id = window.location.hash.split('-')[1];
                        var box_id = window.location.hash.split('-')[2];
                        var selected_box = ".pluslet-" + box_id;

                        myGuideSearch.settings.tabs('select', tab_id);

                        $('html, body').animate({ scrollTop: jQuery('a[name="box-' + box_id + '"]').offset().top }, 'slow');

                        $(selected_box).effect("pulsate", {
                            times: 1
                        }, 2000);
                    }
                }, 500);
            }
        },
        getAutoCompleteSettings: function () {
            var autoCompleteSettings = {
                minLength: 3,
                source: myGuideSearch.settings.autoCompleteUrl + myGuideSearch.settings.subjectId,
                focus: function (event, ui) {

                    event.preventDefault();
                    jQuery(".find-guide-input").val(ui.item.label);
                },
                select: function (event, ui) {
                    var tab_id = ui.item.hash.split('-')[1];
                    var box_id = ui.item.hash.split('-')[2];
                    var selected_box = ".pluslet-" + box_id;

                    $('#tabs').tabs('select', tab_id);

                    $(selected_box).effect("pulsate", {
                        times: 1
                    }, 2000);

                    window.location.hash = 'box-' + box_id;
                }
            }
            return autoCompleteSettings;
        },
        loadGuideSearch: function () {
            $('.find-guide-input').autocomplete(myGuideSearch.getAutoCompleteSettings());
        }

    };

    return myGuideSearch;
}
