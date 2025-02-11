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

// HTTP Referer
$http_referer = isset($_SERVER['HTTP_REFERER']) ? scrubData($_SERVER['HTTP_REFERER'], 'url') : "Referer Unavailable";
$stats->setHttpReferer($http_referer);

// Remote Address
$remote_addr = isset($_SERVER['REMOTE_ADDR']) ? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) : null;
$stats->setRemoteAddress($remote_addr);

// Page Title
$page_title = isset($_GET['page_title']) ? scrubData($_GET['page_title'], 'text') : null;
$stats->setPageTitle($page_title);

// User Agent
$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? scrubData($_SERVER['HTTP_USER_AGENT'], 'text') : null;
$stats->setUserAgent($user_agent);

// Subject
$subject = isset($_GET['subject']) ? scrubData($_GET['subject'], 'text') : null;
$stats->setSubjectShortForm($subject);

// Event Type
$event_type = isset($_GET['event_type']) ? scrubData($_GET['event_type'], 'text') : "view";
$stats->setEventType($event_type);

// Tab Name
$tab_name = isset($_GET['tab_name']) ? scrubData($_GET['tab_name'], 'text') : null;
$stats->setTabName($tab_name);

// Link URL, Title, In Tab, In Pluslet
if(isset($_GET['link_url'])) {
    $link_url = scrubData($_GET['link_url'], 'url');
    $link_title = isset($_GET['link_title']) ? scrubData($_GET['link_title'], 'text') : null;
    $in_tab = isset($_GET['in_tab']) ? filter_var($_GET['in_tab'], FILTER_VALIDATE_INT) : null;
    $in_pluslet = isset($_GET['in_pluslet']) ? filter_var($_GET['in_pluslet'], FILTER_VALIDATE_INT) : null;

    $stats->setLinkUrl($link_url);
    $stats->setLinkTitle($link_title);
    $stats->setInTab($in_tab);
    $stats->setInPluslet($in_pluslet);
}

$stats->saveStats();
