<?php

/**
 *   @file index.php
 *   @brief Browse view of records / splash screen
 *
 *   @author adarby
 *   @date Nov, 2011
 */
use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\Dropdown;
use SubjectsPlus\Control\Record;
use SubjectsPlus\Control\LinkChecker;
    
$subcat = "records";
$subsubcat = "index.php";
$page_title = "Browse Items";

// init some vars
$atoz = "";
$letter = "";
$ctag = "";
$full_query = "";

include("../includes/header.php");

try {
    $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}

$results = "<p>" . _("Please select a letter or tag to browse.") . "</p>";

if (isset($_GET["ctag"])) {
    $alpha_id = $_GET["ctag"];
    $full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags  from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and ctags like '%$alpha_id%' order by title.title";
} elseif (!isset($_GET['letter'])) {
    $alpha_id = "A";
    //$full_query = "select distinct title, description, location, restrictions_id, title.title_id as 'this_record', eres_display, ctags  from title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions  and title like '$alpha_id%' order by title.title";
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
// Create the A-Z header

$alpha_query = "SELECT  distinct left(title,1) as 'initial' FROM  title, restrictions, location, location_title, source where title.title_id = location_title.title_id and location.location_id = location_title.location_id and restrictions_id = access_restrictions ORDER BY initial";

$alpha_result = MYSQL_QUERY($alpha_query);


while ($myletter = mysql_fetch_array($alpha_result)) {

    $atoz .="<a href=\""
            . "index.php?letter="
            . $myletter[0]
            . "\">"
            . $myletter[0]
            . "</a> &nbsp;";
}

$atoz .= "<a href=\"index.php?letter=all\">[all]</a>";

// gather ctags
//$current_ctags = explode("|", $this->_ctags);

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


if ($alpha_id) {

    $full_result = MYSQL_QUERY($full_query);

    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    if ($full_result) {
        while ($myrow = mysql_fetch_array($full_result)) {

            $label = $myrow[0];
            $url = $myrow[2];
            $blurb = $myrow[1];
            $id = $myrow[4];

            $row_colour = ($row_count % 2) ? $colour1 : $colour2;

            // weed out extraneous P tags
            $blurb = stripP($blurb);

            $results .= "<div class=\"record-results\" class=\"$row_colour\">\n
        <a href=\"record.php?record_id=$id\" class=\"record-label\">$label</a>\n";
            /* not being used 
        <span style=\"display: none;\" class=\"toggle\">$icons<br />\n
        $blurb<br />\n 
        <span class=\"smaller\">" . _("Location") . ": <a href=\"$url\">$url</a></span>\n
        </span>\n*/
        $results .= "</div>\n";

            $row_count++;
        }
    }
}
print "<br /><br />
<div class=\"ctag-list-box\">
    <div class=\"box\">
    <div align=\"center\">$atoz</div>
    <div align=\"center\" class=\"ctag_list\">$tag_list</div>
    <h2 align=\"center\">$alpha_id</h2>
    </div>
    <div class=\"box no_overflow\">$results</div>
</div>
<div class=\"new-record\">
    <div class=\"box\">
  <h2 class=\"bw_head\">" . _("New Record") . "</h2>
  
  <ol>
    <li>" . _("Make sure the item doesn't already exist!") . "</li>
    <li><a href=\"record.php\">" . _("Create new item") . "</a></li>
  </ol>
  </div>
</div>
";

include("../includes/footer.php");
?>
