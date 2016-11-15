var cloneView = {

    settings: {},
    strings: {},
    bindUiActions: function () {
        cloneView.removeDupMarkup();
    },
    init: function () {
        cloneView.bindUiActions();
    },

    removeDupMarkup : function () {
        var clones = $('div[name="Clone"]');

        $.each(clones, function (index, value) {

            var clone = $(this).find('.pluslet_body');

            $(clone).find('.box_settings').remove();
            $(clone).find('.titlebar').remove();
        });
    }
};
