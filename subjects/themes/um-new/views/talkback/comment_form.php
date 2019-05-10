<div class="tip">
    <h2><?php echo _("Need help <strong>now</strong>? <br/><a href='https://library.miami.edu/research/ask-a-librarian.html'>Ask a Librarian</a>.</h2>"); ?>
    <form id="tellus" action="<?php echo $form_action; ?>" method="post" class="pure-form">
        <div class="talkback_form <?php echo $tb_bonus_css; ?>">
            <p><strong><?php echo _( "Your comment:" ); ?></strong><br/>
                <textarea name="the_suggestion" cols="26" rows="6" class="form-item"
                          value="<?php echo $this_comment; ?>"></textarea><br/><br/>
                <strong><?php echo _( "Your email (optional):" ); ?></strong><br/>
                <input type="text" name="name" size="20" value="<?php echo $this_name; ?>"
                       class="form-item"/>
                <br/>
				<?php echo _( "(In case we need to contact you)" ); ?>
                <br/><br/>

                <button type="submit" name="submit_comment" class="pure-button pure-button-topsearch"><?php echo _( "Submit" ); ?></button>
            </p>
        </div>
    </form>
</div>
<div class="tipend"></div>