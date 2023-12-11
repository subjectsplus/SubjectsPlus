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

    <!-- SP Calder CSS -->
	<link rel="stylesheet" href="<?php print $AssetPath; ?>css/public/sp-calder.css?v=02082023-1" type="text/css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2LY97SP1NW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-2LY97SP1NW');
    </script>

    <!-- Google Analytics -->
    <?php
    global $google_analytics_ua;
    if( (isset($google_analytics_ua)) && (( !empty($google_analytics_ua))) ) {
        if( file_exists('includes/google-analytics-tracker.php') ) {
            include_once ('includes/google-analytics-tracker.php');
        }
    }
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-K7KW9DMD');</script>
    <!-- End Google Tag Manager -->
</head>
<body class="d-flex flex-column">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K7KW9DMD"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
