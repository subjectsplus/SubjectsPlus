<?php
/**
 *   @file databases.php
 *   @brief Display the databases A-Z page
 *
 *   @author adarby
 *   @date jan 2012
 */

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\DbHandler;    

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if (isset($subjects_theme)  && $subjects_theme != "") { include("themes/$subjects_theme/databases.php"); exit;}

$db = new Querier;
$connection = $db->getConnection();

$use_jquery = array("ui");

$page_title = _("Database List");
$description = _("An alphabetical list of the electronic resources available.");
$keywords = _("library, research, electronic journals, databases, electronic resources, full text, online, magazine, articles, paper, assignment");

// set a default if the letter isn't set
if (!isset($_GET["letter"])) {
  $_GET["letter"] = "A";
  $page_title .= ":  A";
} else {
  $page_title .= ": " . ucfirst(scrubData($_GET["letter"]));
}

// Deal with databases by subject display
if (!isset($_GET["subject_id"])) {
  $_GET["subject_id"] = "";
  $clean_id = "";
} else {
  $clean_id = scrubData($_GET["subject_id"], "integer");
}



if ($_GET["letter"] == "bysub") {
  $page_title = _("Database List By Subject");
  if ($clean_id == "") {
    $_GET["subject_id"] = "";
    $show_subjects = TRUE;
  } else {
    $show_subjects = FALSE;
	  // add subject name to title

	  $statement = $connection->prepare("SELECT subject FROM subject WHERE subject_id = :id");
	  $statement->bindParam(":id", $clean_id);
	  $statement->execute();
	  $myrow = $statement->fetchAll();
	  

        $page_title .= ": " . $myrow[0][0];
  }
} else {
  $_GET["subject_id"] = "";
  $show_subjects = FALSE;
}

// Deal with databases by type display
if ($_GET["letter"] == "bytype") {
  $page_title = _("Database List By Format");
  if (!isset($_GET["type"])) {
    $_GET["type"] = "";
    $show_types = TRUE;
  } else {
    $clean_type = ucfirst(scrubData($_GET["type"]));
    $pretty_type = ucwords(preg_replace('/_/', ' ', $clean_type));
    $page_title .= ": " . $pretty_type;
    $show_types = FALSE;
  }
} else {
  $_GET["type"] = "";
  $show_types = FALSE;
  $clean_type = "";
}

// Add a quick search
$description_search = 0; // init to negative
if (isset($_POST["searchterm"])) {
  $_GET["letter"] = "%" . $_POST["searchterm"];
  $page_title = _("Database List: Search Results");
  $description_search = 1; // if you want to search descriptions, too, set to 1; otherwise to 0
}

$alphabet = getLetters("databases", $_GET["letter"], 1, TRUE);

// Get our newest databases

$connection = $db->getConnection();
$statement = $connection->prepare("SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5");
$statement->execute();
$rnew = $statement->fetchAll(); 

