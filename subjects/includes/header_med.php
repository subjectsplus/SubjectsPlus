<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php print $page_title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="Description" content="<?php if (isset($description)) {print $description;} ?>" />
<meta name="Keywords" content="<?php if (isset($keywords)) {print $keywords;} ?>" />
<meta name="Author" content="" />
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/shared/pure-min.css">
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/shared/grids-responsive-min.css">
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/public/um.css">
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/public/um-med.css">

<link type="text/css" media="print" rel="stylesheet" href="<?php print $AssetPath; ?>css/public/um-print.css">
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/shared/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="https://calder.med.miami.edu/images/halfmoontabs.css">



<?php 
// Some constants, previously in the config.php

if ($_SERVER['HTTP_HOST'] != "localhost") {
    define("PATH_FROM_ROOT", "http://library.miami.edu");
    define("THEME_FOLDER", "http://library.miami.edu/wp-content/themes/");
    define("THEME_BASE_DIR", "http://library.miami.edu/wp-content/themes/umiami/");
} else {
    define("PATH_FROM_ROOT", "/richter/chc");
    define("THEME_BASE_DIR", "http://localhost/richter/wp-content/themes/umiami/");
}

define("PATH_TO_CHILD", PATH_FROM_ROOT . "/chc");

// Load our jQuery libraries + some css
if (isset($use_jquery)) { print generatejQuery($use_jquery);
}

if (!isset ($noheadersearch)) { 
    
    $search_form = '
            <div class="autoC" id="autoC">
                <form id="sp_admin_search" class="pure-form" method="post" action="' . getSubjectsURL() . 'search.php">
                <input type="text" placeholder="Search" autocomplete="off" name="searchterm" size="" id="sp_search" class="ui-autocomplete-input autoC"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                <input type="submit" name="submitsearch" id="topsearch_button" class="pure-button pure-button-topsearch" value="Go">
                </form>
            </div>    ';
} else {
    $search_form = '';
}

// We've got a variable for those who wish to keep the old styles
$v2styles = TRUE;
?>
    <?php
    global $google_analytics_ua;
    if( (isset($google_analytics_ua)) && (( !empty($google_analytics_ua))) ) {

        echo "<div id='google-analytics-ua' style='visibility: hidden;' data-uacode='{$google_analytics_ua}'></div>";

        if( file_exists('includes/google-analytics-tracker.php') ) {
            include_once ('google-analytics-tracker.php');
        }
    }
    ?>



</head>

<body>

<div id="wrap">

<div id="header-content"> 
    <div class="pure-g header-content">

        <div class="pure-u-1-2">
          <a href="http://calder.med.miami.edu/index.html"><img src="https://calder.med.miami.edu/images/calderheaderlogo.jpg" alt="Home Page" border="0" class="calder-logo" /></a>
        </div>
      
        <div class="pure-u-1-2 uhealth-area">
          <a href="http://umiamihospital.com/"><img src="https://calder.med.miami.edu/images/uhealth-300.jpg" alt="UMHealth System" border="0" class="uhealth-logo" /></a>
        </div>
        <span id="menu_button"><a class="pure-button pure-u-1 button-menu" href="#">Menu</a></span>

    </div> <!-- end pure-g -->

    <!-- NAV -->      
    <div class="pure-g">
        <div class="pure-u-1" id="spum_nav">              
              <ul class="nav" id="nav_menu">
                  <li><a href="http://calder.med.miami.edu/index.html">HOME</a></li>
                  <li><a href="http://calder.med.miami.edu/mission.html">MISSION</a></li>
                  <li><a href="http://calder.med.miami.edu/librarianask.html">ASK A LIBRARIAN</a></li>
                  <li><a href="http://calder.med.miami.edu/request_forms.html" rel="dropmenu1_e">FORMS</a></li>
                  <li><a href="http://calder.med.miami.edu/other_libraries.html#" rel="dropmenu2_e">OTHER LIBRARIES</a></li>
                  <li><a href="http://calder.med.miami.edu/department_and_staff_listings.html">STAFF LIST</a></li>
                  <li><a href="http://miami-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/myAccountMenu.do?vid=med&fromLink=gotoMyAccountUI">MY ACCOUNT</a></li>
                  <li><a href="http://spmed.library.miami.edu/subjects/talkback.php">PATRON SUGGESTIONS &amp; COMMENTS</a></li>
                </ul><!--end #nav_menu -->
        </div> <!-- end #spum_nav --> 
    </div>


    <!--1st drop down menu -->                                                   
      <div id="dropmenu1_e" class="dropmenudiv_e">
        <a href="http://calder.med.miami.edu/forms/bib-search.html">Bibliographic Search Request</a>
        <a href="http://calder.med.miami.edu/forms/e-journal_access_problem_form.html">E-Journal Access Problem</a>
        <a href="http://calder.med.miami.edu/forms/general_feedback.html">General Feedback</a>
         <a href="http://calder.med.miami.edu/forms/journal_photocopy_request_patron.html">Interlibrary Loan / <br />&nbsp;Journal Photocopy Request</a>
        <a href="http://calder.med.miami.edu/forms/request_purchase_subscription.html">Purchase / <br />&nbsp;Subscription Request Form</a>
        <a href="http://calder.med.miami.edu/forms/reference_question.html">Reference Question</a>
        <a href="http://calder.med.miami.edu/forms/classregistration2.html">Register for a Class!</a>
      </div>
                
        
        <!--2nd drop down menu -->                                                
      <div id="dropmenu2_e" class="dropmenudiv_e" style="width: 320px;">
        <a href="http://bascompalmer.org/physician-resources/library-services">Mary and Edward Norton Library of Ophthalmology</a>
        <a href="http://library.miami.edu/">Otto G. Richter Library</a>
        <a href="http://library.miami.edu/rsmaslib/">Rosenstiel School of Marine and Atmospheric <br />&nbsp;Science Library</a>
        <a href="http://www.law.miami.edu/library/">University of Miami Law Library</a>
        <a href="http://calder.med.miami.edu/umhlibrary">University of Miami Hospital Library</a>        
      </div>

    

    <!-- PAGE HEADER-->
    <div class="pure-g">  
        <div class="pure-u-1">
          <h1><?php print $page_title; ?></h1>
        </div>
    </div> 

</div> <!-- end #header-content -->


<!--MAIN CONTENT AREA-->
<div class="wrapper-full">

<div class="pure-g">
    
        <div class="pure-u-1">

        <div id="body_inner_wrap">
