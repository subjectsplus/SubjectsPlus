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
                                <span style="clear: both;font-size: 11px;">Comment on <em><?php echo date("D M j, Y, g:i a", strtotime($comment['date_submitted']) ); ?></em></span></p>
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
	                <?php
	                if( (isset($talkback_use_recaptcha)) && ($talkback_use_recaptcha === true )) {

		                include 'comment_form_recaptcha.php';
	                } else {
		                include 'comment_form.php';
	                }
	                ?>
                </div>
            </div>
            <!-- END BODY CONTENT -->

        </div> <!--end #body_inner_wrap-->
    </div> <!--end pure-u-1-->
</div>
