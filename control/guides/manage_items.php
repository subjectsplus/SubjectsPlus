<?php
/**
 *   @file manage_items.php
 *   @brief Organize records associated with a guide
  called by guide.php via functions.php when the pencil icon is clicked
  for an "all items by source" pluslet/box
 *
 *   @author adarby
 *   @date feb 2011
 */
$subcat = "guides";
$page_title = "Manage Resources";

ob_start();
if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
    $no_header = "yes";
}

include("../includes/header.php");

$postvar_subject_id = '';

if(isset($_GET['subject_id']))
{
	$postvar_subject_id = $_GET['subject_id'];
}
$nocookies = "";

if (isset($_REQUEST["subject_id"])) {

    $subject_id = $_REQUEST["subject_id"];

    $q = "SELECT subject, shortform FROM subject WHERE subject_id = '$subject_id'";

    $r = $db->query($q);

    $mysub = mysql_fetch_row($r);

    $subject_name = $mysub[0];
    $shortform = $mysub[1];

    setcookie("our_guide", $subject_name);
    setcookie("our_guide_id", $postvar_subject_id);
    setcookie("our_shortform", $shortform);
} elseif (isset($_COOKIE["our_guide_id"])) {

    $subject_id = $_COOKIE["our_guide_id"];
    $subject_name = $_COOKIE["our_guide"];
    $shortform = $_COOKIE["our_shortform"];
} else {

    $nocookies = "yes";
}

ob_end_flush();

$subs_query = "SELECT distinct s.subject_id, s.subject
FROM subject s INNER JOIN tab t
ON s.subject_id = t.subject_id
INNER JOIN pluslet_tab pt
ON t.tab_id = pt.tab_id
INNER JOIN pluslet p
ON pt.pluslet_id = p.pluslet_id
WHERE p.type != 'Special'
ORDER BY s.subject, s.type";


/* Select all active records (this is based on a db connection made above) */
$subs_result = $db->query($subs_query);

// create the option
$subs_option_boxes = "";

while ($myrow = mysql_fetch_array($subs_result)) {
    $subs_id = $myrow["0"];
    $subs_name = $myrow["1"];

    $subs_option_boxes .= "<option value=\"manage_items.php?subject_id=$subs_id\">$subs_name</option>";
}

$all_guides = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" size=\"1\" onChange=\"window.location=this.options[selectedIndex].value\">
<option value=\"\">" . _("-- Choose a Different Guide --") . "</option>
$subs_option_boxes
</select>
</form>";

$q = "select title.title_id, title, location, source, source.source_id, rank.rank_id
FROM title, restrictions, location, location_title, source, rank
WHERE title.title_id = location_title.title_id and location.location_id = location_title.location_id
AND restrictions_id = access_restrictions and rank.subject_id = '$subject_id' and rank.title_id = title.title_id
AND source.source_id = rank.source_id order by source.rs asc, source.source,
rank.rank asc, title.title";

$r = $db->query($q);

$num_rows = count($r);
$last_source_id = ""; // init
$ourlist = ""; // init

if ($num_rows != "") {
    $row_count = 0;
    while ($myrow = mysql_fetch_row($r)) {

        $this_source_id = $myrow[4];


        if ($this_source_id != $last_source_id) {
            if ($row_count > 0) {
                $ourlist .= "</ul>";
            }
            $ourlist .= "<p class=\"leftcolheader\">$myrow[3]</p>\n
			<ul id=\"sortable-$myrow[4]\" class=\"sortable\">";

            $last_source_id = $this_source_id;
        }

        $ourlist .= "<li id=\"item-$myrow[5]\" style=\"padding: 2px; margin: 4px; border: 1px dashed #ccc;\"><a id=\"delete-$myrow[5]\"><img src=\"$IconPath/delete.png\" class=\"pointer\" /></a> $myrow[1]</li>";

        $row_count++;
    }
} else {

    $ourlist = "<p>" . _("There do not seem to be any resources associated with this guide.  To add new resources, go to the Records tab, select that resource, and then add your subject.") . "</p>";
}

/////////////////////////////////
// Display content
/////////////////////////////////

print "";

