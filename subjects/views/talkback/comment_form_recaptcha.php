<div class="tip">
    <h2>Need help <strong>now</strong>? <br/><a href="http://calder.med.miami.edu/librarianask.html">Ask
            a Librarian</a>.</h2>

	<?php

	$siteKey = $talkback_recaptcha_site_key;

	if ( $siteKey === '' ):
		?>
        <h2>Add your keys</h2>
        <p>If you do not have keys already then visit <kbd> <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a></kbd>
            to generate them. Edit this file and set the respective keys in <kbd>$siteKey</kbd> and <kbd>$secret</kbd>.
            Reload the page after this.</p>
	<?php
	else:
	// Add the g-recaptcha tag to the form you want to include the reCAPTCHA element
	?>


        <form id="tellus" action="<?php print $form_action; ?>" method="post" class="pure-form">
            <div class="talkback_form <?php print $tb_bonus_css; ?>">
                <p><strong><?php print _( "Your comment:" ); ?></strong><br/>
                    <textarea name="the_suggestion" cols="26" rows="6" class="form-item"
                              value="<?php print $this_comment; ?>"></textarea><br/><br/>
                    <strong><?php print _( "Your email (optional):" ); ?></strong><br/>
                    <input type="text" name="name" size="20" value="<?php print $this_name; ?>"
                           class="form-item"/>
                    <br/>
					<?php print _( "(In case we need to contact you)" ); ?>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                    <br/><br/>

                    <button type="submit" name="submit_comment" class="pure-button pure-button-topsearch"
                    ><?php print _( "Submit" ); ?>
                    </button>
                </p>
            </div>
        </form>

        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>"></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('<?php echo $siteKey; ?>', { action: 'talkback' }).then(function (token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        </script>

	<?php
	endif; ?>




    <p>
</div>
<div class="tipend"></div>