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

$azRange = range('A', 'Z');
$azRange[] = "Num";
$azRange[] = "All";

foreach ($alpha_result as $myletter) {

    if (isset($myletter[0][0])) {

        $upperCase = strtoupper($myletter[0][0]);

        if ($count == 0) {
            $firstletter = $upperCase;
        }

        if (in_array($upperCase, $azRange)) {
            $atoz .="<a href=\""
                . "index.php?letter="
                . $upperCase
                . "\">"
                . $upperCase
                . "</a> &nbsp;";

            $count++;
        }
    }
}

$atoz .= "<a href=\"index.php?letter=Num\">Num</a>&nbsp;";
$atoz .= "<a href=\"index.php?letter=all\">[all]</a>";

// end A-Z header for now

$results = "<p>" . _("Please select a letter or tag to browse.") . "</p>";

if (isset($_GET["ctag"])) {
    $alpha_id = $_GET["ctag"];
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and ctags like '%$alpha_id%' order by title.title";
} elseif (!isset($_GET['letter'])) {
    $alpha_id = $firstletter;
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status  from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and title like '$firstletter%' order by title.title";
    //$alpha_id = FALSE;
} elseif ($_GET['letter'] == "Num") {
    $alpha_id = "Num";
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions and title REGEXP '^[[:digit:]]'order by title.title";
}elseif ($_GET['letter'] == "all") {
    $alpha_id = "All Records";
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions order by title.title";
} elseif ($_GET['letter'] == "restricted") {
    $alpha_id = _("Restricted Items");
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions and restrictions_id != '1' order by title.title";
} elseif ($_GET['letter'] == "unrestricted") {
    $alpha_id = _("Unrestricted Items");
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions and restrictions_id = '1' order by title.title";
} elseif ($_GET['letter'] == "az") {
    $alpha_id = _("A-Z List Items");
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions and eres_display = 'Y' order by title.title";    
} else {
    $alpha_id = $_GET['letter'];
    //$alpha_id = FALSE;
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags, record_status from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and title like '$alpha_id%' order by title.title";
}

// print $full_query;

// Now we continue to make our 
// gather ctags

$tag_list = "<span class=\"";
if (isset($_GET["letter"]) && $_GET["letter"] == "az") {
    $tag_list .= "ctag-on";
} else {
    $tag_list .= "ctag-off";
}
$tag_list .= "\"><a href=\"index.php?letter=az\">" . _("A-Z List") . "</a></span>
<span class=\"";
if (isset($_GET["letter"]) && $_GET["letter"] == "restricted") {
    $tag_list .= "ctag-on";
} else {
    $tag_list .= "ctag-off";
}
$tag_list .= "\"><a href=\"index.php?letter=restricted\">" . _("Restricted") . "</a></span>
<span class=\"";
if (isset($_GET["letter"]) && $_GET["letter"] == "unrestricted") {
    $tag_list .= "ctag-on";
} else {
    $tag_list .= "ctag-off";
}
$tag_list .= "\"><a href=\"index.php?letter=unrestricted\">" . _("Free") . "</a></span>";

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
    $record_count = count($full_result);

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

            if($myrow['eres_display']  == 'Y') {
                $az_star = "<i class=\"fa fa-star\"></i>";
            } else {
                $az_star = "<i class=\"fa fa-star-o\"></i>";
            }

            // added v4.1
            $record_status = "";

            if (isset($myrow['record_status']) && $myrow['record_status'] != "Active") {
                 $record_status = " <span style=\"margin-left: 1em;\" class=\"smallgrey\">" . $myrow['record_status'] . "</span> ";
             }

            $record_url = $myrow['location'];
            $link_tooltip = '<span style="float:right;" class="tooltip_wrapper"><a href="'.$record_url.'" target="_blank"> <i class="fa fa-link"></i> <span class="tooltip">'.$record_url.'</span></a></span>';

            // weed out extraneous P tags
            $blurb = stripP($blurb);

            $results .= "<div class=\"record-results $row_colour\">&nbsp;&nbsp; $az_star  <a href=\"record.php?record_id=$id\" class=\"record-label\" title=\"$label\">$label</a> $record_status $link_tooltip\n";
   
        $results .= "</div>\n";

            $row_count++;
        }
    }
}

// let's put it together

$letter_header_body = "<div align=\"center\" style=\"font-size: 1.2em;\">$atoz</div>\n
<div align=\"center\" class=\"ctag_list\">$tag_list</div>
<h2 align=\"center\">$alpha_id</h2>
<p>Record count: $record_count</p>
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

makePluslet(_("New AZRecord"), $new_record_body, "no_overflow");

print "</div>"; // close pure-u-1-3
print "</div>"; // close pure-g


include("../includes/footer.php");
?>
