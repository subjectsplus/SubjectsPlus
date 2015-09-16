<?php
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Stats\Stats;

header('Content-Type: image/gif');
header("Cache-Control: private, no-cache, no-cache=Set-Cookie, proxy-revalidate");
header("Expires: Wed, 11 Jan 2000 12:59:00 GMT");
header("Last-Modified: Wed, 11 Jan 2006 12:59:00 GMT");
header("Pragma: no-cache");

echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

$db = new Querier;
$stats = new Stats($db);

if(isset($_SERVER['HTTP_REFERER'])) {
	$stats->setHttpReferer($_SERVER['HTTP_REFERER']);
} else {
	$stats->setHttpReferer("Referer Unavailable");
}

if(isset($_SERVER['REMOTE_ADDR'])) {
	$stats->setRemoteAddress($_SERVER['REMOTE_ADDR']);
} 

if(isset($_GET['page_title'])) {
	$stats->setPageTitle($_GET['page_title']);
}

if(isset($_SERVER['HTTP_USER_AGENT'])) {
	$stats->setUserAgent($_SERVER['HTTP_USER_AGENT']);
}

if(isset($_GET['subject'])) {
	$stats->setSubjectShortForm($_GET['subject']);
}

if(isset($_GET['event_type'])) {
	$stats->setEventType($_GET['event_type']);
} else {
	$stats->setEventType("view");
	
}

if(isset($_GET['tab_name'])) {
	$stats->setTabName($_GET['tab_name']);
}

if(isset($_GET['link_url'])) {
	$stats->setLinkUrl($_GET['link_url']);
}

$stats->saveStats();
