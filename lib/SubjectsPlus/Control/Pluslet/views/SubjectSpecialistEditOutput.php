<?php
    if($this->_extra['showPhoto'][0] == "on") {
        $inputShowPhoto = "<input type='checkbox' name='SubjectSpecialist-extra-showPhoto' checked/>";
    } else {
        $inputShowPhoto = "<input type='checkbox' name='SubjectSpecialist-extra-showPhoto'/>";
    }

?>


<form class="pure-form pure-form-stacked">
    <label>Show Photo</label>
    <?php echo $inputShowPhoto; ?>

    <label>Show Title</label>
    <input type="checkbox"
           name="SubjectSpecialist-extra-showTitle"
           value="1"/>

    <label>Show Phone</label>
    <input type="checkbox"
           name="SubjectSpecialist-extra-showPhone"
           value="1"/>

    <label>Facebook</label>
    <input required aria-required="true"
           type="text"
           id="facebook"
           name="SubjectSpecialist-extra-facebook"
           placeholder="username only"
           value="<?php echo $this->_extra['facebook']; ?>"/>
    <label>Twitter</label>
    <input required aria-required="true"
           type="text"
           id="twitter"
           name="SubjectSpecialist-extra-twitter"
           placeholder="username only - no @"
           value="<?php echo $this->_extra['twitter']; ?>"/>
    <label>Pinterest</label>
    <input required aria-required="true"
           type='text'
           id='pinterest'
           name="SubjectSpecialist-extra-pinterest"
           placeholder="username only"
           value="<?php echo $this->_extra['pinterest']; ?>"/>
</form>