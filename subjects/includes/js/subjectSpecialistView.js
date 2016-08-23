var subjectSpecialistView = {

    bindUiActions: function () {

    },
    init: function () {

        subjectSpecialistView.bindUiActions();
        subjectSpecialistView.removeEditor();
        subjectSpecialistView.removeItems();
        subjectSpecialistView.moveBodyContentToBottom();
    },

    removeItems : function () {
        $('div[data-show-photo="No"]').remove();
        $('.staff-content[data-show-title="No"]').remove();
        $('.staff-content[data-show-email="No"]').remove();
        $('.staff-content[data-show-phone="No"]').remove();
        $('.staff-social[data-show-facebook="No"]').remove();
        $('.staff-social[data-show-twitter="No"]').remove();
        $('.staff-social[data-show-pinterest="No"]').remove();
        $('.staff-social[data-show-instagram="No"]').remove();
    },

    removeEditor : function () {
        if ( $('h4[data-show-name="No"]'))  {
            $('h4[data-show-name="No"]').parent('.subject-specialists').remove();
        }
    },

    moveBodyContentToBottom : function () {
        var content = ($('#subject-specialist-content'));
        $('div[name="SubjectSpecialist"]').children('.pluslet_body').prepend(content);
    }
};