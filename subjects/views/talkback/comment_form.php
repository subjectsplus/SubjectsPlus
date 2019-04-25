<?php ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("tellus").submit();
    }
</script>
<form id="tellus" action="<?php print $form_action; ?>" method="post" class="pure-form">
    <div class="talkback_form">
        <p><strong><?php print _( "Your comment:" ); ?></strong><br/>
            <textarea name="the_suggestion" cols="26" rows="6" class="form-item"
                      value="<?php print $this_comment; ?>"></textarea><br/><br/>
            <strong><?php print _( "Your email (optional):" ); ?></strong><br/>
            <input type="text" name="name" size="20" value="<?php print $this_name; ?>"
                   class="form-item"/>
            <br/>
			<?php print _( "(In case we need to contact you)" ); ?>
            <br/><br/>
			<?php global $talkback_recaptcha_site_key; ?>
            <button type="submit" name="submit_comment" class="btn btn-default g-recaptcha"
                    data-sitekey="<?php echo $talkback_recaptcha_site_key; ?>"
                    data-callback="onSubmit"
                    data-size="invisible"><?php print _( "Submit" ); ?></button>
        </p>
    </div>
</form>

<?php global $talkback_recaptcha_site_key; ?>
<?php global $talkback_recaptcha_secret_key; ?>

<?php

$siteKey = $talkback_recaptcha_site_key;
$secret = $talkback_recaptcha_secret_key;

if ( $siteKey === '' || $secret === '' ):
	?>
    <h2>Add your keys</h2>
    <p>If you do not have keys already then visit <kbd> <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a></kbd>
        to generate them. Edit this file and set the respective keys in <kbd>$siteKey</kbd> and <kbd>$secret</kbd>.
        Reload the page after this.</p>
<?php
else:
	// Add the g-recaptcha tag to the form you want to include the reCAPTCHA element
	?>
    <p>The reCAPTCHA v3 API provides a confidence score for each request.</p>
    <p><strong>NOTE:</strong>This is a sample implementation, the score returned here is not a reflection on your Google
        account or type of traffic. In production, refer to the distribution of scores shown in <a
                href="https://www.google.com/recaptcha/admin" target="_blank">your admin interface</a> and adjust your
        own threshold accordingly. <strong>Do not raise issues regarding the score you see here.</strong></p>
    <ol id="recaptcha-steps">
        <li class="step0">reCAPTCHA script loading</li>
        <li class="step1 hidden"><kbd>grecaptcha.ready()</kbd> fired, calling
            <pre>grecaptcha.execute('<?php echo $siteKey; ?>', {action: 'examples/v3scores'})'</pre>
        </li>
        <li class="step2 hidden">Received token from reCAPTCHA service, sending to our backend with:
            <pre class="token">fetch('/recaptcha-v3-verify.php?token=abc123</pre>
        </li>
        <li class="step3 hidden">Received response from our backend:
            <pre class="response">{"json": "from-backend"}</pre>
        </li>
    </ol>
    <p><a href="/recaptcha-v3-request-scores.php">⟳ Try again</a></p>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>"></script>
    <script>
        const steps = document.getElementById('recaptcha-steps');
        grecaptcha.ready(function () {
            document.querySelector('.step1').classList.remove('hidden');
            grecaptcha.execute('<?php echo $siteKey; ?>', {action: 'examples/v3scores'}).then(function (token) {
                document.querySelector('.token').innerHTML = 'fetch(\'/recaptcha-v3-verify.php?action=examples/v3scores&token=\'' + token;
                document.querySelector('.step2').classList.remove('hidden');

                fetch('/recaptcha-v3-verify.php?action=examples/v3scores&token=' + token).then(function (response) {
                    response.json().then(function (data) {
                        document.querySelector('.response').innerHTML = JSON.stringify(data, null, 2);
                        document.querySelector('.step3').classList.remove('hidden');
                    });
                });
            });
        });
    </script>
<?php
endif; ?>
