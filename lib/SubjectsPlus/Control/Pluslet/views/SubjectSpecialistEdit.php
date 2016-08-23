<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/19/16
 * Time: 3:11 PM
 */

?>

<div class="subject-specialist-form-container">

    <h4><?php echo $this->fullname; ?></h4>
    <input type="text" name="SubjectSpecialist-extra-staffId<?php echo $this->staff_id; ?>" value="<?php echo $this->staff_id; ?>" style="display:none;">
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showName<?php echo $this->staff_id; ?>" value="<?php echo $this->showName; ?>">
    <label style="display:inline;"> Show Name</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showPhoto<?php echo $this->staff_id; ?>" value="<?php echo $this->showPhoto; ?>"/>
    <label style="display:inline;"> Show Photo</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showTitle<?php echo $this->staff_id; ?>" value="<?php echo $this->showTitle; ?>"/>
    <label style="display:inline;"> Show Title</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showEmail<?php echo $this->staff_id; ?>" value="<?php echo $this->showEmail; ?>" />
    <label style="display:inline;"> Show Email</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showPhone<?php echo $this->staff_id; ?>" value="<?php echo $this->showPhone; ?>" />
    <label style="display:inline;"> Show Phone</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showFacebook<?php echo $this->staff_id; ?>" value="<?php echo $this->showFacebook; ?>" />
    <label style="display:inline;"> Show Facebook</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showTwitter<?php echo $this->staff_id; ?>" value="<?php echo $this->showTwitter; ?>" />
    <label style="display:inline;"> Show Twitter</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showPinterest<?php echo $this->staff_id; ?>" value="<?php echo $this->showPinterest; ?>" />
    <label style="display:inline;"> Show Pinterest</label>
    <br>

    <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showInstagram<?php echo $this->staff_id; ?>" value="<?php echo $this->showInstagram; ?>" />
    <label style="display:inline;"> Show Instagram</label>

</div>




<script>



    $(document).ready(function(){
        $("textarea[name=editor1]").hide();


        /*
         CKEDITOR.replace( 'editor1', {
         height: 250
         } );

         */
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

    });


</script>