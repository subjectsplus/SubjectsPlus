/**
 *
 * Handles the behaviour of hashes
 *
 * @author little9 (Jamie Little)
 *
 */
$(document).ready(function() {
    if (window.location.hash.indexOf('tabs') > -1) {
        $("input[aria-controls='" + window.location.hash + "']").trigger('click');
    }

    $('.ui-tabs-nav li').on('click', function () {
        window.location.hash = $(this).attr('aria-controls');
    });
});
