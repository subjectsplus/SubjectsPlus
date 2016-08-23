function subjectSpecialist() {

    var mySubjectSpecialist = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            mySubjectSpecialist.clickCheckboxes();
            mySubjectSpecialist.isNumberKey();
        },
        init: function () {
            mySubjectSpecialist.bindUiActions();
            mySubjectSpecialist.setCheckboxes();
            mySubjectSpecialist.removeEditor();
            mySubjectSpecialist.removeItems();
            mySubjectSpecialist.hideTextarea();
            mySubjectSpecialist.moveBodyContentToBottom();
        },

        hideTextarea : function () {
            $("textarea[name=editor1]").hide();
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

        setCheckboxes : function () {
            $(".checkbox_ss").each(function() {
                if( $(this, "input").val() == "Yes") {
                    $(this, "input").prop("checked", true);
                }
            });
        },

        clickCheckboxes : function () {
            $('.checkbox_ss').on('click', function() {

                if( ($(this).attr('value') == "No") || $(this).attr('value') == "" ) {
                    $(this).attr('value', 'Yes');
                    $(this, "input").prop("checked", true);
                } else {
                    $(this).attr('value', 'No');
                    $(this, "input").prop("checked", false);
                }
            });
        },

        moveBodyContentToBottom : function () {
            var content = $('.pluslet_body').find('#subject-specialist-content');

            var plusletId = $(content).parents('id');
            console.log(plusletId);

        },

        isNumberKey: function () {
            $( "#target" ).keypress(function() {
                console.log( "Handler for .keypress() called." );
            });
        }
    };

    return mySubjectSpecialist;
}