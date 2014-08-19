<?php


/*

PGFeed is a caching PHP parser for RSS and Atom feeds.  It uses PHP's
XMLReader functions and so will only run under PHP 5.

Use:

require "PGFeed.php";
$p = new PGFeed;

$source = "somefeed";
$p->parse($source);
$channel = $p->getChannel(); // gets data about the feed as a whole
echo $channel["title"];      // prints the title of the feed

$items = $p->getItems();     // gets news items
echo $items[0]["title"];     // prints the title of the first item

$p->dump();                  // prints out array structure of parse

There are four parameters that may optionally be set using the
setOptions() function.  These are:

1. strip - removes all markup from output.  Default is off (0).

2. returns - controls how many items are returned for a feed.  Default
   is 12.

3. error-reporting - controls whether error messages are visible.
   Default is off (0).

4. caching - Sets the time limit for caching.  Default is 7200 (2
   hrs.).  If you do not want the parser to cache, set this to NULL.

So,

$p->setOptions(1,3,0,86400);

would create a parser that strips markup, returns 3 items, does not
show error messages, and continues to use a cached copy of a feed for
24 hrs.

Finally, if you would like to customize the error message generated
when an exception is thrown, you can do so as follows:

$p->setErrorMessage("my custom error message");


===================================================================

Copyright 2008 Ron Gilmour
This software is distributed under the terms of the GNU General Public
License.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

===================================================================

*/


class PGFeed {

  private $source;
  private $strip;
  private $returns;
  private $report;
  private $cache;
  private $localfile;
  private $feedxml;
  private $feedLinks;
  private $feedCategories;
  private $feedAuthorsCount;
  private $itemLinks;
  private $itemCategories;
  private $reader;
  private $cachedCopy;
  private $cacheTime;
  private $age;
  private $now;
  private $cachedContents;
  private $name;
  private $isAtom;
  private $channelElements;
  private $isInItem;
  private $isInImage;
  private $channel;
  private $description;
  private $newDescription;
  private $item;
  private $items;
  private $feedElements;
  private $isInSource;
  private $linkAttributes;
  private $author;
  private $isInAuthor;
  private $feedAuthors;
  private $categoryAttributes;
  private $itemNumber;
  private $isInContributor;
  private $contributor;
  private $summary;
  private $newSummary;
  private $content;
  private $newContent;
  private $isInDescription;


  private function checkSource($source) {
    if (XMLReader::open($source)) {
      return true;
    } else {
      throw new Exception($this->errorMessage);
    }
  }


  function setOptions($strip, $returns, $report, $cache) {
    $this->strip = $strip;
    $this->returns = $returns;
    $this->report = $report;
    $this->cache = $cache;
  }


  function setErrorMessage($message) {
    $this->errorMessage = $message;
  }


  private function cacheFeed($source) {
    if (!(file_exists("pgcache"))) {
      mkdir("pgcache");
    }
    $localfile = "pgcache/" . urlencode($source);
    $feedxml = file_get_contents($source);
    
    $fp = fopen($localfile,"w");
    fwrite($fp, $feedxml);
    fclose($fp);
  }


