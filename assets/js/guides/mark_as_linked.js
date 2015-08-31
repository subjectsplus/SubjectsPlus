/**
 * Created by cbrownroberts on 8/28/15.
 */

//identify pluslets marked as linked aka cloned and addClass linked_pluslet
function mark_as_linked() {
    var $linkedBoxes = $("div.pluslet[name='Clone']");
    $linkedBoxes.each(function() {
        $(this).children(".titlebar").children(".titlebar_text").addClass('linked_pluslet');

    });
}