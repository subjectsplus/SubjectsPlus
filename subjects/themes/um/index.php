<?php
/**
 *   @file index.php
 *   @brief Display the subject guides splash page
 *
 *   @author adarby
 *   @date mar 2011
 */
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;
    
$use_jquery = array("ui");

$page_title = $resource_name;
$description = "The best stuff for your research.  No kidding.";
$keywords = "research, databases, subjects, search, find";
$noheadersearch = TRUE;

$db = new Querier;
$connection = $db->getConnection();

// let's use our Pretty URLs if mod_rewrite = TRUE or 1
if ($mod_rewrite == 1) {
   $guide_path = "";
} else {
   $guide_path = "guide.php?subject=";
}

if (isset($_GET['type']) && in_array(($_GET['type']), $guide_types)) {

    // use the submitted value
    $view_type = scrubData($_GET['type']);
} else {
    $view_type = "all";
}

///////////////////////
// Have they done a search?

$search = "";

if (isset($_POST["search"])) {
    $search = scrubData($_POST["search"]);
}

// set up our checkboxes for guide types
$tickboxes = "<ul>";

foreach ($guide_types as $key) {
    $tickboxes .= "<li><input type=\"checkbox\" id=\"show-" . ucfirst($key) . "\" name=\"show$key\"";
    if ($view_type == "all" || $view_type == $key) {
        $tickboxes .= " checked=\"checked\"";
    }
    $tickboxes .= "/>" . ucfirst($key) . " Guides</li></li>\n";
}

$tickboxes .= "</ul>";

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

$q2 = "select subject, subject_id, shortform from subject where active = '1' AND type != 'Placeholder' order by subject_id DESC limit 0,5";


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

    $newlist .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";

// List guides function -- no other page uses it ? //



$searchbox = '
<div class="autoC" id="autoC" style="margin: 1em 2em 2em 0;">
    <form id="sp_admin_search" class="pure-form" method="post" action="search.php">
        <span class="titlebar_text">' .  _("Search Research Guides") . '</span>
        <input type="text" placeholder="Search" autocomplete="off" name="searchterm" size="" id="sp_search" class="ui-autocomplete-input autoC"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
        <input type="submit" alt="Search" name="submitsearch" id="topsearch_button" class="pure-button pure-button-topsearch" value="Go">
    </form>
</div>
';

// Add header now, because we need a value ($v2styles) from it

include("includes/header_um.php");

// put together our main result display

//$guide_results = listGuides($search, $view_type);

// This is the hack for CHC grouped guides; should be superceded in future //

// Guides by Department
// Set up here for CHC
if (isset($_GET["d"])) {
    $guide_results = "";
  switch ($_GET["d"]) {
    case "CHC":

    $chc_guides = array(
    array(
        "title" => "Cuban Heritage Collection",
        "description" => "An introduction to doing research at the Cuban Heritage Collection, including information about our services and accessing our materials.",
        "url" => "http://libguides.miami.edu/chc?hs=a",
        "image" => "chc-mural_75x75.jpg"
    ),
    array(
        "title" => "CHC Music Guide",
        "description" => "A guide to the music collections at the Cuban Heritage Collection.  Includes a spreadsheet with information about music scores in non-music collections.",
        "url" => "http://sp.library.miami.edu/subjects/guide.php?subject=chcmusic",
        "image" => "chc_beny_more.jpg"
    ),
    array(
        "title" => "CHC Theater Guide",
        "description" => "A guide to the theater collections at the Cuban Heritage Collection.  Includes links to archival and print materials related to our theater holdings.",
        "url" => "http://sp.library.miami.edu/subjects/guide.php?subject=chctheater",
        "image" => "chc_theater.jpg"
    ),
    array(
        "title" => "2014 US-Cuba Policy Change Research Guide",
        "description" => "A guide to resources collected by the Cuban Heritage Collection on the 17 December 2014 US-Cuba policy change announcement.  Includes visualizations of Twitter data captured during the announcement.",
        "url" => "http://sp.library.miami.edu/subjects/guide.php?subject=cubapolicy2014",
        "image" => "cuba_policy.png"

    ),
    array(
        "title" => "Operation Pedro Pan Collections Guide",
        "description" => "A guide to the collections related to the Pedro Pan exodus.  Contains links to oral histories of Pedro Pan children.",
        "url" => "http://sp.library.miami.edu/subjects/guide.php?subject=pedropan",
        "image" => "chc-mural_75x75.jpg"
    )
    );
      $page_title = $page_title . ": Cuban Heritage Collection";
      $intro = "";

      foreach ($chc_guides as $key => $value) {
        $guide_results .= "<div style=\"clear: both;\">
          <img class=\"staff_photo\" align=\"left\" style=\"margin-bottom: 20px;\" title=\"" . $value["title"] . "\" alt=\"" . $value["title"] . "\" src=\"themes/um/chc-images/" . $value["image"] . "\" />
          <a href=\"{$value["url"]}\">{$value["title"]}</a><br />
          {$value["description"]}
          <br style=\"\">
          </div>"
          ;
      }


    break;
  }
} else {
  // Default dubious guide listing
  $intro = "<p> These guides identify key resources in specific areas. Check out our <a href=\"http://libguides.miami.edu/\">complete list of interactive library subject guides</a>, tabbed for easy reference. You can also chat with our resource librarians or leave them a message.</p>";
  //$guide_list = listGuides($search, $view_type);
  $guide_results = listGuides($search, $view_type);
}

// End CHC hack


  $our_results = "<div id=\"letterhead\">$tickboxes</div>
  $guide_results";

  //$layout = makePluslet("", $our_results, "","",FALSE);



////////////////////////////
// Now we are finally read to display the page
////////////////////////////

?>
<div class="panel-container">
<div class="pure-g" id="guidesplash">
    
    <div class="pure-u-1 pure-u-lg-3-4 panel-adj" id="listguides">
        <div class="breather">        
            <?php print $our_results; ?>
        </div> <!-- end breather -->
    </div><!--end 3/4 main area-->

    <div class="pure-u-1 pure-u-lg-1-4 sidebar-bkg">

          <!-- start tip -->
          <div class="tip">
            <h2><?php print _("Search Guides"); ?></h2>
                  <?php
                  $input_box = new CompleteMe("quick_search", "search_results.php", $proxyURL, "Quick Search", "guides", '');
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
