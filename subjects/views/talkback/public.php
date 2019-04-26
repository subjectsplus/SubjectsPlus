<?php ?>
<div class="pure-g">
    <div class="pure-u-1">
        <div id="content_roof"></div> <!-- end #content_roof -->
        <div id="shadowkiller"></div> <!--end #shadowkiller-->

        <div id="body_inner_wrap">

            <script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
            <script>
                function onSubmit(token) {
                    document.getElementById("tellus").submit();
                }
            </script>

            <br>
            <div class="pure-g">
                <div class="pure-u-1 pure-u-lg-2-3">
                    <div class="pluslet_simple no_overflow">

                        <?php if (isset($insertCommentFeedback) && !empty($insertCommentFeedback)) {
                            echo $insertCommentFeedback;
                        } else { ?>

	                    <?php print _( "<p><strong>Talk Back</strong> is where you can <strong>ask a question</strong> or <strong>make a suggestion</strong> about library services.</p>
<p>So, please let us know what you think, and we will post your suggestion and an answer from one of our helpful staff members.</p>" ); ?>

                        <?php } ?>

                    </div>
                    <div class="pluslet_simple no_overflow">
                        <h2><?php echo $comment_header; ?> <span style="font-size: 12px;"><a href="<?php echo $form_action . $current_comments_link; ?>"><?php echo $current_comments_label; ?> </a> </span></h2>


                        <?php if(is_array($comments)) {

                        foreach($comments as $comment): ?>

                            <?php
	                            // Let's link back to the staff page
		                        $name_id  = explode( "@", $comment['email'] );
		                        $lib_page = "staff_details.php?name=" . $name_id[0];
                            ?>

                        <div class="tellus_item oddrow">

                            <a name="<?php echo $comment['talkback_id']; ?>"></a>

                            <p class="tellus_comment">
                                <span class="comment_num"><?php echo $comment['talkback_id']; ?></span>
                                <strong><?php echo $comment['question']; ?></strong><br>
                                <span style="clear: both;font-size: 11px;">Comment on <em><?php echo $comment['date_submitted']; ?></em></span></p>
                            <br>

                            <p><?php
	                            if ( $talkback_show_headshot === true ) {
		                            echo getHeadshot( $comment['email'] );
	                            }
                            ?>
                                    <?php echo $comment['answer']; ?></p>
                            <p style="clear: both;font-size: 11px;">Answered by <a
                                        href="<?php echo $lib_page; ?>"><?php echo $comment['title']; ?></a>, <?php echo $comment['fname']; ?> <?php echo $comment['lname']; ?>
                                </p>
                        </div>
                        <?php endforeach; ?>

                        <?php } else {

                            echo $comments;
                        } ?>

                    </div>
                </div>
                <div class="pure-u-1 pure-u-lg-1-3">
                    <!-- start pluslet -->
                    <div class="pluslet">
                        <div class="titlebar">
                            <div class="titlebar_text"><?php print _( "Tell Us What You Think" ); ?>
                            </div>
                        </div>
                        <div class="pluslet_body">
                            <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
	                            <?php print _( "<strong>Wait! Do you need help right now?</strong><br>Visit the Research Desk!" ); ?>
                            </p>
                            <br>

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
                                <?php echo $recaptcha_response; ?>

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
                                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                                            <br/><br/>

                                            <button type="submit" name="submit_comment" class="btn btn-default"
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

                        </div>
                    </div>
                    <!-- end pluslet -->
                    <br>

                </div>
            </div>
            <!-- END BODY CONTENT -->

        </div> <!--end #body_inner_wrap-->
    </div> <!--end pure-u-1-->
</div>
