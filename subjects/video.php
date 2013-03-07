<?php
/**
 *   @file video.php
 *   @brief Display the videos
 *
 *   @author adarby
 *   @date feb 2012
 */
include("../control/includes/config.php");
include("../control/includes/functions.php");

$use_jquery = array("colorbox");

$page_title = _("Library Videos");
$description = _("A collection of videos produced at this library.");
$keywords = _("library, research, videos, instruction");

$extra_sql = "";
// Intro text
$intro = "<p>A collection of videos produced at this library.</p>";
$display = "<br class=\"clear\" />";

try {
  $dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
  echo $e;
}

// Get Tags
// create the option
$vtag_items = "
  <ul>
  <li><a href=\"video.php?tag=all\">All</a></li>";

foreach ($all_vtags as $value) {
  $vtag_items .= "<li><a href=\"video.php?tag=$value\">" . ucfirst($value) . "</a></li>";
}

$vtag_items .= "</ul>";

// Clean up user submission
if (isset($_GET["video_id"])) {
  $extra_sql = "and video_id = '" . scrubData($_GET["video_id"], "integer") . "'";
}

if (isset($_GET["tag"])) {
  if (in_array($_GET["tag"], $all_vtags)) {
    $pretty_tag = ucfirst($_GET["tag"]);
    $extra_sql = "and vtags like '%" . $_GET["tag"] . "%'";
  }
}


$q = "select distinct video_id, title, description, source, foreign_id, duration, date
        FROM video
        WHERE display = '1'
        $extra_sql
        ORDER BY date";

// print $q;

// check row count for 0 returns
$r = MYSQL_QUERY($q);
$num_rows = mysql_num_rows($r);



if ($num_rows != 0) {


  while ($myrow = mysql_fetch_array($r)) {

    $patterns = "/'|\"/";
    $replacements = "";

    $item_id = $myrow["video_id"];
    $item_title = $myrow["title"];
    $item_blurb = $myrow["description"];
    $safe_title = trim(preg_replace($patterns, $replacements, $item_title));

    // prepare the location
    switch ($myrow["source"]) {
      case "Vimeo":
        $location = "http://player.vimeo.com/video/" . $myrow["foreign_id"];
        break;
      case "YouTube":
        $location = "http://www.youtube.com/embed/" . $myrow["foreign_id"];
        break;
    }

    $thumb_loc = $AssetPath . "images/video_thumbs/" . $item_id;
    $thumbnail_medium = "<img src=\"" . $thumb_loc . "_medium.jpg\" alt=\"" . $item_title . "\" class=\"ajax\" href=\"$location\" />"; // hard-coded width bc youtube medium is BIIIG
    $thumbnail_small = "<img src=\"" . $thumb_loc . "_small.jpg\" alt=\"" . $item_title . "\"  />";
    $date = $myrow["date"];

    // convert seconds into something more friendly
    $item_duration = "";
    $mins = floor($myrow["duration"] / 60);
    if ($mins != 0) {
      $item_duration .= "$mins minutes, ";
    }

    $secs = $myrow["duration"] % 60;
    $item_duration .= "$secs seconds";

    $display .= "<div class=\"vid_container\">
            $thumbnail_medium <h3 class=\"ajax\" href=\"$location\">$safe_title</h3><p class=\"runtime\">$item_duration</p><a class=\"details_details\">More about this video</a>
          <div class=\"list_bonus\">$item_blurb</div>
            </div>";
  }
} else {
  $display .= "<div class=\"no_results\">" . _("Sorry, no videos match this criteria.") . "</div>";
}

//////////////////////
// To Respond or Not
// Setup our columns
if ($is_responsive == TRUE) {
  $ldiv = "class=\"span9\"";
  $rdiv = "class=\"span3\"";
} else {
  $ldiv = "id=\"leftcol\"";
  $rdiv = "id=\"rightcol\"";
}

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header.php");
?>
<div <?php print $ldiv; ?>>
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print $page_title; ?></div>
        </div>
        <div class="pluslet_body">
            <br />
      <?php print $intro; ?>
      <br />
      <?php print $display; ?>
        </div>
    </div>
</div>
<div <?php print $rdiv; ?>>
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print _("Feature Films"); ?></div>
        </div>
        <div class="pluslet_body"><p>  Looking for movies to check out?  See <a href="">The Place Where We List Movies</a>.</p></div>
    </div>
    <!-- start pluslet -->
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print _("Browse by Tag"); ?></div>
        </div>
        <div class="pluslet_body"><?php print $vtag_items; ?></div>
    </div>
    <!-- end pluslet -->
    <br />

</div>

<br />

<?php
///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer.php");
?>

<script type="text/javascript" language="javascript">
  $(document).ready(function(){


    // show db details
    $(".details_details").live("click", function() {
      
      $(this).parent().find(".list_bonus").toggle()
    });

    $(".ajax").colorbox({iframe:true, innerWidth:640, innerHeight:480});


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