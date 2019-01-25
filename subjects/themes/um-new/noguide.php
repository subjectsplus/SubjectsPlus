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
include( "includes/header_um-new.php" );

// Now we are finally read to display the page
?>

<input id="jekyll-category" value="sp-guide" type="hidden">
<div class="feature section-minimal">
    <div class="container text-center minimal-header">
        <h1><a href="index.php" class="no-decoration default">Research Guides</a></h1>
        <hr align="center" class="hr-panel">

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon"></div>
        </div>
    </div>
</div>

<!-- Search Component -->
<section class="search-area d-none d-lg-block">
    <div class="full-search">
        <div class="container text-center">
            <div class="search-group">
                <div class="uml-site-search-container"></div>
            </div>
        </div>
    </div>
</section>

<section class="section section-half-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
    <p>This guide is currently unavailable. It may be under maintenance, or just resting.</p>
    
    <p>See a <a href="index.php">listing of all guides</a>, or use the search box above.</p>
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
            </div>
            <div class="col-lg-4">

            </div>
        </div>
    </div>
</section>


<?php
// Load footer file
include( "includes/footer_um-new.php" );
?>