if ($nocookies == "yes") {

    $title_line = "<h2>" . _("Organize Resources") . "</h2>";
} else {

    $title_line = "<h2>" . _("Organize Resources for ") . "$subject_name</h2>";
    $response = "<div id=\"response\" class=\"feedback\"></div>
	<br />
	<div id=\"savour\" style=\"clear: both;float:left; padding-left: 40%;  \"><button class=\"button\" id=\"save_guide\" style=\"display:none;\">" . _("SAVE CHANGES") . "</button></div>";
}


    print "<div id=\"maincontent\">
$response
<div class=\"clearboth\"float: left; width: 600px;\">
	<br class=\"clear-both\" />
	$title_line
	<div class=\"box no_overflow\">
        <div id=\"res_content\">
	$ourlist
        </div>
	</div>
</div>";

if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
  // don't include sidebar in popup
} else {
    print "<div style=\"float: left; margin-left: 1em; width: 300px;\">
        <br />
        <h2>" . _("View Guide") . "</h2>
        <div class=\"box\">
        <ul>
        <li><a href=\"guide.php?subject_id=$subject_id\">" . _("Admin guide") . "</a></li>
        <li><a href=\"$PublicPath" . "guide.php?subject=$shortform\">" . _("Public guide") . "</a></li>
        </ul>
</div>
";

}

include("../includes/footer.php");
?>


<script type="text/javascript">
    var user_id = "<?php print $_SESSION["staff_id"]; ?>";
    var user_name = "<?php print $_SESSION["fname"] . " " . $_SESSION["lname"]; ?>";
    var subject_id = "<?php print $postvar_subject_id; ?>";

    $(function() {

        ////////////////////////////
        // MAKE COLUMNS SORTABLE
        // Make "Save Changes" button appear on sorting
        ////////////////////////////
        // connectWith: '.sort-column',

        $('ul[id*=sortable-]').each(function() {

            var update_id = $(this).attr("id").split("-");

            update_id = update_id[1];

            $("#sortable-"+update_id).sortable({
                placeholder: 'ui-state-highlight',
                cursor: 'move',
                update: function() {
                    $("#response").hide();
                    $("#save_guide").fadeIn();
                }

            });
        });

        $('a[id*=delete-]').livequery('click', function(event) {

            var delete_id = $(this).attr("id").split("-");
            var item_id = delete_id[1];

            var confirm_yes = "confirm-yes-" + item_id;

            $("#item-"+item_id).after("<div class=\"confirmer\"><?php print $rusure; ?>  <a id=\"" + confirm_yes + "\"><?php print $textyes; ?></a> | <a id=\"confirm-no-"+item_id+"\"><?php print $textno; ?></a></div>");

            return false;
        });


        $('a[id*=confirm-yes-]')
        .livequery('click', function(event) {

            var delete_id = $(this).attr("id").split("-");
            var rank_id = delete_id[2];

            $(this).parent().remove();
            $("#item-"+rank_id).remove();

            $("#response").load("helpers/ajax_mod.php", {action: 'delete_rank', subject_id: subject_id, user_name: user_name, delete_id:rank_id},
            function() {

                $("#response").fadeIn();
            });

            return false;
        });

        $('a[id*=confirm-no-]')
        .livequery('click', function(event) {

            $(this).parent().remove();

            return false;
        });


        $('button[id=save_guide]').livequery('click', function(event) {


            //////////////////////
            // We're good, save the guide layout
            // insert a pause so the new pluslet is found
            //////////////////////
            $("#response").hide();
            $("#save_guide").fadeOut();
            //$("#savour").append('<span class="loader"><img src="images/loading_animated.gif" height="30" /></span>');
            setTimeout(saveGuide, 1000);

            return false;
        });

        function saveGuide() {

            var our_data = new Array();
            $('ul[id*=sortable-]').each(function() {
                var type_id = $(this).attr("id").split("-");
                type_id = type_id[1];

                our_data.push(type_id);

            });


            for (x in our_data) {

                var num = our_data[x];
                our_data[x] = new Array();
                our_data[x] = $('#sortable-'+num).sortable('serialize', {key:num});

            }
            //alert(our_data.join("&"));
            our_data = our_data.join("&");
            // alert(our_data);

            $("#response").load("helpers/ajax_mod.php", {action: 'update_rank', subject_id: subject_id, user_name: user_name, our_data:our_data},
            function() {

                $("#response").fadeIn();
            });

        }


    });
</script>
