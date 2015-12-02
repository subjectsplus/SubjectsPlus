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



<?php 

// Turn off https with a redirect on front end pages 

/*
if(isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != ""){
    $redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $redirect");
}

*/

if ($_SERVER['HTTP_HOST'] != "localhost") {
    define("PATH_FROM_ROOT", "//library.miami.edu");
    define("THEME_FOLDER", "//library.miami.edu/wp-content/themes/");
    define("THEME_BASE_DIR", "//library.miami.edu/wp-content/themes/umiami/");
} else {
    define("PATH_FROM_ROOT", "/richter");
    define("THEME_BASE_DIR", "http://localhost/richter/wp-content/themes/umiami/");
}

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

</head>

<body>
<div id="wrap">

<div id="header-content"> 
    <div class="pure-g header-content">

        <div class="pure-u-1 pure-u-md-1-5">
          <a href="<?php print PATH_FROM_ROOT; ?>/index.php"><img src="//library.miami.edu/wp-content/themes/umiami/images/logo.png" alt="University of Miami Libraries" /></a>
          <span id="menu_button"><a class="pure-button button-menu" href="#">Menu</a></span>          
        </div>        
        
        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">&nbsp;</div>
        
        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">
          <img src="http://library.miami.edu/wp-content/themes/umiami/images/question_green.png" alt="ask a librarian" />
          <span class="header-text"><a href="http://library.miami.edu/ask-a-librarian/">Ask a Librarian</a></span>
        </div>      
      
        <div class="pure-u-1 pure-u-md-1-5 visible-desktop">
          <img src="http://library.miami.edu/wp-content/themes/umiami/images/talk_bubble_green.png" alt="talk back" />
          <span class="header-text"><a href="<?php print PATH_TO_SP; ?>subjects/talkback.php" title="Make a comment">Comments</a></span>
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
              
              <!--BOOKS-->
              <li class="mega"><a href="http://library.miami.edu/books/">BOOKS</a>
                  <!-- begin books mega menu -->
                  <div class="mega_child mega-md mega-left">
                    <div class="megasearchzone">
                    <p>Looking for books? Start with the catalog:</p>
                      <form action="http://catalog.library.miami.edu/search/" method="get" name="search" id="search">
                        <input type="hidden" id="iiiFormHandle_1">

                        <select name="searchtype" id="searchtype">
                          <option value="X" selected="selected">Keyword</option>
                          <option value="t">Title</option>
                          <option value="a">Author</option>
                          <option value="d">Subject</option>
                        </select>

                        <input type="hidden" name="SORT" id="SORT" value="D" />

                        <input maxlength="75" name="searcharg" size="20" />
                        
                        <input name="Search" type="submit" id="Search" value="Search" />
                      </form>
                    </div>
                    <ul>
                      <li><a href="http://catalog.library.miami.edu/">Catalog home</a></li>
                      <li class="last"><a href="<?php print PATH_TO_SP; ?>subjects/new_acquisitions.php">New Acquisitions</a></li>
                    </ul>
                    <ul>
                      <li><a href="http://miami.lib.overdrive.com/">Overdrive E-Books</a></li>
                    </ul>
                    <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/books/">Books Overview</a></div>
                  </div>
                  <!-- end books mega menu -->
              </li>

              <!--ARTICLES-->
              <li class="mega"><a href="http://library.miami.edu/articles/">ARTICLES</a>
                  <!-- begin articles mega menu -->
                  <div class="mega_child mega-md mega-left">
                      <div class="megasearchzone">
                      <p>Search for Articles across many databases:</p>
                        <form action="http://miami.summon.serialssolutions.com/search" method="GET" id="summon_search">
                          <input type="hidden" value="ContentType,Newspaper Article, true" name="" />
                          <input type="hidden" value="ContentType,Book Review, true" name="" />
                          <input type="hidden" value="ContentType,Trade Publication Article, true" name="" />
                          <input name="s.q" value="" size="30" />
                          <input type="submit" value="Go" /> 
                        </form>
                      </div>
                      <ul>
                        <li class="last"><a href="<?php print PATH_TO_SP; ?>subjects/databases.php">Databases A-Z</a></li>
                      </ul>
                      <ul>
                        <li class="last"><a href="http://mt7kx4ww9u.search.serialssolutions.com/">Journal List</a></li>
                      </ul>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/articles/">Articles Overview</a></div>
                  </div>
                  <!-- end articles mega menu -->
              </li>


              <!--CDSs/DVDs-->
              <li class="mega"><a href="http://library.miami.edu/media/">CD / DVDs</a>
                  <!-- begin cdz mega menu -->
                  <div class="mega_child mega-lg mega-left">
                    <div class="megasearchzone">
                    <p>Looking for Music or Movies? Use the Catalog:</p>
                      <form action="http://catalog.library.miami.edu/search/" method="get" name="search" id="search">
                        <input type="hidden" id="iiiFormHandle_1">

                        <select name="searchtype" id="searchtype" style="">
                          <option value="X" selected="selected">Keyword</option>
                          <option value="t">Title</option>
                          <option value="a">Author</option>
                          <option value="d">Subject</option>
                          <option value="c">LC Call Number</option>
                          <option value="l">Local Call Number</option>
                          <option value="g">SuDocs Number</option>
                          <option value="i">ISSN/ISBN Number</option>
                          <option value="o">OCLC Number</option>
                          <option value="m">Music Pub. Number</option>
                        </select>

                        <input type="hidden" name="SORT" id="SORT" value="D" />

                        <input maxlength="75" name="searcharg" size="15"  style="" /><br /><br />

                           <span style="font-size:12px;">limit to:</span> 
                         <select id="label4" name="searchscope">
                           <option value="17" selected="selected"> DVDs/Videos </option>
                            <option value="15"> Music CDs</option>
                            <option value="8"> Music Library</option>
                            <option value="16"> Music Recordings</option>
                            <option value="18"> Music Scores</option>
                            <option value="19"> Streaming Audio/Video</option>
                            <option value="11">Entire Collection</option>
                          </select>
                          
                        <input type="hidden" id="iiiFormHandle_1"/>
                        <input name="Search" type="submit" id="Search" value="Search" />
                      </form>
                      

                  </div>
                  <div class="mega_feature">
                        <a href="http://library.miami.edu/udvd/">
                        <img alt="Try UDVD for your DVD Needs" title="Try UDVD for your DVD Needs" src="https://library.miami.edu/wp-content/themes/umiami/images/udvd_square.png">
                        </a>
                  </div>
                  <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/media/">CD/DVDs Overview</a>, <a href="http://library.miami.edu/musiclib/">Music Library</a></div>
                </div>
                  <!-- end cdz mega menu -->
               </li>

               <!--RESEARCH-->
               <li class="research mega"><a href="http://library.miami.edu/research/">RESEARCH</a>
                  <!-- begin research mega menu -->
                  <div class="mega_child mega-lg mega-left-special">
                      <ul>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/research/getting-started/">Getting Started</a></li>
                          <li><a href="http://libguides.miami.edu/">Research Guides</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/research/consultations/">Research Consultations</a></li>
                         <li><a href="<?php print PATH_TO_SP; ?>subjects/staff.php?letter=Subject Librarians A-Z">Subject Librarians</a></li>
                         <li  class="last"><a href="<?php print PATH_FROM_ROOT; ?>/citation/">Citation Help</a></li>
                      </ul>
                      <ul>                          
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/workshops-tutorials/">Workshops</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/tutorials/">Tutorials</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/copyright/">Copyright</a></li>
                          <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/scholarly-communications/">Scholarly Communications &amp; Publishing</a></li>
                      </ul>
                      <div class="mega_feature">
                        <img src="<?php print PATH_TO_SP; ?>assets/users/_djui/headshot.jpg" alt="Librarian headshot" />
                        Need Help?  <a href="<?php print PATH_FROM_ROOT; ?>/ask-a-librarian/">Ask a Librarian</a>
                      </div>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/research/">Research Overview</a></div>
                  </div>
                  <!-- end research mega menu -->
              </li>

              <!--LIBRARIES AND COLLECTIONS-->
              <li class="libraries mega"><a href="http://library.miami.edu/libraries-collections/">LIBRARIES &amp; COLLECTIONS</a>
                  <!-- begin lib/cols mega menu -->
                  <div class="mega_child mega-lg mega-right">
                        <ul>
                            <li><a href="http://arc.miami.edu/the-school/facilities/architecture-reference-library">Architecture</a></li>
                            <li><a href="http://www.bus.miami.edu/research-library/">Business</a></li>
                            <li><a href="http://www.law.miami.edu/library/">Law</a></li>
                            <li><a href="http://www.library.miami.edu/rsmaslib/">Marine</a></li>
                            <li><a href="http://calder.med.miami.edu/">Medical</a></li>
                            <li><a href="http://library.miami.edu/musiclib/">Music</a></li>
                            <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/">Richter (interdisciplinary)</a></li>
                        </ul>
                        <ul>
                            <li><a href="http://www.library.miami.edu/chc/">Cuban Heritage Collection</a></li>
                            <li><a href="http://www.library.miami.edu/specialcollections/">Special Collections</a></li>
                            <li><a href="http://merrick.library.miami.edu/">UM Digital Collections</a></li>
                            <li><a href="http://library.miami.edu/oral-histories/">UM Oral Histories</a></li>
                            <li><a href="http://scholarlyrepository.miami.edu/">UM Scholarly Repository</a></li>
                            <li class="last"><a href="http://www.library.miami.edu/universityarchives/">University Archives</a></li>
                        </ul>
                      <div class="mega_feature">
                        <img src="https://library.miami.edu/wp-content/themes/umiami/images/rsmas.jpg" alt="RSMAS" />
                        <p style="align-right"><a href="http://www.library.miami.edu/rsmaslib/">RSMAS Library</a></p>
                      </div>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/libraries-collections/">Collections Overview</a>,
                        <a href="<?php print PATH_TO_SP; ?>subjects/new_acquisitions.php">New Acquisitions</a>,
                        <a href="<?php print PATH_FROM_ROOT; ?>/suggest-a-purchase/">Suggest a Purchase</a></div>
                  </div>
                  <!-- end lib/cols mega menu -->
                </li>

                <!--SERVICES-->
                <li class="services mega"><a href="http://library.miami.edu/services/">SERVICES</a>
                  <!-- begin services mega menu -->
                    <div class="mega_child mega-md mega-right-special">
                      <ul>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/borrowing/">Access &amp; Borrowing</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/ada/">ADA/Disability Services</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/course-reserves/">Course Reserves</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/interlibrary-loan/">Interlibrary Loan</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/printing/">Printing</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/teaching-support/">Teaching Support</a></li>
                          <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/instruction-tour-request-form/">Instruction/Tour Request</a></li>
                          
                      </ul>
                       <ul>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/computers/">Computers</a></li>                          
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/medialab/">Digital Media Lab</a></li>
                          <li><a href="<?php print PATH_TO_SP; ?>subjects/gis">GIS Services</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/reserve-equipment/">Reserve Equipment</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/graduate-study/">Graduate Study Room</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/booking/richter-study/">Reserve Group Study Room</a></li>
                          <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/rooms-spaces/">Rooms &amp; Spaces</a></li>
                        </ul>
                     
                        <div class="mega_more">See also <a href="<?php print PATH_TO_SP; ?>subjects/staff.php">Staff List</a>,
                          <a href="<?php print PATH_FROM_ROOT; ?>/patron/">Information by Patron Type</a></div>
                    </div>
                  <!-- end services mega menu -->
                </li>

                <!--ABOUT-->
                <li class="about mega"><a href="http://library.miami.edu/about/">ABOUT</a>
                  <!-- begin about mega menu -->
                  <div class="mega_child mega-sm mega-right-special">
                      <ul>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/patron/visitor/">Visitor Information</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/employment/">Employment</a></li>
                        <li><a href="<?php print PATH_TO_SP; ?>subjects/faq.php">FAQs</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/forms/">Forms</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/policies/">Policies</a></li>
                        <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/publications/">Publications</a></li>

                      </ul>
                      <ul>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/hours/">Hours</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/floor-plans/">Floor Plans</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/departments/">Library Departments</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/maps/">Maps &amp; Directions</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/service-desks/">Service Desks</a></li>
                        <li class="last"><a href="<?php print PATH_TO_SP; ?>subjects/staff.php">Staff List</a></li>
                      </ul>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/about/">About the Libraries Overview</a></div>
                    </div>
                    <!-- end about mega menu-->
                </li>

                <!--ACCOUNTS-->
                <li class="login mega last-child" rel="accounts"><a href="http://library.miami.edu/patron/" class="nav_highlight">Accounts+</a>
                <!-- begin accounts mega menu -->
                  <div class="mega_child mega-sm mega-right-special2">
                      <div class="mega_intro"><span style="width: 155px;display: inline-block;">Accounts</span>
                        <span style="display: inline-block; width: 160px;">Information for</span>
                      </div>
                      <ul>
                        <li><a href="http://ibisweb.miami.edu:2082/patroninfo">MyLibrary</a></li>                        
                        <li><a href="https://www.courses.miami.edu/webapps/login/">Blackboard</a></li>
                        <li><a href="https://triton.library.miami.edu/">ILLiad (Interlibrary Loan)</a></li>
                        <li><a href="http://illweb.library.miami.edu/aeon/logon.html">Aeon</a></li>
                        <li class="last"><a href="https://myum.miami.edu/">MyUM</a></li>
                      </ul>
                      <ul>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/patron/undergrad/">Undergraduate</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/patron/grad/">Graduate Student</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/patron/faculty/">Faculty</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/patron/alumnus/">Alumnus</a></li>
                        <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/patron/visitor/">Visitor</a></li>
                      </ul>
                  </div>
                <!-- end accounts mega menu -->
                </li>

          </ul>
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
