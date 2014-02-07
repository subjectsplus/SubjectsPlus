<?php
/**
 *   @file guide.php
 *   @brief Display the subject guides
 *
 *   @author adarby
 *   @date mar 2011
 */
$use_jquery = "yes";

// so that it doesn't conk out if you go directly to display.php

if (!isset($_REQUEST['subject'])) {
    header("Location:index.php?type=redirect");
}

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

// init
$oksubs = array();
$main_col_pluslets = "";
$sidebar_pluslets = "";

// special image path because of mod_rewrite issues when source types are included in URL
$img_path = $PublicPath . "images";


try {
    $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}

// Generate list of acceptable user-submitted subjects
//  WHERE active = '1'

$q = "SELECT shortform FROM subject";
$r = mysql_query($q);

while ($oksub = mysql_fetch_array($r)) {

    $oksubs[] .= $oksub[0];
}

// Check if our user-submitted name is okay; else use default
if (in_array(($_GET['subject']), $oksubs)) {
    // use the submitted subject
    $check_this = $_GET['subject'];
} elseif (in_array(($_POST['subject']), $oksubs)) {
    // use the submitted subject
    $check_this = $_POST['subject'];
} else {
    // use the first good subject
    $check_this = FALSE;
}


$page_title = $resource_name;
$description = _("The best stuff for your research.  No kidding.");
$keywords = _("library, research, databases, subjects, search, find");

if ($check_this) {


    $query = "select subject, subject_id, extra, description, keywords, redirect_url from subject where shortform = '$check_this'";
//print $query;
    $result = mysql_query($query);

    $sub = mysql_fetch_row($result);

	$redirect_url = $sub[5];
	if( !is_null($redirect_url) && !empty($redirect_url)  )
	{
		header("Location:$redirect_url");
	}

    $subject_string = $sub[0];
    $this_id = $sub[1];

    // check for description and keywords, which may be blank since they were added v2
    if ($sub[3] != "") {
        $description = $sub[3];
    }
    if ($sub[4] != "") {
        $keywords = $sub[4];
    }

// get any adjustments to the column widths
/*    if ($sub[2]) {
        $jobj = json_decode($sub[2]);
        $main_width = $jobj->{'maincol'};
        $side_width = 98 - $main_width;
    }
*/


    $jobj = json_decode($sub[2]);
    $col_widths = explode("-", $jobj->{'maincol'});

    if (isset($col_widths[0]) && $col_widths[0] > 0) {
        $left_width = $col_widths[0];
    } else {
        $left_width = 0;
    }

    if (isset($col_widths[1])) {
        $main_width = $col_widths[1];
    } else {
        $main_width = 0;
    }

    if (isset($col_widths[2]) && $col_widths[2] > 0) {
        $side_width = $col_widths[2];
    } else {
        $side_width = 0;
    }

// select all the pluslets from an existing guide and put on screen

    $qc = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra
	FROM pluslet p, subject s, pluslet_subject ps
	WHERE p.pluslet_id = ps.pluslet_id
	AND s.subject_id = ps.subject_id
	AND s.subject_id = '$this_id'
	ORDER BY prow ASC
	";

//print $qc;

    $rc = mysql_query($qc);

        //init
    $left_col_pluslets = "";
    $main_col_pluslets = "";
    $sidebar_pluslets = "";

    while ($myrow = mysql_fetch_array($rc)) {

        // Get our guide type
        // Make sure it's not blank, as that will throw an error
        if ($myrow[4] != "") {
            // Here we assemble the pluslet, that will be gound in
            if ($myrow[4] == "Special") {
                $obj = "sp_Pluslet_" . $myrow[0];
            } else {
                $obj = "sp_Pluslet_" . $myrow[4];
            }


            global $obj;
            $record = new $obj($myrow[0], "", $this_id);
/*
            if ($myrow[3] == 1) {
                // Main column content
                $main_col_pluslets .= $record->output("view", "public");
            } else {
                // sidebar contents
                $sidebar_pluslets .= $record->output("view", "public");
            }

            */

            switch ($myrow[3]) {
                case 0:
                $left_col_pluslets .= $record->output("view", "public");
                break;
                case 1:
                default:
                $main_col_pluslets .= $record->output("view", "public");
                break;
                case 2:
                $sidebar_pluslets .= $record->output("view", "public");
                break;
            }
        }
        unset($record);
    }

    $page_title = "$page_title: $subject_string";

    // get last revised
    $last_mod = lastModded("guide", $this_id,0,0);

} else {
    //////////////
    //Invalid subject name
    /////////////

    $main_col_pluslets = "
<div class=\"pluslet\">
   <div class=\"titlebar\">
    <div class=\"titlebar_text\">" . _("No Guide") . "</div>
   </div>
   <div class=\"pluslet_body\">
   <p>There does not appear to be any guide by that name.</p>
   <p><a href=\"index.php\">Please try again</a></p>
   </div>
</div>";
}
////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header.php");

// responsive or not?
if (isset($is_responsive) && $is_responsive == TRUE) {
    // make sure they aren't 0

    if ($left_width > 0) {
        print "<div class='span$left_width'>
        $left_col_pluslets
        </div>";
    }

    if ($main_width > 0) {
        print "<div class='span$main_width'>
        $main_col_pluslets
        </div>";
    }

    if ($side_width > 0) {
        print "<div class='span$side_width'>
        $sidebar_pluslets
        </div>";
    }

} else {
    print "<div id=\"leftcol\">
    $left_col_pluslets
    </div>
    <div id=\"maincol\">
    $main_col_pluslets
    </div>
    <div id=\"rightcol\">
    $sidebar_pluslets
    </div>";
}



///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer.php");

?>

<script type="text/javascript" language="javascript">

    $(document).ready(function(){

        // .togglebody makes the body of a pluslet show or disappear
        $('.titlebar_text').livequery('click', function(event) {

            $(this).parent().next('.pluslet_body').toggle('slow');
        });

        var new_left_width = "<?php print $left_width * 7.5; ?>%";
        var new_main_width = "<?php print $main_width * 7.5; ?>%";
        var new_sidebar_width = "<?php print $side_width * 7.5; ?>%";
        //alert(new_left_width + "-" + new_main_width + "-" + new_sidebar_width);
        if (new_main_width.length > 0) {
            $('#leftcol').width(new_left_width);
            $('#maincol').width(new_main_width);
            $('#rightcol').width(new_sidebar_width);
        }


    });

    $(window).load(function(){
        // jQuery functions to initialize after the page has loaded.

        function findStuff() {
            $(".find_feed").each(function(n) {
                var feed = $(this).attr("name").split("|");
                $(this).load("includes/feedme.php", {type: feed[4], feed: feed[0], count: feed[1], show_desc: feed[2], show_feed: feed[3]});
            });

        }

        findStuff();
    });


</script>
