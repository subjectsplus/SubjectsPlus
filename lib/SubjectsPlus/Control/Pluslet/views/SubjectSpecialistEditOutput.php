<?php
    if($this->_extra['showPhoto'][0] == "on") {
        $inputShowPhoto = "<input type='checkbox' name='SubjectSpecialist-extra-showPhoto' checked/>";
    } else {
        $inputShowPhoto = "<input type='checkbox' name='SubjectSpecialist-extra-showPhoto'/>";
    }

    if($this->_extra['showTitle'][0] == "on") {
        $inputShowTitle = "<input type='checkbox' name='SubjectSpecialist-extra-showTitle' checked/>";
    } else {
        $inputShowTitle = "<input type='checkbox' name='SubjectSpecialist-extra-showTitle'/>";
    }

    if($this->_extra['showPhone'][0] == "on") {
        $inputShowPhone = "<input type='checkbox' name='SubjectSpecialist-extra-showPhone' checked/>";
    } else {
        $inputShowPhone = "<input type='checkbox' name='SubjectSpecialist-extra-showPhone'/>";
    }

    if($this->_extra['showEmail'][0] == "on") {
        $inputShowEmail = "<input type='checkbox' name='SubjectSpecialist-extra-showEmail' checked/>";
    } else {
        $inputShowEmail = "<input type='checkbox' name='SubjectSpecialist-extra-showEmail'/>";
    }

?>


<form class="pure-form pure-form-stacked">

    <?php echo $inputShowPhoto; ?> <label style="display:inline;"> Show Photo </label>

    <br>
    <?php echo $inputShowTitle; ?> <label style="display:inline;"> Show Title</label>

    <br>
    <?php echo $inputShowPhone; ?> <label style="display:inline;"> Show Phone</label>

    <br>
    <?php echo $inputShowEmail; ?> <label style="display:inline;"> Show Email</label>
    <br>

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