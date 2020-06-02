<?php $siteKey = $talkback_recaptcha_site_key;

if ( $siteKey === '' ): ?>
    <h2>Add your keys</h2>
    <p>If you do not have keys already then visit <kbd> <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a></kbd>
        to generate them. Edit this file and set the respective keys in <kbd>$siteKey</kbd> and <kbd>$secret</kbd>.
        Reload the page after this.</p>
<?php else:
// Add the g-recaptcha tag to the form you want to include the reCAPTCHA element
?>
<div class="feature popular-list">
    <h3>- Need help now? -</h3>
    <a href="https://library.miami.edu/research/ask-a-librarian.html" class="btn btn-default">Ask a Librarian</a>
    <hr>

    <form id="tellus" action="talkback.php" method="post">
        <div class="<?php print $tb_bonus_css; ?>">
            <div class="form-group">
                <label for="the_suggestion"><?php print _( "Your comment:" ); ?></label>
                <textarea name="the_suggestion" id="the_suggestion" class="form-control" rows="3" value="<?php print $this_comment; ?>"></textarea>
            </div>
            <div class="form-group">
                <label for="suggestion_email"><?php print _( "Your email (optional):" ); ?></label>
                <input type="email" class="form-control" id="suggestion_email" name="suggestion_email" value="">
                <p>(In case we need to contact you)</p>
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            </div>
            <button type="submit" name="submit_comment" class="pure-button pure-button-topsearch"
            ><?php print _( "Submit" ); ?>
            </button>
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
</div>

<?php endif; ?>