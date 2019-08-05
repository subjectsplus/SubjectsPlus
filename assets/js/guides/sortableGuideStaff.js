/**
 * Object to get details related to the current guide.
 *
 *
 **/
/*jslint browser: true*/

/*global $, jQuery, alert*/
function sortableGuideStaff() {
    "use strict";

    var sortableGuideStaff = {

        settings: {
            staffListItemsSelector: 'div.sortable-staff-list'
        },
        bindUiActions: function () {
            sortableGuideStaff.makeGuidesSortable();
        },
        makeGuidesSortable : function () {
            $(sortableGuideStaff.settings.staffListItemsSelector).sortable({
                axis: 'y'
            });
        },
        init: function () {
            $( document ).ready(function() {
                sortableGuideStaff.bindUiActions();
            });
        }
    };

    return sortableGuideStaff;
}

var sortableGuideStaff = new sortableGuideStaff();
sortableGuideStaff.init();