$newlist = "<ul>\n";
foreach ($rnew as $myrow) {
  $db_url = "";

  // add proxy string if necessary
  if ($myrow[0][2] != 1) {
    $db_url = $proxyURL;
  }

  $newlist .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";

// Featured Databases //

  $featured_list = "";
  $featured = FALSE;
  
// requires featured in ctags field in control/includes/config.php
$connection = $db->getConnection();
$featured_statement = $connection->prepare("select distinct title, location, access_restrictions, title.title_id as this_record
        FROM title, location, location_title
        WHERE ctags LIKE '%featured%'
        AND title.title_id = location_title.title_id
        AND location.location_id = location_title.location_id
        ORDER BY title");
$featured_statement->execute();

$featured_list = "";

if ($rfeatured = $featured_statement->fetchAll()) {
  $featured_list = "<ul>\n";
  foreach ($rfeatured as $myrow) {

    // add proxy string if necessary
    if ($myrow[2] != 1) {
      $db_url = $proxyURL;
    } else {
      $db_url = "";
    }

    $featured_list .= "<li><a href=\"" . $db_url . $myrow[1] . "\">$myrow[0]</a></li>\n";

  }

  $featured_list .= "</ul>\n";
  $featured = TRUE;

} 


// Intro text
$intro = "";

if (isset($_POST["searchterm"])) {
  $selected = scrubData($_POST["searchterm"]);
  $intro .= "<p style=\"background-color: #eee; padding: .3em; border: 1px solid #ccc; width: 75%;\">Search results for <strong>$selected</strong></p><br />";
}

$intro .= "<br class=\"clear-both\" />
<div style=\"float: right; padding: 0 1.5em .5em 0;\"><a id=\"expander\" style=\"cursor: pointer;\">expand all descriptions</a></div>";

// Create our table of databases object

$our_items = new DbHandler();

$out = "";

// if we're showing the subject list, do so

if ($show_subjects == TRUE) {
  $out .= $our_items->displaySubjects();

} elseif ($show_types == TRUE) {

  $out .= $our_items->displayTypes();
} else {
  // if it's the type type, show filter tip
  if (isset($clean_type) && $clean_type != "") {
    $out .= "<div class=\"faq_filter\">displaying databases filtered by $clean_type >> <a href=\"databases.php?letter=bytype\">view all types</a></div>";
  }

  // otherwise display our results from the database list
   
  $out .= $our_items->writeTable($_GET["letter"], $clean_id, $description_search);
}

// Assemble the content for our main pluslet/box
$display = $intro . $out;

include("includes/header.php");

// Our version 2 vs version 3 styles choice

if (isset ($v2styles) && $v2styles == 1) {
  $db_results = "<form class=\"pure-form\">$alphabet</form>
  $display";

  $layout = makePluslet("", $db_results, "","",FALSE);
} else {
  print "version 3 styles not set up yet";
}


////////////////////////////
// Now we are finally read to display the page
////////////////////////////

?>

<div class="pure-g">
<div class="pure-u-1 pure-u-lg-2-3">

      <?php print $layout; ?>

</div>
<div class="pure-u-1 pure-u-lg-1-3 database-page">
  <!-- start pluslet -->
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Search Databases"); ?></div>
    </div>
    <div class="pluslet_body">
          <?php
          $input_box = new CompleteMe("quick_search", "databases.php", $proxyURL, "Quick Search", "records", 30);
          $input_box->displayBox();
          ?>
    </div>
  </div>
  <!-- end pluslet -->

  <?php if ($featured) { ?>
  <!-- start pluslet -->
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Featured Databases"); ?></div>
    </div>
    <div class="pluslet_body"> <?php print $featured_list; ?> </div>
  </div>
  <!-- end pluslet -->
  <?php } ?>  

  <!-- start pluslet -->
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Newest Databases"); ?></div>
    </div>
    <div class="pluslet_body"> <?php print $newlist; ?> </div>
  </div>
  <!-- end pluslet -->

  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Key to Icons"); ?></div>
    </div>
    <div class="pluslet_body"> <?php global $all_ctags; print showIcons($all_ctags, 1); ?></div>
  </div>
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
  
/*  We use this at UM to track database clicks as events in GA
    $(".trackContainer a").click(function() {
      _gaq.push(['_trackEvent', 'OutboundLink', 'Click', $(this).text()]);
      // alert($(this).text());
    });
*/
    var stripeholder = ".zebra";
    // add rowstriping
    stripeR();


    $("[id*=show]").livequery("change", function() {

      var showtype_id = $(this).attr("id").split("-");
      //alert("u clicked: " + showtype_id[1]);
      unStripeR();
      $(".type-" + showtype_id[1]).toggle();
      stripeR();

    });

    // show db details
    $("span[id*=bib-]").livequery("click", function() {
      var bib_id = $(this).attr("id").split("-");
      $(this).parent().parent().find(".list_bonus").toggle()
    });

    // show all db details
    $("#expander").click(function() {
      $(".list_bonus").toggle()
    })

    function stripeR(container) {
      $(".zebra").not(":hidden").filter(":even").addClass("evenrow");
      $(".zebra").not(":hidden").filter(":odd").addClass("oddrow");
    }

    function unStripeR () {
      $(".zebra").removeClass("evenrow");
      $(".zebra").removeClass("oddrow");
    }


  });
</script>
