<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no">

    <title><?php print $page_title; ?> | University of Miami Libraries</title>

    <meta name="description" content="<?php if (isset($description)) {print $description;} ?>">
    <meta name="author" content="University of Miami Libraries">
    <meta name="keywords" content="<?php if (isset($keywords)) {print $keywords;} ?>">

    <!-- SP CSS -->
    <link rel="stylesheet" href="<?php print $AssetPath; ?>css/public/sp-slim.css" type="text/css">

    <!-- Google Analytics and Tag Manager -->
    <?php
    global $google_analytics_ua;
    global $google_tag_manager;
    if( isset($google_analytics_ua) && !empty($google_analytics_ua) ) {
        if( file_exists('includes/google-analytics-tag-manager.php') ) {
            include_once ('includes/google-analytics-tag-manager.php');
        }
    }
    ?>

</head>

<body>

<!-- Vendor Scripts-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=<?php print $google_tag_manager; ?>"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!--SP jQuery legacy-->
    <?php
    // Load our jQuery libraries + some css
    if (isset($use_jquery)) { print generatejQuery($use_jquery); }

    // We've got a variable for those who wish to keep the old styles
    $v2styles = TRUE;
    ?>

<div class="c-offcanvas-content-wrap">

    <!-- Load Jekyll-built site header (UM Sliver, Site Header)-->
    <?php include("includes/jekyll-site-header.php"); ?>

    <!-- Main Content-->
    <div class="body-slim">
        <?php
        $header_type = isset($header_type) ? $header_type : null;
        if (is_null($header_type)
            && !strpos($_SERVER['PHP_SELF'], 'subjects/guide.php')
            && !strpos($_SERVER['PHP_SELF'], 'subjects/usearch-problem-report.php')):
            ?>
            <div class="key-messaging-mobile d-lg-none"><!--Key messaging mobile--></div>
        <?php endif; ?>
        <main>
