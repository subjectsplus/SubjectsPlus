/**
 * Created by cbrownroberts on 8/28/15.
 */


function mark_as_favorite() {

    //identify pluslets marked as favorites and addClass favorite_pluslet
    var $favBoxes = $("input.favorite_pluslet_input:checked")

    $favBoxes.each(function() {
        $(this).parent().parent().parent().parent().find(".titlebar_text").addClass('favorite_pluslet');

    });

}