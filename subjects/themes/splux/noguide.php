<?php
/**
 * @file collection.php
 * @brief Display the subject guides by collection splash page
 */

use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;


$page_title  = _( "Research Guide Unavailable" );
$description = _( "This content currently unavailable." );
$keywords    = _( "" );


// Add header now
include( "includes/header_splux.php" );

// Now we are finally read to display the page
?>

<div class="section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><a href="<?php print $PublicPath; ?>index.php" class="no-decoration default">Research Guides</a></h1>
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

<section class="section section-half-top">
    <div class="container">
        <p>This guide is currently unavailable. It may be under maintenance, or just resting.</p>
        <p>See a <a href="<?php print $PublicPath; ?>index.php">listing of all guides</a>, or use the search box above.</p>
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
include( "includes/footer_splux.php" );
?>