  function parse($source) {

    if (!($this->report)) {
      error_reporting(0);
    }

    if (!(is_null($this->returns))) {
      $returns = $this->returns;
    } else {
      $returns = 12;
    }

    $feedLinks = -1;
    $feedCategories = -1;
    $feedAuthorsCount = -1;
    $itemLinks = -1;
    $itemCategories = -1;

    $reader = new XMLReader();

    $cachedCopy = "pgcache/" . urlencode($source);
    
    if (file_exists($cachedCopy)) {
      $cacheTime = filemtime($cachedCopy);
      $now = time();
      $age = $now - $cacheTime;
    }

    if ((file_exists($cachedCopy) && $this->cache && ($age > $this->cache)) || (!(file_exists($cachedCopy)))) {
      try {
	PGFeed::checkSource($source);
      }
      catch (Exception $e) {
	if ($this->report) {
	  echo 'Message: ' . $e->getMessage();
	}
	die();
      }
      if ($this->cache) {
	PGFeed::cacheFeed($source);
      }
      $reader->open($source);
    } else {
      $cachedContents = file_get_contents($cachedCopy);
      $reader->XML($cachedContents);
    }
    
  
    while ($reader->read()) {
      $name = $reader->name;

      if ($name == "feed" && $reader->nodeType == 1) {
	$isAtom = 1;
      }

      // RSS children of channel (except cloud, image, & textInput)
      else if (in_array("$name",$this->channelElements) && $reader->nodeType == 1 && !($isInItem) && !($isInImage) && !($isAtom)) {
	$reader->read();
	$this->channel["$name"] = $reader->value;
      }
      
      // RSS channel/cloud 
      else if ($name == "cloud" && $reader->nodeType == 1 && !($isAtom)) {
	foreach ($this->cloudAttributes as $att) {
	  if ($reader->moveToAttribute("$att")) {
	    $this->channel["cloud"]["$att"] = $reader->value;
	  }
	}
      }

      // RSS channel/image
      else if ($name == "image" && $reader->nodeType == 1 && !($isAtom)) {
	$isInImage = 1;
      }
      
      else if ($name == "image" && $reader->nodeType == 15 && !($isAtom)) {
	$isInImage = 0;
      }

      else if (in_array("$name",$this->imageElements) && $reader->nodeType == 1 && $isInImage && !($isAtom)) {
	$reader->read();
	$this->channel["image"]["$name"] = $reader->value;
      }

      // RSS channel/textInput
      else if ($name == "textInput" && $reader->nodeType == 1 && !($isAtom)) {
	$isInTextInput = 1;
      }

      else if ($name == "textInput" && $reader->nodeType == 15 && !($isAtom)) {
	$isInTextInput = 0;
      }

      else if (in_array("$name",$this->textInputElements) && $reader->nodeType == 1 && $isInTextInput && !($isAtom)) {
	$reader->read();
	$this->channel["textInput"]["$name"] = $reader->value;
      }


      // RSS item
      else if ($name == "item" && $reader->nodeType == 1 && !($isAtom)) {
	$isInItem = 1;
	$itemNumber++;
       	$item = array();
      }
      
      // RSS children of item (except description & enclosure)
      else if (in_array("$name",$this->itemElements) && $reader->nodeType == 1 && $isInItem && !($isAtom)) {
	$reader->read();
	$item["$name"] = $reader->value;
      }
      
      // RSS item/description
      else if ($name == "description" && $reader->nodeType == 1 && $isInItem && !($isAtom)) {
	$isInDescription = 1;
	$reader->read();
	$description = $reader->value;
	if ($this->strip) {
	  $newDescription = preg_replace("/<.+?>/","",$description);
	}
	else {
	  $newDescription = $description;
	}
	$item["description"] = $newDescription;
      }

      else if ($name == "description" && $reader->nodeType == 15) {
	$isInDescription = 0;
      }

      // RSS item/enclosure
      else if ($name == "enclosure" && $reader->nodeType == 1 && $isInItem && !($isAtom)) {
	foreach ($this->enclosureAttributes as $enatt) {
	  if ($reader->moveToAttribute("$enatt")) {
	    $item["enclosure"]["$enatt"] = $reader->value;
	  }
	}
      }
      
      // RSS item end tag
      else if ($name == "item" && $reader->nodeType == 15 && !($isAtom)) {
	$isInItem = 0;
	if ($itemNumber <= $returns) {
	  array_push($this->items,$item);
	}
      }

      // Atom
      // Atom children of feed (except title, link, generator, author, category)
      else if (in_array("$name",$this->feedElements) && $reader->nodeType == 1 && !($isInItem) && $isAtom) {
	$reader->read();
	$this->channel["$name"] = $reader->value;
      }

      // Atom title (feed or entry)
      else if ($name == "title" && $reader->nodeType == 1 && !($isInSource) && $isAtom) {
	if ($reader->moveToAttribute("type")) {
	  if ($isInItem) {
	    $item["titleAttributes"]["type"] = $reader->value;
	  } else {
	    $this->channel["titleAttributes"]["type"] = $reader->value;
	  }
	}
	$reader->read();
	if ($isInItem) {
	  $item["title"] = $reader->value;
	} else {
	  $this->channel["title"] = $reader->value;
	}
      }
      
      // Atom feed link
      else if ($name == "link" && $reader->nodeType == 1 && !($isInItem) && $isAtom) {
	$feedLinks++;
	foreach ($this->linkAttributes as $latt) {
	  if ($reader->moveToAttribute("$latt")) {
	    $this->channel["links"]["$feedLinks"]["$latt"] = $reader->value;
	  }
	}
      }

      // Atom generator
      else if ($name == "generator" && $reader->nodeType == 1 && $isAtom) {
	$reader->moveToAttribute("uri");
	$this->channel["generatorURI"] = $reader->value;
	$this->channel["link"] = $reader->value;
	$reader->read();
	$this->channel["$name"] = $reader->value;
      }
      
      // Atom author (feed and entry)
      else if ($name == "author" && $reader->nodeType == 1 && $isAtom) {
	$isInAuthor = 1;
	$author = array();
      }

      else if (in_array($name,$this->authorElements) && $reader->nodeType == 1 && $isInAuthor && $isAtom) {
	$reader->read();
	$author["$name"] = $reader->value;
      }

      else if ($name == "author" && $reader->nodeType == 15 && $isAtom) {
	$isInAuthor = 0;
	if ($isInItem) {
	  array_push($this->authors,$author);
	  $item["authors"] = $this->authors;
	  $this->authors = array();
	} else {
	  array_push($this->feedAuthors,$author);
	  $this->channel["authors"] = $this->feedAuthors;
	}

      }

      // Atom feed category
      else if ($name == "category" && $reader->nodeType == 1 && !($isInItem) && $isAtom) {
	$feedCategories++;
	foreach ($this->categoryAttributes as $cat) {
	  if ($reader->moveToAttribute("$cat")) {
	  $this->channel["categories"]["$feedCategories"]["$cat"] = $reader->value;
	  }
	}
      }

      // Atom feed rights
      else if ($name == "rights" && $reader->nodeType == 1 && !($isInItem) && $isAtom) {
	if ($reader->moveToAttribute("src")) {
	  $this->channel["rights"]["src"] = $reader->value;
	}
	if ($reader->moveToAttribute("type")) {
	  $this->channel["rights"]["type"] = $reader->value;
	}
	$reader->read();
	$this->channel["rights"]["rights"] = $reader->value;
      }

      // Atom entry
      else if ($name == "entry" && $reader->nodeType == 1 && $isAtom) {
	$isInItem = 1;
	$item = array();
	$itemNumber++;
	$itemLinks = -1;
	$itemCategories = -1;
      }

      else if ($name == "entry" && $reader->nodeType == 15 && $isAtom) {
	$isInItem = 0;
	if ($itemNumber <= $returns) {
	  array_push($this->items,$item);
	}
	$authors = array();
      }

      
      // Atom children of entry (only the simple ones)
      else if (in_array("$name",$this->entryElements) && $reader->nodeType == 1 && $isInItem && $isAtom) {
	$reader->read();
	$item["$name"] = $reader->value;
      }


      // Atom entry rights
      else if ($name == "rights" && $reader->nodeType == 1 && $isInItem && $isAtom) {
	if ($reader->moveToAttribute("src")) {
	  $item["rights"]["src"] = $reader->value;
	}
	if ($reader->moveToAttribute("type")) {
	  $item["rights"]["type"] = $reader->value;
	}
	$reader->read();
	$item["rights"]["rights"] = $reader->value;
      }


      // Atom entry link
      else if ($name == "link" && $reader->nodeType == 1 && $isInItem && $isAtom) {
	$itemLinks++;
	foreach ($this->linkAttributes as $latt2) {
	  if ($reader->moveToAttribute("$latt2")) {
	    $item["links"]["$itemLinks"]["$latt2"] = $reader->value;
	    $item["link"] = $item["links"][0]["href"];
	  }
	}
      }

      // Atom entry category
      else if ($name == "category" && $reader->nodeType == 1 && $isInItem && $isAtom) {
	$itemCategories++;
	foreach ($this->categoryAttributes as $catt) {
	  if ($reader->moveToAttribute("$catt")) {
	  $item["categories"]["$itemCategories"]["$catt"] = $reader->value;
	  }
	}
      }

      // Atom entry contributor
      else if ($name == "contributor" && $reader->nodeType == 1 && $isInItem && $isAtom) {
	$isInContributor = 1;
	$contributor = array();
      }

      else if (in_array($name,$this->authorElements) && $reader->nodeType == 1 && $isInContributor && $isAtom) {
	$reader->read();
	$contributor["$name"] = $reader->value;
      }

      else if ($name == "contributor" && $reader->nodeType == 15 && $isAtom) {
	$isInContributor = 0;
	array_push($this->contributors,$contributor);
	$item["contributors"] = $this->contributors;
      }

      // Atom entry source
      else if ($name == "source" && $reader->nodeType == 1 && $isAtom) {
	$isInSource = 1;
      }

      else if ($name == "source" && $reader->nodeType == 15 && $isAtom) {
	$isInSource = 0;
      }

      // Atom entry summary
      else if ($name == "summary" && $reader->nodeType == 1 && $isInItem && $isAtom) {
	if ($reader->moveToAttribute("src")) {
	  $item["sourceAttributes"]["src"] = $reader->value;
	}
	if ($reader->moveToAttribute("type")) {
	  $item["sourceAttributes"]["type"] = $reader->value;
	}
	$reader->read();
	$summary = $reader->value;
	if ($this->strip) {
	  $newSummary = preg_replace("/<.+?>/","",$summary);
	}
	else {
	  $newSummary = $summary;
	}
	$item["source"] = $newSummary;
      }

      // Atom entry content
      else if ($name == "content" && $reader->nodeType == 1 && $isInItem && $isAtom) {
	$isInDescription = 1;
 	if ($reader->moveToAttribute("src")) {
 	  $item["contentAttributes"]["src"] = $reader->value;
	}
	if ($reader->moveToAttribute("type")) {
	  $item["contentAttributes"]["type"] = $reader->value;
	}
	$reader->read();
	$content = $reader->value;
	if ($this->strip) {
	  $newContent = preg_replace("/<.+?>/","",$content);
	}
	else {
	  $newContent = $content;
	}
	$item["content"] = $newContent;
	$item["description"] = $newContent;
      }

      else if ($name == "content" && $reader->nodeType == 15 && $isInItem && $isAtom) {
	$isInDescription = 0;
      }

      // CDATA
      else if ($reader->nodeType == 4 && $isInDescription) {
	$cdata = $reader->value;
	if ($this->strip) {
	  $cdata = preg_replace("/<.+?>/","",$cdata);
	}
	$item["content"] = $cdata;
	$item["description"] = $item["content"];
      }

      $reader->close;
    }
  }
  
