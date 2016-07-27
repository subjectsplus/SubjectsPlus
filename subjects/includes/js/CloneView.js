function cloneView() {

    var myCloneView = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            myCloneView.removeDupMarkup();
        },
        init: function () {
            myCloneView.bindUiActions();
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

    return myCloneView;
}