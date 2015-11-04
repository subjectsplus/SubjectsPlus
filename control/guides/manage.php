<?php
/**
 *   @file manage.php
 *   @brief Deal with files and records stored in SP
 *
 *   @author adarby, rgilmour
 *   @date april 2011; updated mar 2013--removed staff_pluslet table functionality, thus we can delete this table.
 */

use SubjectsPlus\Control\Querier;


$subsubcat = "";
$subcat = "guides";
$page_title = "Manage Files, Record Associations, etc.";

include("../includes/header.php");

//init
$all_guides = "";

//////////
// File mgr
// not ready yet; so all commented out
// with a lot of files in fckuserfiles, php timed out
///////////////


$ignoreMe = array("headshot.jpg"); // files we don't care about
//this users
$this_user = explode("@", $_SESSION["email"]);
/*
  // Collect the filenames from the following two locations and their subdirectories
  $fckuserfilesPath = "../../assets/fckuserfiles";
  $spuserfilesPath = "../../assets/users";
  $fckFiles = listdir($fckuserfilesPath);
  $userFiles = listdir($spuserfilesPath);
  $files = array_merge($fckFiles, $userFiles);
 */

$use_unix_find = TRUE;

// admin can add parameter to see all
//change dgonzalez v2.0 to use getAssestPath function (dynamic)
if ((isset($_GET["view"]))) {
    if ($_GET["view"] == "all") {
        //$userPath = $root . "/sp/assets/users";
        $userPath = getAssetPath() . "users";
    } else {
        //$userPath = $root . "/sp/assets/users/" . "_" . scrubData($_GET["view"]);
        $userPath = getAssetPath() . "users" . DIRECTORY_SEPARATOR . "_" . scrubData($_GET["view"]);
    }
} else {
    //$userPath = $root . "/sp/assets/users/" . "_" . $this_user[0];
    $userPath = getAssetPath() . "users" . DIRECTORY_SEPARATOR . "_" . $this_user[0];
}

// Collect the filenames from the following two locations and their subdirectories
if (!isset($use_unix_find)) {

    $userFilesCmd = "find " . $userPath . " -type f";
    $userFiles = shell_exec($userFilesCmd);
    $userFiles = preg_split('/\n/', $userFiles);
    $disposable = array_pop($userFiles);
} else {
    $userFiles = listdir($userPath);
}

$querier = new Querier;

$file_list = "<table width=\"98%\" class=\"striper\">
    <tr>
    <th>" . _("File") . "</th>
    <th>" . _("Size") . "</th>
    <th>" . _("Owner") . "</th>
    <th>" . _("Guides") . "</th>
    <th>" . _("Delete") . "</th>
    </tr>";

