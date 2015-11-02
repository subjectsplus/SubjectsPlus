<?php

    /**
     *   @file feedme.php
     *   @brief Decide what to do with an RSS-like request (could be flickr, delicious, rss)
     *
     *   @author adarby, jlittle
     *   @date
     *   @todo
     */
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    include("../../control/includes/autoloader.php");

    use SubjectsPlus\Control\PGFeed;

    $source = "";

    if (isset($_POST["type"])) {

        if ($_POST["type"] == "RSS") {
            $source = $_POST["feed"];

        }
        // tweak the source if it's a delicious feed
        if ($_POST["type"] == "Delicious") {

            // http://feeds.delicious.com/v2/{format}/{username}/{tag[+tag+...+tag]}
            $source = "http://feeds.delicious.com/v2/rss/" . $source . "";

        }

        if ($_POST["type"] == "Tumblr") {

            $source = "http://" . $_POST["feed"] . ".tumblr.com/rss";

        }
    }

    if ($_POST["count"]) {
        $count = $_POST["count"];
    } else {
        $count = 5;
    }

    $show_desc = $_POST["show_desc"];
    $show_feed = $_POST["show_feed"];

    //print "show feed = $show_feed; count = $count; show desc = $show_desc";
    $p = new PGFeed;

    $p->setOptions(1, $count, 0, 7200);
    $p->parse($source);
    $channel = $p->getChannel(); // gets data about the feed as a whole
    $items = $p->getItems();     // gets news items
    //$text = tr("vFeedSource", "source:");
    $our_feed .= "source: <a href=\"$channel[link]\">$channel[link]</a>";

    switch ($_POST["type"]) {

        case "Delicious":

            print "<ul class='rss-list'>";

            // prints the linked title for each item
            foreach ($items as $i) {
                print_r($items);
                echo "<li><a href=\"" . $i['link'] . "\" target=\"_blank\">" . $i['title'] . "</a>";
                if ($show_desc == 1) {
                    print "<br />" . $i['description'];
                }
                print "</li>";

            }
            print "</ul>\n";
            break;
            //case "rss":
        case "Tumblr":
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

            break;


        default:
            //echo $source;
            print "<ul class='rss-list'>";
            // prints the linked title for each item
            foreach ($items as $i) {
                print "<li class='rss-list-item'><a href=\"" . $i['link'] . "\" target=\"_blank\">" . $i['title'] . "</a>";
                if ($show_desc == 1) {
                    print "<br />" . $i['description'];
                }
                print "</li>";
            }
            print "</ul>\n";

            break;
    }

    if ($show_feed == 1) {
        print "<p>$our_feed</p>";
    }
    ?>