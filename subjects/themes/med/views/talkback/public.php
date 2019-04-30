<?php ?>
<div class="panel-container">
    <div class="pure-g">

        <div class="pure-u-1 pure-u-lg-3-4 panel-adj">
            <div class="breather">


	            <?php if (isset($insertCommentFeedback) && !empty($insertCommentFeedback)) {
		            echo $insertCommentFeedback;
	            } else { ?>

		            <?php print _( "<p>Please use this page to write comments or make suggestions about Library services, resources, and facilities.</p>
<p>Suitable comments will be posted along with Library responses.</p>" ); ?>

	            <?php } ?>


                <h2><?php echo $comment_header; ?> <span style="font-size: 12px;"><a href="<?php echo $form_action . $current_comments_link; ?>"><?php echo $current_comments_label; ?> </a> </span></h2>


	            <?php if(is_array($comments)) {

	                $row_count = 1;

		            foreach($comments as $comment): ?>

			            <?php
			            // Let's link back to the staff page
			            $name_id  = explode( "@", $comment['email'] );
			            $lib_page = "staff_details.php?name=" . $name_id[0];
			            ?>

                        <div class="tellus_item oddrow">

                            <a name="<?php echo $row_count; ?>"></a>

                            <p class="tellus_comment">
                                <span class="comment_num"><?php echo $row_count; ?></span>
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
                    <?php $row_count ++; ?>
		            <?php endforeach; ?>

	            <?php } else {

		            echo $comments;
	            } ?>


            </div>
        </div> <!--end 3/4 main area column-->

        <div class="pure-u-1 pure-u-lg-1-4 database-page sidebar-bkg">
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

        </div><!--end 1/4 sidebar column-->

    </div> <!--end pure-g-->
</div> <!--end panel-container-->