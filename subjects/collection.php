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

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if (isset($subjects_theme)  && $subjects_theme != "") { include("themes/$subjects_theme/collection.php"); exit;}
   
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

$searchbox = '
<div class="autoC" id="autoC" style="margin: 1em 2em 2em 0;">
    <form id="sp_admin_search" class="pure-form" method="post" action="search.php">
        <span class="titlebar_text">' .  _("Search Research Guides") . '</span>
        <input type="text" placeholder="Search" autocomplete="off" name="searchterm" size="" id="sp_search" class="ui-autocomplete-input autoC"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
        <input type="submit" alt="Search" name="submitsearch" id="topsearch_button" class="pure-button pure-button-topsearch" value="Go">
    </form>
</div>
';

// Add header now 

include("includes/header.php");

// put together our main result display

    $guide_results = "";

if (isset($_GET["d"])) {

    $guide_results = listGuideCollections($_GET["d"]);
  
} else {
  // Default collection listing
  $intro = "<p></p>";
  //$guide_list = listGuides($search, $view_type);
  $guide_results = listCollections($search);
}

$layout = makePluslet("", $guide_results, "","",FALSE);

// End CHC hack


////////////////////////////
// Now we are finally read to display the page
////////////////////////////

?>
<br />
<div class="pure-g" id="guidesplash">
<div class="pure-u-1 pure-u-md-2-3" id="listguides">
<?php print $layout; ?>

    </div>

    <div class="pure-u-1 pure-u-md-1-3">

      <!-- start pluslet -->
      <div class="pluslet">
        <div class="titlebar">
          <div class="titlebar_text"><?php print _("Search Guides"); ?></div>
        </div>
        <div class="pluslet_body">
              <?php
              $input_box = new CompleteMe("quick_search", "search.php", $proxyURL, "Quick Search", "guides", 30);
              $input_box->displayBox();
              ?>
        </div>
      </div>
      <!-- end pluslet -->
        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php print _("Newest Guides"); ?></div>
            </div>
            <div class="pluslet_body"> <?php print $newest_guides; ?> </div>
        </div>
        <!-- start pluslet -->
        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php print _("Newest Databases"); ?></div>
            </div>
            <div class="pluslet_body"> <?php print $newlist; ?> </div>
        </div>
        <!-- end pluslet -->
        <br />

    </div>
</div>
<?php
///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer.php");

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