if ($userFiles) {
    $rowcount = 1;
    foreach ($userFiles as $f) {
        $nameParts = array_reverse(preg_split("/[\/\\\\]/", $f));

        $shortName = $nameParts[0];
        $nu = explode( DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "users", $f);
        //print "<p>" . $nu[1];
        $location_hint = $nameParts[1];
        $linky = $UserPath . $nu[1];

        if (!in_array($shortName, $ignoreMe)) { // can we ignore it?
            $fileInfo = stat($f);
            $fileSizeKb = number_format($fileInfo['size'] / 1000, 0);

/*
            $findOwnersQuery = "SELECT s.fname, s.lname
                        FROM pluslet p, pluslet_staff ps, staff s
                        WHERE
                        p.pluslet_id = ps.pluslet_id AND
                        s.staff_id = ps.staff_id AND
                        p.body LIKE '%" . $shortName . "%'";
            print $findOwnersQuery;

            $findOwnerResult = $querier->query($findOwnersQuery);
            $owner = $findOwnerResult[0]['fname'] . " " . $findOwnerResult[0]['lname'];

            $findGuidesQuery = "SELECT s.subject, s.subject_id
                        FROM pluslet p, pluslet_subject ps, subject s
                        WHERE
                        p.pluslet_id = ps.pluslet_id AND
                        s.subject_id = ps.subject_id AND
                        p.body LIKE '%" . $location_hint . "/" . $shortName . "%'";

            //print $findGuidesQuery;
            $findGuidesResult = $querier->query($findGuidesQuery);
            $guides = array(); // for the list of guides in which the file appears

            if ($findGuidesResult) {
                foreach ($findGuidesResult as $row) {
                    $guideName = $row['subject'];
                    $guideId = $row['subject_id'];
                    $guides["$guideId"] = $guideName;
                }
            }
*/
        	$db = new Querier();


            $findGuidesQuery = "
				SELECT st.fname, st.lname, s.subject, s.subject_id
				FROM pluslet p INNER JOIN pluslet_section ps
				ON p.pluslet_id = ps.pluslet_id
				INNER JOIN section sec
				ON ps.section_id = sec.section_id
				INNER JOIN tab t
				ON sec.tab_id = t.tab_id
				INNER JOIN subject s
				ON t.subject_id = s.subject_id
				INNER JOIN staff_subject ss
				ON s.subject_id = ss.subject_id
				INNER JOIN staff st
				ON ss.staff_id = st.staff_id
				WHERE p.body LIKE " . $db->quote('%' . $location_hint . "/" .$shortName. '%') . "
                OR p.body LIKE " . $db->quote('%' . $location_hint . trim( " \\ " ) . $shortName. '%') . "
                OR p.body LIKE " . $db->quote('%' . $location_hint . trim( " \\ " ) . "image" . trim( " \\ " ) . $shortName. '%') . "
                OR p.body LIKE " . $db->quote('%' . $location_hint . "/image/" . $shortName. '%');

            $findGuidesResult = $querier->query($findGuidesQuery);
            $guides = array(); // for the list of guides in which the file appears

            if ($findGuidesResult) {

                foreach ($findGuidesResult as $row) {
                    $owner = $row['fname'] . " " . $row['lname'];
                    $guideName = $row['subject'];
                    $guideId = $row['subject_id'];
                    $guides["$guideId"] = $guideName;
                }
            }else
            {
            	$owner = '';
            }

            if (empty($guides)) { // the file is an orphan--flag it!
                $tr = "<tr class=\"zebra oddrow\" id=\"item-$rowcount\">";
            } else {
                $tr = "<tr class=\"zebra\" id=\"item-$rowcount\">";
            }

            $file_list .= $tr . "<td><a class=\"showmedium\" href=\"$linky\">$shortName</a></td><td>$fileSizeKb kb</td><td>$owner</td><td>";

            foreach ($guides as $gid => $full) {
                $file_list .= "<a href='" . $CpanelPath . "guides/guide.php?subject_id=" . $gid . "'>" . $full . "</a><br />";
            }

            $file_list .= "</td>
            <td><a id=\"delete-$rowcount\" name=\"file-$rowcount-$location_hint-" . urlencode($shortName) . "\"><i class=\"fa fa-trash fa-lg\" alt=\"" . _("Delete Item") . "\"></i></a></td></tr>";
        }
        $rowcount++;
    }
} else {
    $file_list .="<tr><td colspan=\"5\">" . _("No files found.") . "</td></tr>";
}
$file_list .= '</table>';



///////////
// Record mgr
/////////////

$subs_option_boxes = getSubBoxes("", "", 1);

$dropdown_intro_text = _("Please check with the guide's owner before modifying");

$all_guides .= "
<form method=\"get\" action=\"manage_items.php\" name=\"form\">
<select name=\"subject_id\" size=\"1\">
<option value=\"\">" . _("-- Choose Guide --") . "</option>
$subs_option_boxes
</select>
<br /><br />
<input type=\"submit\" name=\"submit\" value=\"" . _("Submit") . "\" />
</form>";

$manage_all_box = "<p>" . _("Use this to organize which items are associated with a record, and thus displayed in 'All Items by Source' box.") . "</p>
$all_guides
<br />
<p><i class=\"fa fa-pencil fa-lg\" alt=\"" . _("Edit icon") . "\"></i> " . _("Note: You can also do this in a guide, by clicking on the pencil icon for an 'All Items by Source' box.") . "</p>
<div id=\"test_url\"></div>";

