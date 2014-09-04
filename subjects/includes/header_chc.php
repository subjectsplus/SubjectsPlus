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
<!-- <link type="text/css" media="print" rel="stylesheet" href="<?php print $AssetPath; ?>css/print.css"> -->

<?php 
// Some constants, previously in the config.php

if ($_SERVER['HTTP_HOST'] != "localhost") {
    define("PATH_FROM_ROOT", "");
    define("THEME_FOLDER", "http://library.miami.edu/wp-content/themes/");
    define("THEME_BASE_DIR", "http://library.miami.edu/wp-content/themes/um-chc/");
} else {
    define("PATH_FROM_ROOT", "/dev-wp");
    define("THEME_BASE_DIR", "http://localhost/dev-wp/wp-content/themes/um-chc/");
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
<!--
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
-->

<style>
/* Some overrides for CHC */

.header-text a {
    color: #333333;
    text-decoration: none;
}

#aska a:hover {
 color: #820000;
 text-decoration: underline;
  }

a:link {
    color: #820000;
}
a:link {
    color: #21759B;
    text-decoration: none;
}

a:hover {text-decoration: underline;}


#searchy {
    background-color: #F2EAD4;
    border: 0 none;
    float: left;
}


#searchzone {
    background-color: #F2EAD4;
    border: 1px solid #820000;
    border-radius: 5px 5px 5px 5px;
    padding: 2px 2px 3px;
}


#topsearch_button2 {
    background: url("http://library.miami.edu/chc/wp-content/themes/umiami/images/search_button_bg.png") no-repeat scroll 0 0 transparent;
    color: #F2EAD4;
}

#search_options li:hover, #search_options li.active {
    background-color: #820000;
    color: #FFFFFF;
}
#search_options li {
    border-bottom: 1px solid #820000;
}
#search_options li {
    border-bottom: 1px solid #A2AD00;
    color: #333333;
    cursor: pointer;
    display: block;
    font-size: 12px;
    margin: 0 !important;
    min-width: 135px;
    padding: 0.2em 0 !important;
    position: relative;
    text-align: left;
    vertical-align: middle;
    background-color: #F2EAD4;
}


div.mega_child ul li a:hover {
    background-color: #820000 !important;
    color: #FFFFFF !important;
}

div.mega_more a, div.mega_feature a {
    color: #820000 !important;
}

#nav_menu li.login a {
    background-color: #820000 !important;
    border-left: 4px solid #FFFFFF;
}

a.nav_highlight :hover {
  background-color: #999;
}

#nav_menu li a.selected_href, #nav_menu li a:hover  {
    color: #ccc;
}

#nav_menu li.login a:hover {
    color: #fff;
    background-color: #999;
}

h1 {background-image:  url("http://library.miami.edu/chc/wp-content/themes/um-chc/images/h1_bg_beige.png");}

@media (max-width: 960px) {

    #nav_menu li.login a  {
    background-color: transparent !important;
    border: none;
    border-radius: none;
    }
}
</style>
</head>

<body>

<div id="header-content"> 
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-2-5" style="padding-top: .5em;text-align: left;">
          <a href="/index.php"><img src="http://library.miami.edu/chc/wp-content/themes/um-chc/images/chc_logo.png" alt="UM Cuban Heritage Collection" border="0" /></a>
          <span id="menu_button"><a class="pure-button button-menu" href="#">Menu</a></span>
        </div>
      <div class="pure-u-1 pure-u-md-1-5">&nbsp;</div>
      <div class="pure-u-1 pure-u-md-1-5 visible-desktop" style="padding-top: .5em;">
      <img src="http://library.miami.edu/wp-content/themes/um-chc/images/question_red.png" alt="ask a librarian" />
          <span class="header-text"><a href="http://library.miami.edu/ask-a-librarian/">Ask a Librarian</a></span>
      </div>      
      <div class="pure-u-1 pure-u-md-1-5 visible-desktop"  style="padding-top: .5em;">
        <form id="head_search" action="<?php print THEME_BASE_DIR; ?>resolver.php" method="post">
          <div id="search_container">
            <fieldset style="" id="searchzone">
              <input type="text" name="searchterms" id="searchy" autocomplete="off"  />
              <input type="submit" value="go" id="topsearch_button2" name="submitsearch" alt="Search" />
            </fieldset>
            <fieldset id="search_options">
              <ul>
                <li class="active"><input type="radio" name="searchtype" value="website" checked="checked" />website</li>
                <li><input type="radio" name="searchtype" value="catalog_keyword" />catalog</li>
                <li><input type="radio" name="searchtype" value="article" />articles+</li>
                <li style="border: none;"><input type="radio" name="searchtype" value="digital" />digital collections</li>
              </ul>
            </fieldset>
          </div>
        </form>
      </div>
