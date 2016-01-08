<?php
/**
 *   @file index.php
 *   @brief Display the subject guides by collection splash page
 *
 *   @author adarby
 *   @date sept 2015
 */
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;
   
$use_jquery = array("ui");

$page_title = _("Research Guide Collections");
$description = _("The best stuff for your research.  No kidding.");
$keywords = _("research, databases, subjects, search, find");
$noheadersearch = TRUE;

$db = new Querier;
$connection = $db->getConnection();
    
// let's use our Pretty URLs if mod_rewrite = TRUE or 1
if ($mod_rewrite == 1) {
   $guide_path = "";
} else {
   $guide_path = "guide.php?subject=";
}

///////////////////////
// Have they done a search?

$search = "";

if (isset($_POST["search"])) {
    $search = scrubData($_POST["search"]);
}

// Get the subjects for jquery autocomplete
$suggestibles = "";  // init

$q = "select subject, shortform from subject where active = '1' AND type != 'Placeholder' order by subject";


$statement = $connection->prepare($q);
$statement->execute();
$r = $statement->fetchAll();

//initialize $suggestibles
$suggestibles = '';

foreach ($r as $myrow) {
    $item_title = trim($myrow[0][0]);


	if(!isset($link))
	{
		$link = '';
	}

    $suggestibles .= "{text:\"" . htmlspecialchars($item_title) . "\", url:\"$link$myrow[1][0]\"}, ";

}

$suggestibles = trim($suggestibles, ', ');


// Get our newest guides
$q2 = "select subject, subject_id, shortform from subject where active = '1' and type != 'Placeholder' order by subject_id DESC limit 0,5";

$statement = $connection->prepare($q2);
$statement->execute();
$r2 = $statement->fetchAll();

$newest_guides = "<ul>\n";

foreach ($r2 as $myrow2 ) {
    $guide_location = $guide_path . $myrow2[2];
    $newest_guides .= "<li><a href=\"$guide_location\">" . trim($myrow2[0]) . "</a></li>\n";
}

$newest_guides .= "</ul>\n";


// Get our newest databases
$qnew = "SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5";

$statement = $connection->prepare($qnew);
$statement->execute();
$rnew = $statement->fetchAll();

$newlist = "<ul>\n";
    foreach ($rnew as $myrow) {
    $db_url = "";

    // add proxy string if necessary
    if ($myrow[2] != 1) {
        $db_url = $proxyURL;
    }

    $newlist .= "<li><a href=\"$db_url$myrow[1][0]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";


// Add header now 

include("includes/header_um.php");

// put together our main result display

    $guide_results = "";

if (isset($_GET["d"])) {

    $guide_results = listGuideCollections($_GET["d"]);
  
} else {
  // Default collection listing
  $intro = "<p></p>";
  $guide_results = listCollections($search);

}

//$layout = makePluslet("", $guide_results, "","",FALSE);

// End CHC hack


////////////////////////////
// Now we are finally read to display the page
////////////////////////////

?>

<div class="panel-container">
<div class="pure-g" id="guidesplash">
    
    <div class="pure-u-1 pure-u-lg-3-4 panel-adj" id="listguides">
        <div class="breather">     
            <?php print $guide_results; ?>
        </div> <!-- end breather -->
    </div><!--end 3/4 main area-->

    <div class="pure-u-1 pure-u-lg-1-4 sidebar-bkg">

          <!-- start tip -->
          <div class="tip">
            <h2><?php print _("Search Guides"); ?></h2>
                  <?php
                  $input_box = new CompleteMe("quick_search", "index.php", $proxyURL, "Quick Search", "guides", '');
                  $input_box->displayBox();
                  ?>
          </div>
          <!-- end tip -->
          <div class="tipend"> </div>
          
          <!-- start tip -->
            <div class="tip">
                <h2><?php print _("Newest Guides"); ?></h2>
                <?php print $newest_guides; ?>
            </div>
            <!-- end tip -->
            <div class="tipend"> </div>

        <!-- start tip -->
        <div class="tip">
            <h2><?php print _("Newest Databases"); ?></h2>
            <?php print $newlist; ?>
        </div>
        <!-- end tip -->
        <div class="tipend"> </div>
        

    </div><!--end 1/4 sidebar-->

</div> <!--end pure-g-->
</div> <!--end panel-container-->
<?php
///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer_um.php");

?>


<script type="text/javascript" language="javascript">
    $(document).ready(function(){

        // add rowstriping
        stripeR();


        $("[id*=show]").on("change", function() {

            var showtype_id = $(this).attr("id").split("-");
            //alert("u clicked: " + showtype_id[1]);
            unStripeR();
            $(".type-" + showtype_id[1]).toggle();
            stripeR();


        });

        function stripeR() {
            $(".zebra").not(":hidden").filter(":even").addClass("evenrow");
            $(".zebra").not(":hidden").filter(":odd").addClass("oddrow");
        }

        function unStripeR () {
            $(".zebra").removeClass("evenrow");
            $(".zebra").removeClass("oddrow");
        }

    });
</script>
