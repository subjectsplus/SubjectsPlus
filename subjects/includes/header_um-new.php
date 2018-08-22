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
    <link rel="stylesheet" href="<?php print $AssetPath; ?>css/public/sp.css" type="text/css">

    <!-- Google Analytics and Tag Manager -->
    <?php
    global $google_analytics_ua;
    global $google_tag_manager;
    if( (isset($google_analytics_ua)) && (( !empty($google_analytics_ua))) ) {

        echo "<div id='google-analytics-ua' style='visibility: hidden;' data-uacode='{$google_analytics_ua}'></div>";
        echo "<div id='google_tag_manager' style='visibility: hidden;' data-tag-manager='{$google_tag_manager}'></div>";

        if( file_exists('includes/google-analytics-tag-manager.php') ) {
            include_once ('google-analytics-tag-manager.php');
        }
    }
    ?>
</head>
<body>

<!-- Vendor Scripts-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

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
    <div class="body-default">
        <main>