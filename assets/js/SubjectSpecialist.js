function subjectSpecialist() {


    $('div[data-show-photo="No"]').remove();
    $('.staff-content[data-show-title="No"]').remove();
    $('.staff-content[data-show-email="No"]').remove();
    $('.staff-content[data-show-phone="No"]').remove();
    $('.staff-social[data-show-facebook="No"]').remove();
    $('.staff-social[data-show-twitter="No"]').remove();
    $('.staff-social[data-show-pinterest="No"]').remove();
    $('.staff-social[data-show-instagram="No"]').remove();



    function setCheckboxes() {
        $(".checkbox_ss").each(function() {

            if( $(this, "input").val() == "Yes") {

                $(this, "input").prop("checked", true);
            }
        });


        $(".checkbox_ss").on('click', function() {
            //var value = $(this).attr('value');

            if( ($(this).attr('value') == "No") || $(this).attr('value') == "" ) {
                $(this).attr('value', 'Yes');
                $(this, "input").prop("checked", true);
            } else {
                $(this).attr('value', 'No');
                $(this, "input").prop("checked", false);
            }
        });
    }
}

subjectSpecialist();