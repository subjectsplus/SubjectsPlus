<form class="pure-form pure-form-stacked">
    <div class='social_media'>
        <label>Facebook</label>
        <input required aria-required="true"
               type="text"
               id="facebook"
               name="SocialMedia-extra-facebook"
               placeholder="username only"
               value="<?php echo $this->_extra['facebook']; ?>"/>
        <label>Twitter</label>
        <input required aria-required="true"
               type="text"
               id="twitter"
               name="SocialMedia-extra-twitter"
               placeholder="username only - no @"
               value="<?php echo $this->_extra['twitter']; ?>"/>
        <label>Pinterest</label>
        <input required aria-required="true"
               type='text'
               id='pinterest'
               name="SocialMedia-extra-pinterest"
               placeholder="username only"
               value="<?php echo $this->_extra['pinterest']; ?>"/>
        <label>Instagram</label>
        <input required aria-required="true"
               type='text'
               id='instagram'
               name="SocialMedia-extra-instagram"
               placeholder="username only"
               value="<?php echo $this->_extra['instagram']; ?>"/>
    </div>
</form>