var subjectSpecialistView = {

    bindUiActions: function () {

    },
    init: function () {

        subjectSpecialistView.bindUiActions();
        subjectSpecialistView.removeEditor();
        subjectSpecialistView.removeItems();

    },

    removeItems : function () {
        if ($('div[data-show-photo="No"]')) {
               $('div[data-show-photo="No"]').siblings('.specialist-info').removeClass('show-photo-full');
               $('div[data-show-photo="No"]').remove();
        }
        $('li[data-show-title="No"]').remove();
        $('li[data-show-phone="No"]').remove();
        $('li[data-show-email="No"]').remove();
        $('.staff-social[data-show-facebook="No"]').remove();
        $('.staff-social[data-show-twitter="No"]').remove();
        $('.staff-social[data-show-pinterest="No"]').remove();
        $('.staff-social[data-show-instagram="No"]').remove();
    },

    removeEditor : function () {
        if ( $('h4[data-show-name="No"]'))  {
            $('h4[data-show-name="No"]').parents('.subject-specialists').remove();
        }
    },


};