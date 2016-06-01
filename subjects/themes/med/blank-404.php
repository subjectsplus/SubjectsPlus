<?php

$page_title = "Page not Found";
$description = "404 Page not found";
$keywords = "404, missing page";

$use_jquery = array("ui", "ui_styles");

include("../../../control/includes/config.php");
include("../../../control/includes/functions.php");
include("../../includes/header_med.php");

?>


<div class="panel-container">
<div class="pure-g">

	<div class="pure-u-1 pure-u-lg-3-4 panel-adj">
        <div class="breather">
          	<p>We looked everywhere, and we can't find that page.</p>
			
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

include("../../includes/footer_med.php");

?>