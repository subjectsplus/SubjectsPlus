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
    </div>
</div>

<section class="section usearch_problem_report">
    <div class="container">
		<?php
		if ( isset( $feedback ) && ! empty( $feedback ) ) {
			echo '<div class="feature-light p-3 text-center mb-3"><strong>' . $feedback . '</strong></div>';
		}
		?>

		<?php if (  isset( $hide_form ) && !$hide_form ) : ?>
            <div class="row">
                <div class="col-md-8 col-lg-6">
					<?php
					if ( ( isset( $problem_report_use_recaptcha ) ) && ( $problem_report_use_recaptcha === true ) ) {
						include 'form_recaptcha.php';
					} else {
						include 'form.php';
					}
					?>
                </div>

                <div class="col-md-4 col-lg-4 offset-lg-2">
                    <div class="feature popular-list">
                        <h4>- Need help now? -</h4>
                        <a href="https://library.miami.edu/research/ask-a-librarian.html" class="btn btn-default">Ask a
                            Librarian</a>
                    </div>
                </div>
            </div>
		<?php endif; ?>

    </div>
</section>