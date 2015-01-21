<?php

$page_title = "Page not Found";
$description = "404 Page not found";
$keywords = "404, missing page";

$use_jquery = array("ui", "ui_styles");

include("../../../control/includes/config.php");
include("../../../control/includes/functions.php");
include("../../includes/header_um.php");

?>


<div class="panel-container">
<div class="pure-g">

	<div class="pure-u-1 pure-u-lg-3-4 panel-adj">
        <div class="breather">
          	<p>We looked everywhere, and we can't find that page.</p>
			<p>Try <strong>searching</strong> for the page you want, or
			<strong> select a category</strong> from the navigational menu above.</p>
			<br>
			<form id="head_search" method="post" action="http://library.miami.edu/wp-content/themes/umiami/resolver.php">
			<input type="hidden" value="website" name="searchtype">
			<input id="search_tabs" class="searchinput-4" type="text" autocomplete="off" size="40" name="searchterms" value="">
			<input type="submit" name="submitsearch" value="Search" class="search404">
			</form>
        </div>
    </div> <!--end 3/4 main area column-->


    <div class="pure-u-1 pure-u-lg-1-4 database-page sidebar-bkg">

    </div><!--end 1/4 sidebar column-->

      

      

</div><!--end pure-g-->
</div> <!--end panel-container-->


<!--Data Table-->


<?php

////////////
// Footer
///////////

include("../../includes/footer_um.php");

?>