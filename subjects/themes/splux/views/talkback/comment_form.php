<div>
    <h4><?php echo _("Need help now? Contact Us."); ?></h4>
    <form id="tellus" action="<?php echo $form_action; ?>" method="post">
        <div class="talkback_form <?php echo $tb_bonus_css; ?>">
            <p class="mb-2"><strong><?php echo _( "Your comment:" ); ?></strong></p>
            <textarea name="the_suggestion" cols="26" rows="6" class="form-item" value="<?php echo $this_comment; ?>"></textarea>
            <p class="mb-2 mt-3"><strong><?php echo _( "Your email (optional):" ); ?></strong></p>
            <input type="text" id="suggestion_email" name="suggestion_email" size="20" value="<?php echo $this_name; ?>" class="form-item"/>
            <p style="font-size:.875rem;"><em><?php echo _( "(In case we need to contact you)" ); ?></em></p>
                <button type="submit" name="submit_comment" class="pure-button pure-button-topsearch btn-default"><?php echo _( "Submit" ); ?></button>
        </div>
    </form>
</div>