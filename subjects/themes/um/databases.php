<?php
/**
 *   @file databases.php 
 *   @brief themed override of db a-z page for U Miami
 *
 *   @author adarby
 *   @date oct 2014
 */

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\DbHandler;

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

$alphabet = getLetters("databases", $_GET["letter"], "", TRUE);


// Get our newest databases

$qnew = "SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5";

$newlist = "";

if ($rnew = $db->query($qnew)) {

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

include("includes/header_um.php");

// Our version 2 vs version 3 styles choice

if (isset ($v2styles) && $v2styles == 1) {
  $db_results = "<form class=\"pure-form\">$alphabet</form>
  $display";

  $layout = makePluslet("", $db_results, "","",FALSE);
} else {
  print "version 3 styles not set up yet";
}

// Trial Databases //
// UM trial databases--requires DB_Trials in ctags field

$qtrial = "select distinct title, location, access_restrictions, title.title_id as this_record
        FROM title, location, location_title
        WHERE ctags LIKE '%Database_Trial%'
        AND title.title_id = location_title.title_id
        AND location.location_id = location_title.location_id
        ORDER BY title";

$trial_list = "";

if ($rtrial = $db->query($qtrial)) {
$trial_list = "<ul>\n";
  foreach ($rtrial as $myrow) {

    // add proxy string if necessary
    if ($myrow[2] != 1) {
      $db_url = $proxyURL;
    }

    $trial_list .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";

  }

$trial_list .= "</ul>\n";

} else {
  $trial_list = "No trials at this time.";
}


// Legend //
// <img src=\"$IconPath/lock_unlock.png\" width=\"13\" height=\"13\" border=\"0\" alt=\"Unrestricted Resource\"> = Free Resource <br />\n

$legend = "<p>\n<img src=\"$IconPath/v2-lock.png\" border=\"0\" alt=\"Restricted Resource\"> =  " . _("Campus Faculty, Staff &amp; Students only") . "\n<br />
<img src=\"$IconPath/information.png\" border=\"0\" alt=\"more information icon\" /> = " . _("Click for more information") . "<br /><br />
<!--<img src=\"$IconPath/full_text.gif\" width=\"13\" height=\"13\" border=\"0\" alt=\"Some full text available\"> = " . _("Some full text") . "<br />\n-->
<img src=\"$IconPath/camera.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Resource includes images\"> = " . _("Images") . "<br />\n
<img src=\"$IconPath/television.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Resource includes video\"> = " . _("Video files") . "<br />\n
<img src=\"$IconPath/sound.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Resource includes audio\"> = " . _("Audio files") . "<br />\n
</p>\n";

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

?>
<br />
<div class="pure-g">
  <div class="pure-u-1 pure-u-md-3-4">
    <div class="breather">
          <?php print $db_results; ?>
    </div>
  </div>
<div class="pure-u-1 pure-u-md-1-4 database-page"  style="background: url('http://library.miami.edu/wp-content/themes/umiami/images/sidebar_bg_richter_outside2.jpg'); min-height: 500px; background-repeat: no-repeat;">

  <!-- start tip -->
  <div class="tip">
      <h2><?php print _("Search Databases"); ?></h2>
          <?php
          $input_box = new CompleteMe("quick_search", "databases.php", $proxyURL, "Quick Search", "records", '');
          $input_box->displayBox();
          ?>
  </div>
  <div class="tipend"> </div>
  <!-- end tip -->

      <?php if ($newlist) { ?>
      <div class="tip">
        <h2>5 New Databases (<a href="databases.php?letter=bytype&type=New_Databases">see all</a>)</h2>
        <?php print $newlist; ?>
      </div>
      <div class="tipend"></div>
    <?php } ?>

    <?php if ($trial_list) { ?>
      <div class="tip">
        <h2>Database Trials</h2>
        <?php print $trial_list; ?>
        <br />
        <p style="line-height: 1.3em;">Trial demonstrations of fee-based subscription services under consideration.
          Feedback:  <a href="mailto:d.roose@miami.edu">d.roose@miami.edu</a></p>
      </div>
      <div class="tipend"></div>
    <?php } ?>
    <div class="tip">
      <h2>Key to Icons</h2>
      <?php print $legend; ?>
    </div>
    <div class="tipend"></div>

  <br />

</div>
</div>



<?php
///////////////////////////
// Load footer file
///////////////////////////

include("includes/um_footer.php");
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
