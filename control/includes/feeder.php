<?php

////////////////////////////////////////////////////////////////////////////////
//                         feeder.php
//        Selects which feed reader to use, and displays results
//        Includes either control/lastRSS.php or control/PGFeed.php
////////////////////////////////////////////////////////////////////////////////


if ($pgparser == 1) {
	
	// Uses PGFeed
	require ("PGFeed.php");
	
	// Create PGFeed object -- look in PGFeed file for deets on usage
	// Note that the default is now 2 hours for caching
	$rss = new PGFeed;
	$source = $feed;
	$rss->setOptions(1,3,0,7200);
	$rss->parse($source);
	$items = $rss->getItems();
	$channel = $rss->getChannel(); // gets data about the feed as a whole
	
	// Show last published articles (title, link, description)
	$web_feed .= "<ul class=\"smaller\">\n";
	
		foreach ($items as $item) {
			$web_feed .= "\t<li><a href=\"$item[link]\">".$item['title']."</a><br />".$item['description']."</li>\n";		
		}
		
	$web_feed .="</ul>\n<p class=\"smaller\">Source: <a href=\"$channel[link]\" class=\"smaller\">$channel[title]</a>\n";

		//print $items[0][source];
		
} else {
	
	// Uses LastRss
	require ("lastRSS.php");

	// Create lastRSS object
	$rss = new lastRSS;

	// Set cache dir and cache time limit (1200 seconds)
	// (don't forget to chmod cahce dir to 777 to allow writing)
	$rss->cache_dir = './temp';
	$rss->cache_time = 1200;

	// Try to load and parse RSS file
	if ($rs = $rss->get($feed)) {
		// Show last published articles (title, link, description)
		$web_feed .= "<ul class=\"smaller\">\n";
		foreach($rs['items'] as $item) {
			$web_feed .= "\t<li><a href=\"$item[link]\">".$item['title']."</a><br />".$item['description']."</li>\n";
		}
		$web_feed .="</ul>\n<p class=\"smaller\">Source: <a href=\"$rs[link]\" class=\"smaller\">$rs[title]</a>\n";
	} else {
		$web_feed .= "[Problem Connecting to RSS Feed]\n";
	}
}

?>