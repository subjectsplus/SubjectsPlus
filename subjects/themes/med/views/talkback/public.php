<div class="panel-container">
    <div class="pure-g">
        <div class="pure-u-1 pure-u-lg-3-4 panel-adj">
            <div class="breather">

				<?php if ( isset( $insertCommentFeedback ) && ! empty( $insertCommentFeedback ) ) {
					echo $insertCommentFeedback;
				} else { ?>

					<?php print _( "<p>Please use this page to write comments or make suggestions about Library services, resources, and facilities.</p>
<p>Suitable comments will be posted along with Library responses.</p>" ); ?>

				<?php } ?>


                <div id="letterhead_small" align="center"><?php print $cat_filters; ?></div>


                <h2><?php echo $comment_header; ?> <span style="font-size: 12px;"><a
                                href="<?php echo $form_action . $current_comments_link; ?>"><?php echo $current_comments_label; ?> </a> </span>
                </h2>


				<?php if ( is_array( $comments ) ) {

					$row_count = 1;

					foreach ( $comments as $comment ): ?>

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
                                <span style="clear: both;font-size: 11px;">Comment on <em><?php echo date( "D M j, Y, g:i a", strtotime( $comment['date_submitted'] ) ); ?></em></span>
                            </p>
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

			<?php
			if ( ( isset( $talkback_use_recaptcha ) ) && ( $talkback_use_recaptcha === true ) ) {
				include 'comment_form_recaptcha.php';
			} else {
				include 'comment_form.php';
			}
			?>


        </div><!--end 1/4 sidebar column-->

    </div> <!--end pure-g-->
</div> <!--end panel-container-->