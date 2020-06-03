<?php
use SubjectsPlus\Control\CompleteMe;

$use_jquery = array("ui");

$page_title = "Page Not Found (404)";
$description = "404 Page Not Found";
$keywords = "404, missing page";

include("../control/includes/config.php");
include("../control/includes/functions.php");

//header
include( "includes/header_splux.php" );
?>

<div class="section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
    </div>
</div>

<div class="section section-half">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-lg-8 offset-sm-1 offset-lg-2">
                <!-- Search Area -->
                <div class="default-search">
                    <div class="index-search-area">
                        <?php
                        $input_box = new CompleteMe("quick_search_b", "index.php", $proxyURL, "Find Guides", "guides");
                        $input_box->displayBox();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<section class="section">
    <div class="container">
        <h3>Oh, no!  This page doesn&#8217;t exist.</h3>
        <p>We&#8217;re sorry you had to see this.  We looked everywhere, and we can't find that page.</p>
    </div>
</section>

    <script>
        $(function () {
            //add class to ui-autocomplete dropdown
            $('.ui-autocomplete-input').addClass("index-search-dd");
            $('.index-search-area .pure-button').addClass("btn-small");

        });
    </script>

<?php
// Load footer file
include( "includes/footer_splux.php" ); ?>