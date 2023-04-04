<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php print $page_title; ?> | Louis Calder Memorial Library</title>

    <meta name="description" content="<?php if (isset($description)) {print $description;} ?>">
    <meta name="author" content="Louis Calder Memorial Library | University of Miami Libraries">
    <meta name="keywords" content="<?php if (isset($keywords)) {print $keywords;} ?>">

    <!-- SP CSS -->
	<link rel="stylesheet" href="<?php print $AssetPath; ?>css/public/sp-calder.css?v=02082023-1" type="text/css">
	<link rel="stylesheet" href="<?php print $AssetPath; ?>css/public/sp-calder-temp.css" type="text/css">

    <!-- Google Analytics -->
    <?php
    global $google_analytics_ua;
    if( (isset($google_analytics_ua)) && (( !empty($google_analytics_ua))) ) {
        if( file_exists('includes/google-analytics-tracker.php') ) {
            include_once ('includes/google-analytics-tracker.php');
        }
    }
    ?>
</head>
<body class="d-flex flex-column">

<!-- Vendor Scripts-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<!--SP jQuery legacy-->
<?php
// Load our jQuery libraries + some css
if (isset($use_jquery)) { print generatejQuery($use_jquery); }

// We've got a variable for those who wish to keep the old styles
$v2styles = TRUE;
?>

<!-- Load Calder site header and nav-->
<?php include("themes/calder/calder-site-header.php"); ?>

<!-- Main Content-->
<main>