  function getChannel() {
    return $this->channel;
  }
  
  function getItems() {
    return $this->items;
  }
  
  function dump() {
    print "<pre>";
    print_r($this->channel);
    print "<br /><br />";
    print_r($this->items);
    print "</pre>";
  }

  
  function __construct() {
    // RSS lists
    $this->channelElements = array("title","link","description","language","copyright","managingEditor","webMaster","pubDate","lastBuildDate","category","generator","docs","ttl","rating","skipHours","skipDays");
    // cloud, image, and textInput omitted for special processing
    $this->itemElements = array("title","link","author","category","comments","guid","pubDate","source");
    // description and enclosure omitted for special processing
    $this->cloudAttributes = array("domain","port","path","registerProcedure","protocol");
    $this->imageElements = array("url","title","link","height","width","description");
    $this->textInputElements = array("title","description","name","link");
    $this->enclosureAttributes = array("url","length","type");

    // Atom lists
    $this->feedElements = array("id","updated","icon","logo","subtitle");
    // lots of stuff omitted for special processing
    $this->linkAttributes = array("rel","type","href","title","hreflang","length");
    $this->entryElements = array("id","published","updated");
    // pretty much everything omitted for special processing
    $this->authorElements = array("name","uri","email");
    $this->categoryAttributes = array("term","scheme","label");

    $this->channel = array();
    $this->items = array();
    $this->isInItem = 0;
    $this->strip = 0;
    $this->returns = 0;
    $this->cache = 7200;
    $this->isInImage = 0;
    $this->isInTextInput = 0;
    $this->isAtom = 0;
    $this->isInFeedAuthor = 0;
    $this->authors = array();
    $this->feedAuthors = array();
    $this->isInContributor = 0;
    $this->contributors = array();
    $this->isInSource = 0;
    $this->itemNumber = 0;
    $this->errorMessage = "I'm afraid I can't do that, Dave";
  }
  
}

?>