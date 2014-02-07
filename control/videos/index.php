<?php
/**
 *   @file index.php
 *   @brief handles RUD (Read, Update, Delete) for Video module.
 *
 *   @author adarby
 *   @date march 2011
 */
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\DBConnector;
    
$subcat = "video";
$page_title = "Video Admin";

include("../includes/header.php");

try {
  $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
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

$vidArray = $querierVid->getResult($qVid);

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
                <div style=\"float: left; width: 64px; max-width: 10%;\">
                <a  class=\"showmedium\" href=\"video.php?video_id=$value[0]&amp;wintype=pop\"><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a>
                &nbsp; &nbsp;<a href=\"" . $VideoPath . "?video_id=$value[0]\" target=\"_blank\"><img src=\"$IconPath/eye.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a>
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


print "<br />
<div style=\"float: left;  width: 70%;\">
    <div class=\"box no_overflow\" id=\"answered\">
    <p><strong>$row_count1 " . _("Videos visible") . "</strong> ";
if (!isset($_GET["limit"]) || $_GET["limit"] != "all") {
  print "(<a href=\"index.php?limit=all\">" . _("See All") . "</a>)";
}
print "</p><br />
    $vid_list
    </div>

</div>

<div style=\"float: right; width: 28%;margin-left: 10px;\">
        <div class=\"box\">
    <h2 class=\"bw_head\">" . _("Ingest Video Metadata") . "</h2>

    <p><a href=\"ingest.php\">" . _("FIND VIDEOS") . "</a></p>
    </div>
      <div class=\"box\">
    <h2 class=\"bw_head\">" . _("Add Metadata by Hand") . "</h2>
  
    <p><a href=\"video.php\">" . _("ENTER VIDEO") . "</a></p>
    </div>
    <div class=\"box\">
    <h2 class=\"bw_head\">" . _("About Videos") . "</h2>
    
    <p><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /> = " . _("Edit Video Info") . "</p>
    <p><img src=\"$IconPath/eye.png\" alt=\"edit\" width=\"16\" height=\"16\" /> = " . _("View Video on Public Site") . "</p>
    </div>
</div>
";


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


