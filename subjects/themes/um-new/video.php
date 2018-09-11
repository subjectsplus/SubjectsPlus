<?php
/**
 *   @file video.php
 *   @brief Display the videos
 */

use SubjectsPlus\Control\Querier;

$use_jquery = array("colorbox");

$page_title = _("Library Videos");
$description = _("A collection of videos produced at this library.");
$keywords = _("library, research, videos, instruction");

$extra_sql = "";

// Intro text
$intro = "A collection of videos produced by the University of Miami Libraries.";

try {
} catch (Exception $e) {
    echo $e;
}

// Get Tags
// create the option
$vtag_items = "<ul><li><a href=\"video.php\">All</a></li>";
$display = "";

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
                $location = "https://player.vimeo.com/video/" . $myrow["foreign_id"];
                break;
            case "YouTube":
                $location = "https://www.youtube.com/embed/" . $myrow["foreign_id"];
                break;
        }

        $thumb_loc = $AssetPath . "images/video_thumbs/" . $item_id;
        $thumbnail_path = '';

        if (@fopen($thumb_loc . "_medium.jpg", "r")) {
            $thumbnail_path = $thumb_loc . "_medium.jpg";
        }else if (@fopen($thumb_loc . "_small.jpg", "r")){
            $thumbnail_path = $thumb_loc . "_small.jpg";
        }else{
            $thumbnail_path = $AssetPath . "images/video_thumbs/placeholder/_medium.jpg";
        }

        $thumbnail_medium = "<img src=\"" . $thumbnail_path . "\" alt=\"" . $item_title . "\" class=\"ajax\" href=\"$location\" title=\"Click to play video\" />"; // hard-coded width bc youtube medium is BIIIG
        $thumbnail_small = "<img src=\"" . $thumbnail_path . "\" alt=\"" . $item_title . "\" title=\"Click to play video\" />";
        $date = $myrow["date"];

        // convert seconds into something more friendly
        $item_duration = "";
        $mins = floor($myrow["duration"] / 60);
        if ($mins != 0) {
            $item_duration .= "$mins minutes, ";
        }

        $secs = $myrow["duration"] % 60;
        $item_duration .= "$secs seconds";

        $display .= "<div class=\"vid_container\">$thumbnail_medium <div class=\"vid-meta\"><h3 class=\"ajax\" href=\"$location\" title=\"Click to play video\">$safe_title</h3><p class=\"runtime\">$item_duration</p></div><a class=\"details_details no-decoration default\"><i class=\"fa fa-info-circle\"></i> More about this video</a>
          <div class=\"list_bonus\">$item_blurb</div>
            </div>";
    }
} else {
    $display .= "<div class=\"feature-light p-3\">" . _("Sorry, no videos match this criteria.") . "</div>";
}


// Load header
include("includes/header_um-new.php");
?>

<div class="feature section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
        <hr align="center" class="hr-panel">
        <p class="mb-0 mt-2"><?php print $intro; ?></p>

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon" ></div>
        </div>
    </div>
</div>
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <?php print $display; ?>
            </div>
            <div class="col-lg-3">
                <div class="feature popular-list">
                    <h4><?php print _("Looking for Feature Films?"); ?></h4>
                    <ul>
                        <li><a href="https://miami-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?mode=Basic&vid=uml&tab=default_tab">Library Catalog</a></li>
                        <li><a href="databases.php?letter=bytype&type=Video">Streaming Video</a></li>
                    </ul>
                    <h4>- <?php print _("Browse by Tag"); ?> -</h4>
                    <?php print $vtag_items; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Load footer file
include("includes/footer_um-new.php"); ?>

<script>
    $( function(){

        // show db details
        $('.details_details').click( function() {
            $(this).parent().find(".list_bonus").toggle();
        });

        $(".ajax").colorbox({iframe:true, innerWidth:640, innerHeight:480});

    });
</script>