<?php
/**
 *   @file video.php
 *   @brief Display the videos
 *
 *   @author adarby
 *   @date feb 2012
 */

use SubjectsPlus\Control\Querier;
    
$use_jquery = array("colorbox");

$page_title = _("Library Videos");
$description = _("A collection of videos produced at this library.");
$keywords = _("library, research, videos, instruction");

$extra_sql = "";
// Intro text
$intro = "<p>A collection of videos produced by the University of Miami Libraries.</p>";
$display = "<br />";

try {
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
	$video_id = scrubData($_GET["video_id"], "integer");

	$db = new Querier;
	$connection = $db->getConnection();
	$statement = $connection->prepare("select distinct video_id, title, description, source, foreign_id, duration, date
        FROM video
        WHERE display = '1'
	    and video_id = :video_id
  		ORDER BY date");

	$statement->bindParam(":video_id", $video_id);
	$statement->execute();
	$r = $statement->fetchAll();
}

if (isset($_GET["tag"])) {
	if (in_array($_GET["tag"], $all_vtags)) {
			
		$pretty_tag = "%" . ucfirst($_GET["tag"]) . "%";

		$db = new Querier;
		$connection = $db->getConnection();
		$statement = $connection->prepare("select distinct video_id, title, description, source, foreign_id, duration, date
        FROM video
        WHERE display = '1'
	    and vtags like :tag
  		ORDER BY date");

		$statement->bindParam(":tag", $pretty_tag);
		$statement->execute();
		$r = $statement->fetchAll();

	}
}

if (empty($_GET)) {
	$db = new Querier;
	$connection = $db->getConnection();
	$statement = $connection->prepare("select distinct video_id, title, description, source, foreign_id, duration, date
        FROM video
        WHERE display = '1'
  		ORDER BY date");
	
	$statement->execute();
	$r = $statement->fetchAll();
}

$num_rows = count($r);


if ($num_rows) {


  foreach ($r as $myrow) {

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

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header_um.php");
?>

<div class="panel-container">
<div class="pure-g">
      <div class="pure-u-1 pure-u-lg-3-4 panel-adj">
          <div class="breather">
          <?php print $intro; ?>
          <?php print $display; ?>
          </div>
      </div> <!--end 3/4 main area-->

      <div class="pure-u-1 pure-u-lg-1-4 sidebar-bkg">
              <!-- start tip -->
              <div class="tip">
                  <h2><?php print _("Feature Films"); ?></h2>
                  <p>  Looking for movies to check out?  See <a href="http://library.miami.edu/media/">CDs/DVDs</a> or <a href="http://library.miami.edu/udvd/">UDVD</a>.</p>
              </div>
              <!-- end tip -->
              <div class="tipend"> </div>

              <!-- start tip -->
              <div class="tip">
                  <h2><?php print _("Browse by Tag"); ?></h2>
                  <?php print $vtag_items; ?>
              </div>
              <!-- end tip -->
              <div class="tipend"> </div>

      </div> <!--end 1/4 sidebar column-->

</div><!--end pure=g-->
</div> <!-- end panel-container -->


<?php
///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer_um.php");
?>

<script type="text/javascript" language="javascript">
  $(document).ready(function(){


    // show db details
    $(".details_details").on("click", function() {
      
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