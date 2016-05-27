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

<link rel="stylesheet" type="text/css" href="http://calder.med.miami.edu/images/halfmoontabs.css">

<img src="<?php print $PublicPath; ?>/track.php?subject=<?php echo scrubData($_GET['subject']); ?>&page_title=<?php $page_title; ?>"/>


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
                <input type="submit" alt="Search" name="submitsearch" id="topsearch_button" class="pure-button pure-button-topsearch" value="Go">
                </form>
            </div>    ';
} else {
    $search_form = '';
}

// We've got a variable for those who wish to keep the old styles
$v2styles = TRUE;
?>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-15217512-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script>
$(document).ready(function() {

$("body img:first").hide();
	

$('body').on('click','.ui-tabs-anchor' , function() { 
     var tab_name = $(this).text();

	$.get("<?php print $PublicPath; ?>/track.php?subject=<?php echo scrubData($_GET['subject']); ?>&page_title=<?php echo scrubData($page_title); ?>&event_type=tab_click&tab_name=" + tab_name, function(data) {

	console.log("Tracking tab click");
	});

	
});

$('a').each(function(){
	$(this).addClass('track-me');
});

$('body').on('click', '.track-me', function() {
	$.get('<?php print $PublicPath; ?>/track.php', {'event_type':'link', 'link_url':$(this).attr('href'),'subject': "<?php echo scrubData($_GET['subject']); ?>" });	
});

});

</script>

</head>

<body>
<div id="wrap">

<div id="header-content"> 
    <div class="pure-g header-content">

        <div class="pure-u-1-2">
          <a href="http://calder.med.miami.edu/index.html"><img src="http://calder.med.miami.edu/images/calderheaderlogo.jpg" alt="Home Page" border="0" class="calder-logo" /></a>
        </div>
      
        <div class="pure-u-1-2">
          <a href="http://umiamihospital.com/"><img src="http://calder.med.miami.edu/images/uhealth-300.jpg" alt="UMHealth System" border="0" class="uhealth-logo" /></a>
          
        </div>

        <span id="menu_button"><a class="pure-button pure-u-1 button-menu" href="#">Menu</a></span>

        <!--<div class="pure-u-1 pure-u-md-1-5 visible-desktop">
            <img src="http://library.miami.edu/chc/wp-content/themes/um-chc/images/question_orange.png" alt="ask a librarian" />
            <span class="header-text"><a href="http://library.miami.edu/ask-a-librarian/">Ask a Librarian</a></span>
        </div> 

        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">
          
        </div>-->

    </div> <!-- end pure-g -->

    <!-- NAV -->      
    <div class="pure-g">
        <div class="pure-u-1" id="spum_nav">              
              <ul class="nav" id="nav_menu">

                      <!--VISIT-->
                      <li class="mega"><a href="#">VISIT</a>
                          <!-- begin visit mega menu -->
                            <div class="mega_child mega-md mega-left">                                 
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/hoursdirections">Hours &amp; Directions</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/planyourvisit">Plan Your Visit</a></li>
                                    <li class="last"><a  href="<?php print PATH_TO_CHILD; ?>/instruction">Instruction, Tours, &amp; Room Use</a></li>
                                  </ul>
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/events/">Programs &amp; Events</a></li>
                                    <li class="last"><a  href="<?php print PATH_TO_CHILD; ?>/pavilion">The Robert C. Goizueta Pavilion</a></li>
                                  </ul>
                                  
                                  <div class="mega_more">See also <a href="http://www.library.miami.edu/specialcollections/">Special Collections</a>, <a href="http://www.library.miami.edu/universityarchives/">Archives</a></div>
                            </div>
                      </li>

                      <!--COLLECTIONS-->
                      <li class="mega"><a href="#">COLLECTIONS</a>
                            <div class="mega_child mega-md mega-left">
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/collections">Overview</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/collections/digitalcollections/">Digital Collections</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/collections/books/">Books</a></li>
                                    <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/collections/periodicals/">Periodicals</a></li>
                                  </ul>
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/collections/archivalmaterials/">Archival &amp; Manuscript Materials</a></li>
                                    <li><a href="http://library.miami.edu/oral-histories/">Oral Histories</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/searchtools">Search Tools</a></li>
                                    <li class="last"><a  href="http://libguides.miami.edu/chc">Research Guides</a></li>
                                  </ul>
                            </div>
                      </li>

                      <!--RESEARCH-->                      
                      <li class="archives_visitor mega"><a href="#">RESEARCH</a>
                            <div class="mega_child mega-md mega-left">
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/fellows">Fellowships</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/scholars">Undergraduate Scholars</a></li>
                                    <li class="last"><a href="http://libguides.miami.edu/chc">Research Tips</a></li>
                                  </ul>
                                  <ul>
                                    <li><a href="<?php print PATH_FROM_ROOT; ?>/photocopies-digital-reproductions/">Rights &amp; Reproductions</a></li>
                                    <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/scholarlypublications/">Scholarly Publications</a></li>
                                  </ul>
                            </div>
                      </li>

                      <!--ABOUT US-->
                      <li class="archives_visitor mega"><a href="#">ABOUT US</a>
                            <div class="mega_child mega-md mega-left">
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/contacts/">Contacts</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/missionhistory/">Mission &amp; History</a></li>
                                    <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/grantshistory/">Grants History</a></li>
                                  </ul>
                                  <ul>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/makeagift/">Make a Gift</a></li>
                                    <li><a href="<?php print PATH_TO_CHILD; ?>/amigos/">Join the Amigos</a></li>
                                    <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/followchc/">Follow CHC</a></li>
                                  </ul>
                            </div>
                      </li>

                      <!--ACCOUNTS-->
                      <li class="login mega last-child" rel="accounts"><a href="http://aeon.library.miami.edu/" class="nav_highlight">Your Research Account</a></li>
                    

                </ul><!--end #nav_menu -->
        </div> <!-- end #spum_nav --> 
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
