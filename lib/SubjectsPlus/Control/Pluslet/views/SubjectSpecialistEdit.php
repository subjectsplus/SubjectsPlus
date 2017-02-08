<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/19/16
 * Time: 3:11 PM
 */

?>
<div id="subject-specialist-form-container">

    <?php
        $settings = array();
        foreach($this->_editors as $staff):
            $settings = $this->getSubjectSpeicalistSettings($staff, $this->_array_keys, $this->_data_array);
            $this->setSubjectSpecialist($settings);
    ?>
    <div class="subject-specialist-form">

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


        <?php if($this->facebook == "") { ?>
            <input type="checkbox" name="SubjectSpecialist-extra-showFacebook<?php echo $this->staff_id; ?>" value="No" style="display:none;" />
        <?php } else { ?>
            <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showFacebook<?php echo $this->staff_id; ?>" value="<?php echo $this->showFacebook; ?>" />
            <label style="display:inline;"> Show Facebook</label>
            <br>
        <?php } ?>

        <?php if($this->twitter == "") { ?>
            <input type="checkbox" name="SubjectSpecialist-extra-showTwitter<?php echo $this->staff_id; ?>" value="No" style="display:none;" />
        <?php } else { ?>
            <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showTwitter<?php echo $this->staff_id; ?>" value="<?php echo $this->showTwitter; ?>" />
            <label style="display:inline;"> Show Twitter</label>
            <br>
        <?php } ?>

        <?php if($this->pinterest == "") { ?>
            <input type="checkbox" name="SubjectSpecialist-extra-showPinterest<?php echo $this->staff_id; ?>" value="No" style="display:none;" />
        <?php } else { ?>
            <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showPinterest<?php echo $this->staff_id; ?>" value="<?php echo $this->showPinterest; ?>" />
            <label style="display:inline;"> Show Pinterest</label>
            <br>
        <?php } ?>

        <?php if($this->instagram == "") { ?>
            <input type="checkbox" name="SubjectSpecialist-extra-showInstagram<?php echo $this->staff_id; ?>" value="No" style="display:none;" />
        <?php } else { ?>
            <input class="checkbox_ss" type="checkbox" name="SubjectSpecialist-extra-showInstagram<?php echo $this->staff_id; ?>" value="<?php echo $this->showInstagram; ?>" />
            <label style="display:inline;"> Show Instagram</label>
        <?php } ?>

    </div>
    <?php endforeach; ?>


    <div id="editor-specialist-container"></div>

</div>


    <script>
        var ss = subjectSpecialist();
        ss.init();

        var editor = CKEDITOR.instances['editor-specialist'];

        $('#editor-specialist-container').append(editor.updateElement());

        $('body').on('click', '#save_guide', function(){

            window.location.reload();

        });

    </script>