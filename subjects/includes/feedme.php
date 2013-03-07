<?php

/**
 *   @file feedme.php
 *   @brief Decide what to do with an RSS-like request (could be flickr, delicious, rss)
 *
 *   @author adarby
 *   @date 
 *   @todo
 */

$source = $_POST["feed"];
// tweak the source if it's a delicious feed
if ($_POST["type"] == "Delicious") {
    // http://feeds.delicious.com/v2/{format}/{username}/{tag[+tag+...+tag]}
    $source = "http://feeds.delicious.com/v2/rss/" . $source . "";
}
//print_r($_POST);
if ($_POST["count"]) {
    $count = $_POST["count"];
} else {
    $count = 5;
}

$show_desc = $_POST["show_desc"];
$show_feed = $_POST["show_feed"];

include("../../control/includes/classes/PGFeed.php");

//print "show feed = $show_feed; count = $count; show desc = $show_desc";
$p = new PGFeed;

$p->setOptions(1, $count, 0, 7200);
$p->parse($source);
$channel = $p->getChannel(); // gets data about the feed as a whole
$items = $p->getItems();     // gets news items
//$text = tr("vFeedSource", "source:");
$our_feed .= "source: <a href=\"$channel[link]\">$channel[link]</a>";

switch ($_POST["type"]) {

    case "Flickr":

        // displays linked thumbnails from a Flickr feed
        foreach ($items as $i) {
            echo "<div style=\"display: inline; margin-right: 5px; \"<a href=\"" . $i['link'] . "\" target=\"_blank\"><img src=\"" .
            $i["thumbnailURL"] . "\" height=\"" .
            $i['thumbnailHeight'] . "\" width=\"" .
            $i['thumbnailWidth'] . "\" title=\"$i[title]\" border=\"0\"/></a></div>\n";
        }
        break;

    case "Delicious":

        print "<ul>";
        // prints the linked title for each item
        foreach ($items as $i) {
            echo "<li><a href=\"" . $i['link'] . "\" target=\"_blank\">" . $i['title'] . "</a>";
            if ($show_desc == 1) {
                print "<br />" . $i['description'];
            }
            print "</li>";
        }
        print "</ul>\n";
        //$p->dump();
        break;
    //case "rss":
    default:

        print "<ul>";
        // prints the linked title for each item
        foreach ($items as $i) {
            print "<li><a href=\"" . $i['link'] . "\" target=\"_blank\">" . $i['title'] . "</a>";
            if ($show_desc == 1) {
                print "<br />" . $i['description'];
            }
            print "</li>";
        }
        print "</ul>\n";
        //$p->dump();
        break;
}

if ($show_feed == 1) {
    print "<p>$our_feed</p>";
}
?>