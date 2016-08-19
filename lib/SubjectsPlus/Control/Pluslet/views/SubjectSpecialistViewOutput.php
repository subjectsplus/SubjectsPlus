<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 5/2/16
 * Time: 2:50 PM
 */
?>

<div>
    <?php echo $this->_body_content; ?>
</div>

<script>

    if( $('[name="Clone"]') ) {

        var clone = $('[name="Clone"]');
        var subjectSpecialists = $(clone).children().find('[name="SubjectSpecialist"]');
        $(subjectSpecialists).children('.box_settings, .titlebar').remove();
    }

/*
    var subjectSpecialistPlusletId = $('div[name="SubjectSpecialist"]').attr('id');
    var content = $('#' + subjectSpecialistPlusletId).html();

    var profiles = $(content).find('.subjectSpecialistPluslet');



    $(profiles).each(function() {

        $('.subjectSpecialistPluslet:hidden').remove();

    });

    var newContent = '';
    newContent += $(profiles).html();
    newContent += $('.pluslet_body_content').html();



    var pluslet_body = $('#' + subjectSpecialistPlusletId).find('.pluslet_body');
    $(pluslet_body).html(newContent);
*/

</script>