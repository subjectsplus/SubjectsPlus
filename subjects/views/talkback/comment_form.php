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