<!-- NAV -->      
<div class="pure-u-1" id="spum_nav">
<ul class="nav" style='' id="nav_menu">
<li class="mega"><a href="#">VISIT</a>
          <div class="mega_child" style="width: 410px;">
            <ul>
              <li><a href="<?php print PATH_TO_CHILD; ?>/hoursdirections">Hours & Directions</a></li>
              <li><a href="<?php print PATH_TO_CHILD; ?>/planyourvisit">Plan Your Visit</a></li>
              <li class="last"><a  href="<?php print PATH_TO_CHILD; ?>/instruction">Instruction, Tours, & Room Use</a></li>
              </ul>
              <ul>
              <li><a href="<?php print PATH_TO_CHILD; ?>/events/">Programs & Events</a></li>
              <li class="last"><a  href="<?php print PATH_TO_CHILD; ?>/pavilion">The Robert C. Goizueta Pavilion</a></li>
            </ul>
            <div class="mega_more">See also <a href="http://www.library.miami.edu/specialcollections/">Special Collections</a>, <a href="http://www.library.miami.edu/universityarchives/">Archives</a></div>
          </div></li>

<li class="mega"><a href="#">COLLECTIONS</a>
          <div class="mega_child" style="width: 400px;">
            <ul>
              <li><a href="<?php print PATH_TO_CHILD; ?>/collections">Overview</a></li>
              <li><a href="<?php print PATH_TO_CHILD; ?>/collections/digitalcollections/">Digital       Collections</a></li>
                 <li><a href="<?php print PATH_TO_CHILD; ?>/collections/books/">Books</a></li>
                 <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/collections/periodicals/">Periodicals</a></li>
                 </ul>
            <ul>
                 <li><a href="<?php print PATH_TO_CHILD; ?>/collections/archivalmaterials/">Archival &amp; Manuscript Materials</a></li>
              <li><a href="<?php print PATH_TO_CHILD; ?>/searchtools">Search Tools</a></li>
              <li class="last"><a  href="http://libguides.miami.edu/chc">Research Guides</a></li>
            </ul>
        </div></li>

        <li class="archives_visitor mega"><a href="#">RESEARCH</a>
          <div class="mega_child" style="width: 400px;">
            <ul>
              <li><a href="<?php print PATH_TO_CHILD; ?>/fellows">Fellowships</a></li>
              <li><a href="<?php print PATH_TO_CHILD; ?>/scholars">Undergraduate Scholars</a></li>
              <li class="last"><a href="http://libguides.miami.edu/chc">Research Tips</a></li>
            </ul>
            <ul>
              <li><a href="<?php print PATH_FROM_ROOT; ?>/photocopies-digital-reproductions/">Rights & Reproductions</a></li>
              <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/scholarlypublications/">Scholarly Publications</a></li>
            </ul>
          </div>
        </li>

                <li class="archives_visitor mega"><a href="#">ABOUT US</a>
          <div class="mega_child" style="width: 400px;">
            <ul>
              <li><a href="<?php print PATH_TO_CHILD; ?>/contacts/">Contacts</a></li>
              <li><a href="<?php print PATH_TO_CHILD; ?>/missionhistory/">Mission & History</a></li>
              <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/grantshistory/">Grants History</a></li>
            </ul>
            <ul>
              <li><a href="<?php print PATH_TO_CHILD; ?>/makeagift/">Make a Gift</a></li>
              <li><a href="<?php print PATH_TO_CHILD; ?>/amigos/">Join the Amigos</a></li>
              <li class="last"><a href="<?php print PATH_TO_CHILD; ?>/followchc/">Follow CHC</a></li>
            </ul>
          </div>
        </li>

        <li class="last-child login mega" rel="accounts"><a href="http://aeon.library.miami.edu/">Your Research Account</a>

        </li>
      </ul>
                </div>
              <!-- end accounts mega menu -->
              </li>
          </ul>
      </div> <!-- end spum_nav, pure-u-1 -->
      <div class="pure-u-1">
      <h1><?php print $page_title; ?></h1>
      </div>
    </div> <!-- end pure-g -->
    </div> <!-- end header-content -->



<div class="pure-g">
    <div class="wrapper-full">
        <div class="pure-u-1">

        <div id="body_inner_wrap">
