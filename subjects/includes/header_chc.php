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
<!-- <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700|Open+Sans:400,700|Roboto:400,700|Lato:400,700|Oswald|Raleway:400,700|Ubuntu:400,700' rel='stylesheet' type='text/css'> -->
<link type="text/css" media="print" rel="stylesheet" href="<?php print $AssetPath; ?>css/public/um-print.css">
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/shared/font-awesome.min.css">

<img src="<?php print $PublicPath; ?>/track.php?subject=<?php echo scrubData($_GET['subject']); ?>&page_title=<?php $page_title; ?>"/>


<?php 
// Some constants, previously in the config.php

if ($_SERVER['HTTP_HOST'] != "localhost") {
    define("PATH_FROM_ROOT", "");
    define("THEME_FOLDER", "http://library.miami.edu/wp-content/themes/");
    define("THEME_BASE_DIR", "http://library.miami.edu/wp-content/themes/um-chc/");
} else {
    define("PATH_FROM_ROOT", "/richter/chc");
    define("THEME_BASE_DIR", "http://localhost/richter/wp-content/themes/um-chc/");
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

<style>
/* Some overrides for CHC */

a:link {
    color: #f1722e;
    text-decoration: none;
}
a:hover {text-decoration: underline;}

.pluslet_body a, .pluslet_body a:link {
    color: #f1722e;
    text-decoration: underline;
}

h1 {
    background-image: url("http://library.miami.edu/chc/wp-content/themes/um-chc/images/h1_bg_blue-blue.png");
    background-color: #cee8eb !important;
}

.print-img-tabs, .print-img-tabs:hover {
    background-image: url("../assets/images/printer-chc.png");
    border: 2px solid #f1722e;
}

 #nav_menu li.last-child a:hover {
        color: #ffffff !important;
}

a.nav_highlight:hover {background-color: #999 !important;}

div.mega_child.mega-md {width: 400px !important;}

#spum_nav a:hover,
#spum_nav a:active  {
    color: #e1e1db !important;
}

#spum_nav .mega_child li a:hover {
    color: #fff !important;
}

#nav_menu li a.selected_href {
        color: #ffffff;
    }

#searchzone {
    background-color: #dfdfdf;
    border: 1px solid #f27f41;
}

#searchy {
    background-color: #dfdfdf;
}

#topsearch_button2 {
    background: url("http://library.miami.edu/wp-content/themes/umiami/images/search_button_bg.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
}

#search_options {
    background-color: #dfdfdf;
    border-bottom: 1px solid #c3c3c3;    
    border-left: 1px solid #c3c3c3;
    border-right: 1px solid #c3c3c3;    
}

#search_options li:hover, #search_options li.active {
    background-color: #999;
}

#search_options li {
    border-bottom: 1px solid #c3c3c3;
}

#menu_button {
    display: block;
    float: right; 
    text-align: right; 
    margin-top: 10px;  
}

#social_media_accounts ul li a { color: rgba(51,51,51,0.7) !important;}
#social_media_accounts ul li a:hover { color: #f1722e !important;}

.staff-social li  a {color: rgba(51,51,51,0.7) !important;}
.staff-social li a:hover { color: #f1722e !important;}


/* Front-end pluslet customization colors for titlebar - PV */
.ts-whiteblack, .ts-umgreen {
    background-color: #cee8eb !important;
    color: #333 !important;
}

.titlebar {border-bottom: none !important;}

.pluslet_body .pure-button-pluslet, 
.pluslet_body a.pure-button-pluslet {
  background-color: #f1722e;
  color: #FFF !important;  
}

.pluslet_body .pure-button-pluslet:hover, 
.pluslet_body a.pure-button-pluslet:hover {
  background-color: #999;  
}

.type-experts .expert-button {    
    border: 1px solid #bcbbbb;
    background-color: #f1722e;
    color: #fff !important;
    text-decoration: none !important;
}

.type-experts .expert-button:hover {background-color: #999;}

.table-of-contents { color: #40659b !important;}
.table-of-contents:hover { color: #cc6666 !important;}

.card .card-action a {color: #40659b !important; text-decoration: none !important; }
.card .card-action a:hover {text-decoration: underline !important; color: #cc6666 !important;}



@media screen and (min-width: 48em) {
    #menu_button {display:none !important;}
}

</style>
</head>

<body>
<div id="wrap">

<div id="header-content"> 
    <div class="pure-g header-content">

        <div class="pure-u-1 pure-u-md-2-5">
          <a href="/index.php"><img src="http://library.miami.edu/chc/wp-content/themes/um-chc/images/chc_logo.png" alt="UM Cuban Heritage Collection" border="0" /></a>
          <span id="menu_button"><a class="pure-button button-menu" href="#">Menu</a></span>
        </div>
      
        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">&nbsp;</div>

        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">
            <img src="http://library.miami.edu/chc/wp-content/themes/um-chc/images/question_orange.png" alt="ask a librarian" />
            <span class="header-text"><a href="http://library.miami.edu/ask-a-librarian/">Ask a Librarian</a></span>
        </div> 

        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">
          <form id="head_search" action="<?php print THEME_BASE_DIR; ?>resolver.php" method="post">
                <div id="search_container">
                  <fieldset id="searchzone">
                    <input type="text" name="searchterms" id="searchy" autocomplete="off"  />
                    <input type="submit" value="go" id="topsearch_button2" name="submitsearch" alt="Search" />
                  </fieldset>
                  
                  <fieldset id="search_options">
                      <ul>
                        <li class="active"><input type="radio" name="searchtype" value="website" checked="checked" />website</li>
                        <li><input type="radio" name="searchtype" value="catalog_keyword" />catalog</li>
                        <li><input type="radio" name="searchtype" value="article" />articles+</li>
                        <li class="list-last"><input type="radio" name="searchtype" value="digital" />digital collections</li>
                      </ul>
                  </fieldset>
                </div>
          </form>
        </div>

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
