<?php ?>
<div class="feature section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><?php if ( isset( $page_title
		               ) && ! empty( $page_title ) ) {
				echo $page_title;
			} ?></h1>
        <hr align="center" class="hr-panel">
        <p class="mb-0"><?php if ( isset( $page_description
		                           ) && ! empty( $page_description ) ) {
				echo $page_description;
			} ?></p>

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon"></div>
        </div>
    </div>
</div>

<section class="section talkback">
    <div class="container">
		<?php
		if ( isset( $insertCommentFeedback ) && ! empty( $insertCommentFeedback ) ) {
			echo $insertCommentFeedback;
		}
		?>

        <div class="row">
            <div class="col-lg-8">
                <!--<div class="pills-container">
                    <ul class="list-unstyled d-flex flex-row flex-wrap justify-content-around justify-content-md-start">
                        <?php //print $cat_filters; ?>
                    </ul>
                </div>-->


                <h2><?php if ( isset( $comment_header ) && ! empty( $comment_header ) ) {
						echo $comment_header;
					} ?>
                    <div class="year-switch"><a
                                href="<?php echo $form_action . $current_comments_link; ?>"><?php echo $current_comments_label; ?> </a>
                    </div>
                </h2>

				<?php if ( isset( $comments ) && ! empty( $comments ) ):

					if ( is_array( $comments ) ) {

						$row_count = 1;

						foreach ( $comments as $comment ): ?>

							<?php
							// Let's link back to the staff page
							$name_id  = explode( "@", $comment['email'] );
							$lib_page = "staff_details.php?name=" . $name_id[0];
							?>

                            <div class="tellus_item">

                                <a name="<?php echo $row_count; ?>"></a>

                                <p class="tellus_comment">
                                    <span class="comment_num"
                                          style="background-image: url('<?php echo $asset_path; ?>images/comment_box.png');"><?php echo $row_count; ?></span>

									<?php echo $comment['question']; ?></p>
                                <p class="comment-meta">Comment on
									<?php echo date( "D M j, Y, g:i a", strtotime( $comment['date_submitted'] ) ); ?>
                                </p>

                                <div class="answer"><?php echo $comment['answer']; ?></div>
                                <div class="responder d-flex flex-row flex-nowrap">
									<?php
									if ( $talkback_show_headshot === true ) {
										echo getHeadshot( $comment['email'] );
									}
									?>

                                    <p>Answered by <a
                                                href="<?php echo $lib_page; ?>"><?php echo $comment['fname']; ?> <?php echo $comment['lname']; ?></a>, <?php echo $comment['title']; ?>
                                    </p>
                                </div>


                            </div>
							<?php $row_count ++; ?>
						<?php endforeach; ?>

					<?php } else {

						echo $comments;
					} ?>
				<?php endif; ?>


            </div>

            <div class="col-lg-4">

	            <?php
	            if( (isset($talkback_use_recaptcha)) && ($talkback_use_recaptcha === true )) {
		            var_dump($talkback_use_recaptcha);
		            include 'comment_form_recaptcha.php';
	            } else {
		            include 'comment_form.php';
	            }
	            ?>
            </div>
        </div>

    </div>
</section>


