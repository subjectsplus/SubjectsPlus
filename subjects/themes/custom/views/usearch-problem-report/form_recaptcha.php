<?php $siteKey = $problem_report_recaptcha_site_key;

if ( $siteKey === '' ): ?>
    <h2>Add your keys</h2>
    <p>If you do not have keys already then visit <kbd> <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a></kbd>
        to generate them. Edit this file and set the respective keys in <kbd>$siteKey</kbd> and <kbd>$secret</kbd>.
        Reload the page after this.</p>
<?php else:
// Add the g-recaptcha tag to the form you want to include the reCAPTCHA element
?>
<div class="feature popular-list">
    <form action="<?php echo $form_action; ?>" method="post" class="pure-form">
        <p><strong>* Indicates required fields</strong></p>
        <table class="form_listing">
            <tr>
                <td valign="top">* Name:</td>
                <td valign="top" class="form-item"><input name="user_name" size="" value=""/></td>
            </tr>
            <tr class="evenrow">
                <td valign="top">* Email:</td>
                <td valign="top" class="form-item"><input name="user_email" size="" value=""/></td>
            </tr>
            <tr>
                <td valign="top">UM Affiliation:</td>
                <td valign="top" class="form-item">
                    <select name="affiliation">
                        <option value="Student">Student</option><option value="Faculty/Staff">Faculty/Staff</option><option value="Unaffiliated">Not Affiliated with UM</option></select>
                </td>
            </tr>
            <tr class="evenrow">
                <td valign="top">Problem Item <?php echo $item_title; ?></td>
                <td valign="top" class="form-item"><input name="item_title" id="item_title" size="" value=""/></td>
            </tr>
            <tr>
                <td valign="top">Problem Item Permalink</td>
                <td valign="top" class="form-item"><input name="item_permalink" id="item_permalink" size="" value="" readonly /></td>
            </tr>
            <input type="hidden" name="item_view" value=""/>
            <tr>
                <td valign="top">* Type of Problem</td>
                <td valign="top" class="form-item">
                    <select name="problem_type">
                        <option value="Connecting">Connecting to resources (login errors, browser errors)</option><option value="Viewing Full-text">Viewing full-text (broken links; asked for password/payment)</option><option value="Description Error">Catalog description error</option><option value="Not on Shelf">Item not on shelf</option><option value="Other">Other</option></select>
                </td>
            </tr>
            <tr class="evenrow">
                <td valign="top">* Please describe the problem you encountered in as much detail as possible.</td>
                <td valign="top" class="form-item"><textarea name="description" rows="8" cols="55"></textarea></td>
            </tr>
        </table><p>
            <input type="hidden" id="primo_view" name="primo_view" value=""/>
            <input type="hidden" id="problem_report_form" name="problem_report_form"/>
            <input type="hidden" name="use_recaptcha" id="use_recaptcha"/>
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <button type="submit" name="submit" class="pure-button pure-button-topsearch"
            ><?php print _( "Submit" ); ?>
            </button>
            &nbsp;<input type="reset" value="Reset" class="pure-button pure-button-off">
        </p>
    </form>

    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>"></script>
    <script>

        $( document ).ready(function() {
            console.log( "ready!" );

            function getUrlVars() {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars;
            }

            function getUrlParam(parameter, defaultvalue){
                var urlparameter = defaultvalue;
                if(window.location.href.indexOf(parameter) > -1){
                    urlparameter = getUrlVars()[parameter];
                }
                return urlparameter;
            }

            var title = getUrlParam('item_title', 'No Title');
            var permalink = getUrlParam('item_permalink', 'No Permalink');
            var primo_view = getUrlParam('v', 'richter');

            $("#item_title").val(title);
            $("#item_permalink").val(permalink);
            $('#primo_view').val(primo_view);
        });



        grecaptcha.ready(function () {
            grecaptcha.execute('<?php echo $siteKey; ?>', { action: 'usearch_problem_report' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>
</div>

<?php endif; ?>