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
									<?php echo date( "F j, Y, g:i a", strtotime( $comment['date_submitted'] ) ); ?>
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
                <div class="feature popular-list">
                    <h3>- Need help now? -</h3>
                    <a href="https://new.library.miami.edu/research/ask-a-librarian.html" class="btn btn-default">Ask a
                        Librarian</a>
                    <hr>

					<?php $siteKey = $talkback_recaptcha_site_key; ?>

                    <form id="tellus" action="<?php print $form_action; ?>" method="post">
                        <div>
                            <div class="form-group">
                                <label for="the_suggestion"><?php echo _( "Your comment:" ); ?></label>
                                <textarea name="the_suggestion" id="the_suggestion" class="form-control" rows="3"
                                          value="<?php echo $this_comment; ?>"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="suggestion_email">
									<?php echo _( "Your email (optional):" ); ?>
                                </label>
                                <input type="email" class="form-control" id="suggestion_email" name="suggestion_email"
                                       value="<?php echo $this_name; ?>">
                                <p><?php echo _( "(In case we need to contact you)" ); ?></p>

                            </div>


                            <button type="submit" name="submit_comment" class="btn btn-default"
                            ><?php print _( "Submit" ); ?>
                            </button>
                            </p>
                        </div>
                    </form>


                    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>"></script>
                    <script>
                        grecaptcha.ready(function () {
                            grecaptcha.execute('<?php echo $siteKey; ?>', {action: 'talkback'}).then(function (token) {
                                var recaptchaResponse = document.getElementById('recaptchaResponse');
                                recaptchaResponse.value = token;
                            });
                        });
                    </script>
                </div>
            </div>
        </div>

    </div>
</section>