// Uploads Box //
$uploads_box = "<p>" . _("Highlighted items are orphans.  Pity the orphans!") . "</p>";

// Allow admin to see all
if ( isset( $_SESSION["admin"] ) && $_SESSION["admin"] == 1 ) {
    $uploads_box .= "<p>" . _("Admin Tip: add ?view=all to this page's URL to see all users' items.") . "</p>";
}

$uploads_box .= "<br />$file_list";

// Print out //

print "
<div class=\"pure-g\">
  <div class=\"pure-u-1-2\">  
  ";

makePluslet(_("Manage your Uploads"), $uploads_box, "no_overflow");

print "</div>"; // close pure-u-
print "<div class=\"pure-u-1-2\">";

makePluslet(_("Manage All Items by Source"), $manage_all_box, "no_overflow");

print "</div>"; // close pure-u-
print "</div>"; // close pure

include("../includes/footer.php");

////////////////////
// functions used in the non-find option
///////////////////
// two functions from http://php.net/manual/en/function.readdir.php
// used for recursively reading directories
function listdir($dir='.') {
    if (!is_dir($dir)) {
        return false;
    }

    $files = array();
    listdiraux($dir, $files);

    return $files;
}

function listdiraux($dir, &$files) {
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        $filepath = $dir == '.' ? $file : $dir . '/' . $file;
        if (is_link($filepath))
            continue;
        if (is_file($filepath))
            $files[] = $filepath;
        else if (is_dir($filepath))
            listdiraux($filepath, $files);
    }
    closedir($handle);
}

// end functions
?>
<script type="text/javascript">

    $(function (){

        ///////////////////////

        $('#submit').livequery('click', function() {

            // generate new url
            // http://www.ithacalibrary.com/research/delish_feed.php?label=Art%20History:%20New%20Books%202010-2011&tag=arthis1011,arthis1011plus&notes=yes&num=1
            var url_start = '<?php print $DelishPath; ?>';
            var label = $('#del_label').val();
            var tag = $('#del_tag').val();
            var folder = $('#del_folder').val();
            var notes = $('#del_notes').val();
            var completed_url = url_start + "?folder=' + folder";

            if (tag.length != 0) {
                completed_url += '&tag=' + tag;
            }
            if (label.length != 0) {
                completed_url += '&label=' + label;
            }

            if (notes == 'yes') {
                completed_url += '&notes=yes';
            } else {
                completed_url += '&notes=no';
            }

            var final_string = '<a target="_blank" href="' + completed_url + '">' + completed_url + '</a>';
            $('#test_url').html(final_string);

            return false;
        });


        //Next chunk of code is related to file management functions; not ready yet!
        $('a[id*=delete-]').livequery('click', function(event) {


            var delete_id = $(this).attr("id").split("-");
            var item_id = delete_id[1];

            var confirm_yes = "confirm-yes-" + item_id;

            $("#item-"+item_id).after("<div class=\"confirmer\"><?php print $rusure; ?>  <a id=\"" + confirm_yes + "\"><?php print $textyes; ?></a> | <a id=\"confirm-no-"+item_id+"\"><?php print $textno; ?></a></div>");

            return false;
        });


        $('a[id*=confirm-yes-]').livequery('click', function(event) {

            var delete_id = $(this).attr("id").split("-");
            var this_id = delete_id[2];

            var file_path = $('a[name*=file-'+this_id+'-]').attr("name").split("-");
            // eg   file-1-fckuserfiles-darbyheadshot(1).jpg
            var file_name = file_path[3];
            var folder_hint = file_path[2];

            // Remove the confirm zone, and the row from the table
            $(this).parent().remove();
            $("#item-"+this_id).remove();

            $(".feedback").load("helpers/guide_bits.php", {type: 'delete_file', path:file_name, folder_hint:folder_hint},
            function() {

                $(".feedback").fadeIn();
            });

            return false;
        });

        // Person doesn't wish to change/delete item; remove confirm zone.
        $('a[id*=confirm-no-]').livequery('click', function(event) {
            $(this).parent().remove();
            return false;
        });


    });


</script>
