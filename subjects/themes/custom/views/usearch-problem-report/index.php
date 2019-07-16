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
			echo $feedback;
		}
		?>

        <div class="row">
            <div class="col-lg-8">
				<?php
				if ( ( isset( $problem_report_use_recaptcha ) ) && ( $problem_report_use_recaptcha === true ) ) {
					include 'form_recaptcha.php';
				} else {
					include 'form.php';
				}
				?>
            </div>

            <div class="col-lg-4">
                <aside>
                    <h3>- Need help now? -</h3>
                    <a href="" class="btn btn-default">Ask a Librarian</a>
                    <hr>
                </aside>
            </div>
        </div>
    </div>
</section>