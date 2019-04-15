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
						<?php print _( "<p><strong>Talk Back</strong> is where you can <strong>ask a question</strong> or <strong>make a suggestion</strong> about library services.</p>
		<p>So, please let us know what you think, and we will post your suggestion and an answer from one of our helpful staff members.</p>" ); ?>
                    </div>
                    <div class="pluslet_simple no_overflow">
                        <h2>Comments from 2019 <span style="font-size: 11px; font-weight: normal;"><a
                                        href="talkback.php?t=prev&amp;v=main">See previous years</a></span></h2>

                        <?php foreach($comments as $comment): ?>

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
	                            if ( $show_talkback_face == 1 ) {
		                            echo getHeadshot( $comment['email'] );
	                            }
                            ?>
                                    <?php echo $comment['answer']; ?></p>
                            <p style="clear: both;font-size: 11px;">Answered by <a
                                        href="<?php echo $lib_page; ?>"><?php echo $comment['title']; ?></a>, <?php echo $comment['fname']; ?> <?php echo $comment['lname']; ?>
                                </p>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>
                <div class="pure-u-1 pure-u-lg-1-3">
                    <!-- start pluslet -->
                    <div class="pluslet">
                        <div class="titlebar">
                            <div class="titlebar_text">Tell Us What You Think</div>
                        </div>
                        <div class="pluslet_body">
                            <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <strong>Wait! Do you need help right now?</strong><br>Visit the Research Desk! </p>
                            <br>

                            <?php //include_once 'comment_form.php'; ?>
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
