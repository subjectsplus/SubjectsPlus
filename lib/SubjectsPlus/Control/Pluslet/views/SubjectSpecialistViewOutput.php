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

</script>