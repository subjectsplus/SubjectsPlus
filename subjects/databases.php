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

$db = new Querier;
    
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
    $qt = "SELECT subject FROM subject WHERE subject_id=" . $clean_id . " LIMIT 0,1";

    
    $myrow = $db->query($qt);
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

$alphabet = getLetters("databases", $_GET["letter"], "", FALSE);


// Get our newest databases

$qnew = "SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5";

$rnew = $db->query($qnew);

$newlist = "<ul>\n";
foreach ($rnew as $myrow) {
  $db_url = "";

  // add proxy string if necessary
  if ($myrow[0][2] != 1) {
    $db_url = $proxyURL;
  }

  $newlist .= "<li><a href=\"$db_url$myrow[0]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";

// Intro text

$intro = "<p><img src=\"$IconPath/information.png\" border=\"0\" alt=\"more information icon\" /> " . _("Click for more information about a database.") . "</p>
<br class=\"clear-both\" />";


// Intro text
$intro = "";

if (isset($_POST["searchterm"])) {
  $selected = scrubData($_POST["searchterm"]);
  $intro .= "<p style=\"background-color: #eee; padding: .3em; border: 1px solid #ccc; width: 75%;\">Search results for <strong>$selected</strong></p><br />";
}

$intro .= "<br class=\"clear-both\" />";

// Create our table of databases object

$our_items = new DbHandler();


// if we're showing the subject list, do so

if ($show_subjects == TRUE) {
  $out = $our_items->displaySubjects();
 

} elseif ($show_types == TRUE) {

  $out = $our_items->displayTypes();
} else {
  // if it's the type type, show filter tip
  if (isset($clean_type) && $clean_type != "") {
    $out = "<div class=\"faq_filter\">displaying databases filtered by $clean_type >> <a href=\"databases.php?letter=bytype\">view all types</a></div>";
  }

  // otherwise display our results from the database list
   
  $out = $our_items->writeTable($_GET["letter"], $clean_id, $description_search);
}

// Assemble the content for our main pluslet/box
$display = $alphabet . $intro . $out;

// Legend //
// <img src=\"$IconPath/lock_unlock.png\" width=\"13\" height=\"13\" border=\"0\" alt=\"Unrestricted Resource\"> = Free Resource <br />\n

$legend = "<p class=\"smaller\">\n<img src=\"$IconPath/lock.png\" width=\"13\" height=\"13\" border=\"0\" alt=\"Restricted Resource\"> =  " . _("Campus Faculty, Staff &amp; Students only") . "<br />\n
<img src=\"$IconPath/help.gif\" width=\"13\" height=\"13\" border=\"0\" alt=\"Help guide\"> = " . _("Click for help guide (.pdf or .html)") . "<br />\n
<img src=\"$IconPath/article_linker.gif\" width=\"30\" height=\"13\" border=\"0\" alt=\"ArticleLinker enabled\" /> = " . _("OpenURL Enabled") . "\n<br /><br />
<img src=\"$IconPath/full_text.gif\" width=\"13\" height=\"13\" border=\"0\" alt=\"Some full text available\"> = " . _("Some full text") . "<br />\n
<img src=\"$IconPath/camera.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Resource includes images\"> = " . _("Images") . "<br />\n
<img src=\"$IconPath/television.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Resource includes video\"> = " . _("Video files") . "<br />\n
<img src=\"$IconPath/sound.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Resource includes audio\"> = " . _("Audio files") . "<br />\n
</p>\n";

//////////////////////
// To Respond or Not
// Setup our columns
if ($is_responsive == TRUE) {
  $ldiv = "class=\"span8\"";
  $rdiv = "class=\"span4\"";
} else {
  $ldiv = "id=\"leftcol\"";
  $rdiv = "id=\"rightcol\"";
}

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header.php");
?>
<div class="pure-g-r">
<div class="pure-u-2-3">
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Databases"); ?></div>
    </div>
    <div class="pluslet_body">
      <?php print $display; ?>
    </div>
  </div>
</div>
<div  class="pure-u-1-3">
  <!-- start pluslet -->
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Newest Databases"); ?></div>
    </div>
    <div class="pluslet_body"> <?php print $newlist; ?> </div>
  </div>
  <!-- end pluslet -->
  <!-- start pluslet -->
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Search Databases"); ?></div>
    </div>
    <div class="pluslet_body">
      <form action="databases.php" method="post" autocomplete="off">
        <p>
          <?php
          $input_box = new CompleteMe("quick_search", "databases.php", $proxyURL, "Quick Search", "databases", 30);
          $input_box->displayBox();
          ?>
        </p>
      </form></div>
  </div>
  <!-- end pluslet -->
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Key to Icons"); ?></div>
    </div>
    <div class="pluslet_body"> <?php print $legend; ?> </div>
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
    $(".trackContainer a").click(function() {
      //_gaq.push(['_trackEvent', 'OutboundLink', 'Click', $(this).text()]);
      alert($(this).text());
    });

    var stripeholder = ".zebra";
    // add rowstriping
    stripeR();


    $("[id*=show]").live("change", function() {

      var showtype_id = $(this).attr("id").split("-");
      //alert("u clicked: " + showtype_id[1]);
      unStripeR();
      $(".type-" + showtype_id[1]).toggle();
      stripeR();

    });

    // show db details
    $("img[id*=bib-]").live("click", function() {
      var bib_id = $(this).attr("id").split("-");
      $(this).parent().parent().find(".list_bonus").toggle()
    });

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