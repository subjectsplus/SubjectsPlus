<?php
/**
 *   @file index.php
 *   @brief Display the subject guides splash page
 *
 *   @adapted by dzydlewski from a document created by adarby
 *   @date adapted feb 2012
 */
use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\AllHandler;

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

$use_jquery = "";

$page_title = _("Search Results for Resources by Title and Description");

$letter = "";
$page_title1 = "";
$intro = "";
$subject_id = "";

$description = _("Search for resources available through these research guides.");
$keywords = _("library, research, electronic journals, databases, electronic resources, full text, online, magazine, articles, paper, assignment");


try {
  $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
  echo $e;
}


// Add a quick search
if (isset($_POST["searchterm"])) {
  $_GET["letter"] = $_POST["searchterm"];

  $searchphrase = scrubData($_POST["searchterm"]);
  $page_title1 = "Search results for: " . $searchphrase;
} else {

}

//$alphabet = getLetters("databases", $_GET["letter"]);


// Get our newest databases

$qnew = "SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5";

$rnew = mysql_query($qnew);

$newlist = "<ul>\n";
while ($myrow = mysql_fetch_array($rnew)) {
  $db_url = "";

  // add proxy string if necessary
  if ($myrow[2] != 1) {
    $db_url = $proxyURL;
  }

  $newlist .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";

// Intro text

if (isset($_POST["searchterm"])) {
  $selected = scrubData($_POST["searchterm"]);
  $intro .= "<p  >Search results for <strong>$selected</strong></p><br />";

  // Create our table of databases object

$our_items = new AllHandler();
$out = $our_items->writeTable($_GET["letter"], "");
} else {
  $out = _("You must enter a search term.");
  $selected = "";
}

//$intro = "<h3 style=\"color: #C07922;\">" . $page_title1 . "</h3>";



// Assemble the content for our main pluslet/box
$display = $intro . $out;


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


////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header.php");
?>
<div id="leftcol">
  <div class="pluslet">
    <div class="pluslet_header">
      <div class="titlebar_text">Enter the search term below:</div>


      <form action="search.php" method="post" style="margin-left: 1em;">
        <input type="text" id="letterhead_suggest" size="30" class="searchinput-3" name="searchterm" value="<?php print $selected; ?>" />
        <input type="submit" value="Search" class="button" />
      </form><br/>

      <ul style="margin-left: 2em;">
        <li>Enter a search term of greater than 3 characters (e.g. biology) or a search phrase (e.g. American history). </li>
        <li>Results based on resource title and description.</li>
      </ul>
      <br />
    </div>
  </div>
  <div class="pluslet">

    <div class="pluslet_body">

<?php print $display; ?>


    </div>
  </div>
</div>
<div id="rightcol">
  <div class="pluslet">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Key to Icons"); ?></div>
    </div>
    <div class="pluslet_body"> <?php print $legend; ?> </div>
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

<?php

///// DB Functions //////

function getTableOptions($selected, $subject_id = '') {

  //

  $selection = "WHERE (title LIKE '%" . mysql_real_escape_string($selected) . "%' OR description LIKE '%" . mysql_real_escape_string($selected) . "%')";


  return $selection;
}

///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer.php");
?>
<script type="text/javascript" language="javascript">
  $(document).ready(function(){


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