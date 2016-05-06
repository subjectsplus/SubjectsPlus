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


<div id="hidden-pluslet-id" data-hidden-pluslet-id="<?php echo $this->_pluslet_id; ?>" style="display:none;"></div>

<script>

    var hiddenPlusletId = <?php echo $this->_pluslet_id; ?>;


    if( $('#pluslet-' + hiddenPlusletId).closest('[name="Clone"]').length > 0) {

        $('#pluslet-' + hiddenPlusletId).children('.box_settings').remove();
        $('#pluslet-' + hiddenPlusletId).children('.titlebar').remove();
    }


</script>