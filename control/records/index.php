<?php

/**
 *   @file index.php
 *   @brief Browse view of records / splash screen
 *
 *   @author adarby
 *   @date Nov, 2011; last mod dec 2014
 */

use SubjectsPlus\Control\Dropdown;
use SubjectsPlus\Control\Record;
use SubjectsPlus\Control\LinkChecker;
use SubjectsPlus\Control\Querier;

$subcat = "records";
$subsubcat = "index.php";
$page_title = "Browse Items";

// init some vars
$atoz = "";
$letter = "";
$ctag = "";
$full_query = "";

include("../includes/header.php");

$db = new Querier;

// Where to start?
// Choose initial letter to display

$alpha_query = "SELECT  distinct left(title,1) as 'initial' FROM  title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions ORDER BY initial";

$alpha_result = $db->query($alpha_query);

$count = 0;
$firstletter = "A";
    
foreach ($alpha_result as $myletter) {

	if (isset($myletter[0][0])) {
	
    if ($count == 0) { $firstletter = $myletter[0][0];}

    $atoz .="<a href=\""
            . "index.php?letter="
            . $myletter[0][0]
            . "\">"
            . $myletter[0][0]
            . "</a> &nbsp;";

    $count++;
    
	}
}

$atoz .= "<a href=\"index.php?letter=all\">[all]</a>";

// end A-Z header for now

$results = "<p>" . _("Please select a letter or tag to browse.") . "</p>";

if (isset($_GET["ctag"])) {
    $alpha_id = $_GET["ctag"];
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags  from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and ctags like '%$alpha_id%' order by title.title";
} elseif (!isset($_GET['letter'])) {
    $alpha_id = $firstletter;
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags  from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and title like '$firstletter%' order by title.title";
    //$alpha_id = FALSE;
} elseif ($_GET['letter'] == "all") {
    $alpha_id = "All Records";
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions order by title.title";
} elseif ($_GET['letter'] == "restricted") {
    $alpha_id = "Restricted Items";
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions and restrictions_id != '1' order by title.title";
} elseif ($_GET['letter'] == "unrestricted") {
    $alpha_id = "Unrestricted Items";
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions and restrictions_id = '1' order by title.title";
} else {
    $alpha_id = $_GET['letter'];
    //$alpha_id = FALSE;
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and title like '$alpha_id%' order by title.title";
}

// print $full_query;

// Now we continue to make our 
// gather ctags

$tag_list = "<span class=\"";
if (isset($_GET["letter"]) && $_GET["letter"] == "restricted") {
    $tag_list .= "ctag-on";
} else {
    $tag_list .= "ctag-off";
}
$tag_list .= "\"><a href=\"index.php?letter=restricted\">restricted</a></span>
<span class=\"";
if (isset($_GET["letter"]) && $_GET["letter"] == "unrestricted") {
    $tag_list .= "ctag-on";
} else {
    $tag_list .= "ctag-off";
}
$tag_list .= "\"><a href=\"index.php?letter=unrestricted\">free</a></span>";

// init the ctag
$selected_ctag = "";

if (isset($_GET["ctag"])) {
  $selected_ctag = $_GET["ctag"];
}

// loop through the ctags as defined in the config.php file
foreach ($all_ctags as $value) {
    if ($value == $selected_ctag) {
        $tag_class = "ctag-on";
    } else {
        $tag_class = "ctag-off";
    }

    $tag_list .= " <span class=\"$tag_class\"><a href=\"index.php?ctag=$value\">$value</a></span>";
}


// Output our header text
//print $intro;


if ($alpha_id & $full_query) {

    
    $full_result = $db->query($full_query);

    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    if ($full_result) {
        foreach ($full_result as $myrow) {

            $label = $myrow[0];
            $url = $myrow[2];
            $blurb = $myrow[1];
            $id = $myrow[4];

            $row_colour = ($row_count % 2) ? $colour1 : $colour2;

            // weed out extraneous P tags
            $blurb = stripP($blurb);

            $results .= "<div class=\"record-results $row_colour\">&nbsp;&nbsp;<i class=\"fa fa-star\"></i> <a href=\"record.php?record_id=$id\" class=\"record-label\">$label</a>\n";
   
        $results .= "</div>\n";

            $row_count++;
        }
    }
}

// let's put it together

$letter_header_body = "<div align=\"center\" style=\"font-size: 1.2em;\">$atoz</div>\n
<div align=\"center\" class=\"ctag_list\">$tag_list</div>
<h2 align=\"center\">$alpha_id</h2>
$results

";
 $new_record_body = " 
  <ol>
    <li>" . _("Make sure the item doesn't already exist!") . "</li>
    <li><a href=\"record.php\">" . _("Create new item") . "</a></li>
  </ol>";
print "
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">  
  ";
makePluslet(_("Browse Records"), $letter_header_body, "no_overflow");

print "</div>"; // close pure-u-2-3
print "<div class=\"pure-u-1-3\">";

makePluslet(_("New Record"), $new_record_body, "no_overflow");

print "</div>"; // close pure-u-1-3
print "</div>"; // close pure-g


include("../includes/footer.php");
?>
