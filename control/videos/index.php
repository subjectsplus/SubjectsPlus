<?php
/**
 *   @file index.php
 *   @brief handles RUD (Read, Update, Delete) for Video module.
 *
 *   @author adarby
 *   @date march 2011
 */
use SubjectsPlus\Control\Querier;

    
$subcat = "video";
$page_title = "Video Admin";

include("../includes/header.php");

try {
  } catch (Exception $e) {
  echo $e;
}

if (isset($_GET["limit"])) {
  if ($_GET["limit"] == "all") {
  $limit = "";
  } else {
  $limit = "LIMIT 0," . scrubData($_GET["limit"], "int");
  }

} else {
  $limit = "";
}

$querierVid = new Querier();
$qVid = "SELECT video_id, title, description, source, foreign_id, duration, date, display, vtags
	FROM video
	ORDER BY video_id DESC
	$limit";

$vidArray = $querierVid->query($qVid);

$row_count1 = 0;
$row_count2 = 0;

$colour1 = "evenrow";
$colour2 = "oddrow";

$vid_list = "";

if ($vidArray) {
  foreach ($vidArray as $value) {
    $row_colour1 = ($row_count1 % 2) ? $colour1 : $colour2;

    $short_title = Truncate($value["title"], 200);
    $short_desc = stripslashes(htmlspecialchars_decode(TruncByWord($value["description"], 15)));
    $last_revised_line = lastModded("video", $value[0]);

        if ($value[7] != "1") {
            $activity = " <span style=\"color: #666;\">* " . _("unpublished") . "</span>";
            
        } else {
          $activity = "";
        }
    $vid_list .= "
            <div style=\"clear: both; float: left;  padding: 3px 5px; width: 98%;\" class=\"striper $row_colour1\">
                <div style=\"pluslet_body\">
                <a  class=\"showmedium\" href=\"video.php?video_id=$value[0]&amp;wintype=pop\"><i class=\"fa fa-pencil fa-lg\" alt=\"" . _("Edit") . "\"></i></a>
                &nbsp; &nbsp;<a href=\"" . $VideoPath . "?video_id=$value[0]\" target=\"_blank\"><i class=\"fa fa-eye fa-lg\" alt=\"" . _("View") . "\"></i></a>
                </div>
                <div style=\"float: left; width: 90%;\">
                 $short_title <span style=\"color: #666; font-size: 10px;\">($last_revised_line)</span> $activity
                </div>
            </div>";

    $row_count1++;
  }
} else {

  $vid_list = "<p>" . _("No Videos yet.  Grab your camera.") . "</p>";
}

$ingest_body_text = "<a href=\"ingest.php\">" . _("FIND VIDEOS") . "</a>";
$add_metadata_text = "<a href=\"video.php\">" . _("ENTER VIDEO") . "</a>";
$about_videos_text = "<p><i class=\"fa fa-pencil fa-lg\" alt=\"" . _("Edit") . "\"></i> = " . _("Edit") . "</p>
    <p><i class=\"fa fa-eye fa-lg\" alt=\"" . _("View") . "\"></i> = " . _("View Video on Public Site") . "</p>";



print "<br />

<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">  

    <div class=\"pluslet no_overflow\" id=\"answered\">
    <div class=\"titlebar\">" . _("Collected Videos") . "</div>
    <p class=\"pluslet_body\"><strong>$row_count1 " . _("Videos visible") . "</strong> ";
if (!isset($_GET["limit"]) || $_GET["limit"] != "all") {
  print "(<a href=\"index.php?limit=all\">" . _("See All") . "</a>)";
}
print "</p><br />
<div class=\"pluslet_body\">    
$vid_list
</div>
</div>";

print "</div>"; // close pure-u-2-3
print "<div class=\"pure-u-1-3\">";

makePluslet(_("Ingest Video Metadata"), $ingest_body_text, "no_overflow");

makePluslet(_("Add Metadata by Hand"), $add_metadata_text, "no_overflow");

makePluslet(_("About Videos"), $about_videos_text, "no_overflow");

print "</div>"; // close pure-u-1-3
print "</div>"; // close pure-g


include("../includes/footer.php");
?>


<script type="text/javascript">
  $(document).ready(function(){
    $(".toggle_unanswered").click(function() {
      $("#unanswered .hideme").toggle();
      return false;
    });

    $(".toggle_answered").click(function() {
      $("#answered .hideme").toggle();
      return false;
    });

    /////////////////
    // Load custom modal window
    ////////////////

    $("a[class*=showcustom]").colorbox({
      iframe: true,
      innerWidth:"80%",
      innerHeight:"90%",
      maxWidth: "960px",
      maxHeight: "800px",

      onClosed:function() {
        location.reload();
      }
    });

  });
</script>


