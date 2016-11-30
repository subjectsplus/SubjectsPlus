function subjectSpecialist() {

    var mySubjectSpecialist = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            mySubjectSpecialist.clickCheckboxes();
            //mySubjectSpecialist.cleanupMarkupOnSave();

        },
        init: function () {
            mySubjectSpecialist.bindUiActions();
            mySubjectSpecialist.setCheckboxes();
            mySubjectSpecialist.removeEditor();
            mySubjectSpecialist.removeItems();
            mySubjectSpecialist.hideTextarea();

        },

        hideTextarea : function () {
            $("textarea[name=editor-specialist]").hide();
        },

        removeItems : function () {

            var pluslet_id = $('.subject-specialist-content').attr('data-pluslet-id');
            //console.log(pluslet_id);

            if( $('div[data-show-photo="No"]') ) {
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

            //remove .subject-specialist-description div if empty so that style applies to last child
            var descDiv = $('.subject-specialist-content').attr('data-pluslet-id', pluslet_id).find('.subject-specialist-description');
            $.each(descDiv, function() {

                if( !$.trim( $(this).html() )) {
                    //console.log( $(this).html() );
                    $(this).remove();
                }
            });

        },

        removeEditor : function () {
            if ( $('h4[data-show-name="No"]'))  {
                $('h4[data-show-name="No"]').parents('.subject-specialists').remove();
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
        }


};

    return mySubjectSpecialist;
}