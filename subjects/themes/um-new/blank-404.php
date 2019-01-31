<?php
$page_title = "Page Not Found (404)";
$description = "404 Page Not Found";
$keywords = "404, missing page";

include("../control/includes/config.php");
include("../control/includes/functions.php");

//header
include("includes/header_um-new.php");
?>

<div class="second-level">
    <section class="page-header">
        <div class="row no-gutters">
            <div class="col-lg-6 order-last order-lg-first">
                <div class="page-header-details">
                    <div class="page-header-details-block">
                        <h1>Page Not Found (404)</h1>
                        <hr align="center" class="hr-panel">
                        <ul class="d-sm-flex flex-sm-row flex-sm-nowrap justify-content-sm-center">



                            <li><i class="fas fa-envelope"></i> <a href="mailto:webmaster.lib@miami.edu">webmaster.lib@miami.edu</a></li>

                        </ul>


                        <div class="page-highlights">

                        </div>


                        <div class="favorite-heart">
                            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon" ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-first order-lg-last">
                <div class="page-header-img" style="background-image: url('https://umlibraryeast.blob.core.windows.net/uploads/2018/04/1260-450-nasa-control.jpg');">

                    <script>
                        //Basic Image Tooltips
                        $( function(){
                            $('.basic-img-info').tooltip();
                        });
                    </script>
                    <a href="" class="no-decoration basic-img-info" data-toggle="tooltip" data-placement="top" title="Project Gemini Mission Control Center"><i class="fas fa-info-circle"></i></a>

                </div>
            </div>
        </div>

    </section>
</div>

<section class="search-area d-none d-lg-block">
    <div class="full-search">
        <div class="container text-center">
            <div class="search-group">
                <div class="uml-site-search-container"></div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h3>Oh, no!  This page doesn&#8217;t exist.</h3>
        <p>We&#8217;re sorry you had to see this.  We looked everywhere, and we can't find that page.</p>
        <p>Try <strong>searching</strong> for the page you want, or <strong>select a category</strong> from the navigational menu above.</p>
    </div>
</section>

<?php
// Load footer file
include("includes/footer_um-new.php"); ?>