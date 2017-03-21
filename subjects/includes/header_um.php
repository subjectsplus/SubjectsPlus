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

$primoSearch = "http://miami-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/dlSearch.do";
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
         <?php
            class SearchBox {
              function __construct() {
              }
              
              public function requireToVar($file){
                ob_start();
                require($file);
                
                return ob_get_clean();
                }
                
              public function outputBox() {
                $markup = $this->requireToVar('views/um-searchbox.html');
                return $markup;
              }
            }
            $sb = new SearchBox(); echo $sb->outputBox();

            ?>

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

                    <form action="<?php print $primoSearch; ?>" method="get" name="searchForm3" id="simple3" enctype="application/x-www-form-urlencoded; charset=utf-8" onsubmit="searchPrimo3()">
                      
                      <!-- Customizable Parameters -->
                      <input type="hidden" name="institution" value="01UOML">
                      <input type="hidden" name="vid" value="uml">
                      <input type="hidden" name="tab" value="default_tab">
                      <input type="hidden" name="mode" value="Basic">

                      <!-- Fixed parameters -->
                      <input type="hidden" name="displayMode" value="full">
                      <input type="hidden" name="bulkSize" value="10">
                      <input type="hidden" name="highlight" value="true">
                      <input type="hidden" name="dum" value="true">
                      <input type="hidden" name="query" id="primoQuery3">
                      <input type="hidden" name="displayField" value="all">

                      <!-- Books only -->
                      <input type="hidden" value="facet" name="ct"> 
                      <input type="hidden" value="facet_rtype" name="fctN"> 
                      <input type="hidden" value="Books" name="fctV"> 
                      <input type="hidden" value="show_only" name="rfnGrp">

                      <select name="searchfield3" id="searchfield3">
                        <option value="any">Anywhere</option>
                        <option value="title"> Title</option>
                        <option value="creator"> Author/Creator</option>
                        <option value="sub"> Subject</option>
                        <option value="lsr06"> Call Number</option>
                        <option value="isbn"> ISBN</option>
                        <option value="lsr01"> Course Reserve</option>
                      </select>

                      <input maxlength="75" type="text" id="primoQueryTemp3" value="" size="20" class="primoQueryInput">
                      <input id="goSearch" title="Search" onclick="searchPrimo3()" type="submit" value="Search" alt="Search">
                    </form>

                    <script type="text/javascript">                                        
                        function searchPrimo3() {
                          var searchfield = document.getElementById("searchfield3").value;
                          document.getElementById("primoQuery3").value = searchfield + ",contains," + document.getElementById("primoQueryTemp3").value.replace(/[,]/g, " ");
                          document.forms["searchForm3"].submit();
                        }                        
                    </script>

                    </div>
                    <ul>
                      <li><a href="http://search.library.miami.edu/">Catalog home</a></li>
                      <li class="last"><a href="https://library.miami.edu/newitems/index.php?src=richter&display=grid">New Items</a></li>
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

                      <form id="simple4" name="searchForm4" method="GET" target="_self" action="<?php print $primoSearch; ?>" enctype="application/x-www-form-urlencoded; charset=utf-8" onsubmit="searchPrimo4()">

                      <!-- Customizable Parameters -->
                      <input type="hidden" name="institution" value="01UOML">
                      <input type="hidden" name="vid" value="uml">
                      <input type="hidden" name="tab" value="everything">
                      <input type="hidden" name="search_scope" value="Everything">                
                      <input type="hidden" name="mode" value="Basic">

                      <!-- Fixed parameters -->
                      <input type="hidden" name="displayMode" value="full">
                      <input type="hidden" name="bulkSize" value="10">
                      <input type="hidden" name="highlight" value="true">
                      <input type="hidden" name="dum" value="true">
                      <input type="hidden" name="query" id="primoQuery4">
                      <input type="hidden" name="displayField" value="all">

                      <!-- Articles only -->
                      <input type="hidden" value="facet" name="ct"> 
                      <input type="hidden" value="facet_rtype" name="fctN"> 
                      <input type="hidden" value="articles" name="fctV"> 
                      <input type="hidden" value="show_only" name="rfnGrp">

                      <input type="text" id="primoQueryTemp4" value="" size="35" class="primoQueryInput">
                      <input id="go4" title="Search" onclick="searchPrimo4()" type="submit" value="Search" alt="Search">
                  </form>

                  <script type="text/javascript">                                        
                        function searchPrimo4() {
                          document.getElementById("primoQuery4").value = "any,contains," + document.getElementById("primoQueryTemp4").value.replace(/[,]/g, " ");
                        document.forms["searchForm4"].submit();
                        }
                  </script>

                      </div>
                      <ul>
                        <li class="last"><a href="<?php print PATH_TO_SP; ?>subjects/databases.php">Databases A-Z</a></li>
                      </ul>
                      <ul>
                        <li class="last"><a href="http://miami-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/dlSearch.do?institution=01UOML&vid=uml&query=facet_atoz%2cexact%2cA&indx=1&bulkSize=30&dym=false&loc=local%2cscope%3a%28AZ01UOML%29&fn=goAlmaAz&sortField=stitle&almaAzSearch=true&azSearch=true&selectedAzAlmaLetter=A">Journal List</a></li>
                      </ul>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/articles/">Articles Overview</a></div>
                  </div>
                  <!-- end articles mega menu -->
              </li>


              <!--CDSs/DVDs-->
              <li class="mega"><a href="http://library.miami.edu/media/">CD / DVDs</a>
                  <!-- begin cdz mega menu -->
                  <div class="mega_child mega-md mega-left">
                    <div class="megasearchzone">
                    <p>Looking for Music or Movies? Use the Catalog:</p>
                      
                      <form action="<?php print $primoSearch; ?>" method="get" name="searchForm5" id="simple5" enctype="application/x-www-form-urlencoded; charset=utf-8" onsubmit="searchPrimo5()">

                        <!-- Customizable Parameters -->
                        <input type="hidden" name="institution" value="01UOML">
                        <input type="hidden" name="vid" value="uml">
                        <input type="hidden" name="tab" value="default_tab">
                        <input type="hidden" name="mode" value="Basic">

                        <!-- Fixed parameters -->
                        <input type="hidden" name="displayMode" value="full">
                        <input type="hidden" name="bulkSize" value="10">
                        <input type="hidden" name="highlight" value="true">
                        <input type="hidden" name="dum" value="true">
                        <input type="hidden" name="query" id="primoQuery5">
                        <input type="hidden" name="displayField" value="all">

                        <select name="searchfield5" id="searchfield5">
                         <option value="any">Anywhere</option>
                          <option value="title">Title</option>
                          <option value="creator">Author/Creator</option>
                          <option value="sub">Subject</option>
                          <option value="lsr06">Call Number</option>
                          <option value="isbn">ISBN</option>
                          <option value="lsr01">Course Reserve</option>
                        </select>



                      <input maxlength="75" type="text" id="primoQueryTemp5" value="" size="20" class="primoQueryInput" /> 

                      <p style="font-size:14px !important; margin: 0;">limit to:
                        <select id="searchfield6" name="query">                        
                          <option value="facet_rtype,exact,audio" id="mediaType_audio1">Audio</option> 
                          <option value="facet_rtype,exact,video" id="mediaType_video1">Video</option>                    
                        </select>

                        <input id="goSearch5" title="Search" onclick="searchPrimo5()" type="submit" value="Search" alt="Search" />
                      </p> 
                    </form>

                    <script type="text/javascript">                                        
                        function searchPrimo5() {
                          var searchfield = document.getElementById("searchfield5").value;
                          document.getElementById("primoQuery5").value =  searchfield + ",contains," + document.getElementById("primoQueryTemp5").value.replace(/[,]/g, " ");
                          document.forms["searchForm5"].submit();
                        }                        
                    </script>
                      

                  </div>
                  
                  <!--<div class="mega_feature">
                        <a href="http://library.miami.edu/udvd/">
                        <img alt="Try UDVD for your DVD Needs" title="Try UDVD for your DVD Needs" src="https://library.miami.edu/wp-content/themes/umiami/images/udvd_square.png">
                        </a>
                  </div>-->
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
                        <li><a href="http://sp.library.miami.edu/subjects/index.php">Research Guides</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/research/consultations/">Research Consultations</a></li>
                        <li><a href="<?php print PATH_FROM_ROOT; ?>/library-research-scholars/">Library Research Scholars</a></li>
                        <li class="last"><a href="<?php print PATH_TO_SP; ?>subjects/staff.php?letter=Subject Librarians A-Z">Liaison Librarians A-Z</a></li>
                      </ul>
                      <ul>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/citation/">Citation Help</a></li>                          
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/workshops/">Workshops</a></li>
                          <li><a href="http://sp.library.miami.edu/subjects/tutorials">How-to Videos</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/copyright/">Copyright</a></li>
                          <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/scholarly-communications/">Scholarly Communications &amp; Publishing</a></li>
                      </ul>
                      <div class="mega_feature">
                        <img src="<?php print PATH_TO_SP; ?>assets/users/_djui/headshot.jpg" alt="Librarian headshot" />
                        Need Help?  <a href="<?php print PATH_FROM_ROOT; ?>/ask-a-librarian/">Ask a Librarian</a>
                      </div>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/research/">Research Overview</a>, <a href="http://library.miami.edu/learningcommons/">Learning Commons</a></div>
                  </div>
                  <!-- end research mega menu -->
              </li>

              <!--LIBRARIES AND COLLECTIONS-->
              <li class="libraries mega"><a href="http://library.miami.edu/libraries-collections/">LIBRARIES &amp; COLLECTIONS</a>
                  <!-- begin lib/cols mega menu -->
                  <div class="mega_child mega-lg mega-right">
                        <ul>
                            <li><a href="http://library.miami.edu/architecture/">Architecture</a></li>
                            <li><a href="http://library.miami.edu/business/">Business</a></li>
                            <li><a href="http://www.law.miami.edu/library/">Law</a></li>
                            <li><a href="http://library.miami.edu/rsmaslib/">Marine</a></li>
                            <li><a href="http://calder.med.miami.edu/">Medical</a></li>
                            <li><a href="http://library.miami.edu/musiclib/">Music</a></li>
                            <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/">Richter (interdisciplinary)</a></li>
                        </ul>
                        <ul>
                            <li><a href="http://library.miami.edu/chc/">Cuban Heritage Collection</a></li>
                            <li><a href="http://merrick.library.miami.edu/">Digital Collections</a></li>
                            <li><a href="http://library.miami.edu/oral-histories/">Oral Histories</a></li>
                            <li><a href="http://scholarlyrepository.miami.edu/">Scholarly Repository</a></li>
                            <li><a href="http://library.miami.edu/specialcollections/">Special Collections</a></li>
                            <li><a href="http://library.miami.edu/universityarchives/">University Archives</a></li>
                        </ul>
                      <div class="mega_feature">
                        <img src="https://library.miami.edu/wp-content/themes/umiami/images/rsmas.jpg" alt="RSMAS" />
                        <p style="align-right"><a href="http://www.library.miami.edu/rsmaslib/">RSMAS Library</a></p>
                      </div>
                      <div class="mega_more">See also <a href="<?php print PATH_FROM_ROOT; ?>/libraries-collections/">Collections Overview</a>, <a href="https://library.miami.edu/newitems/index.php?src=richter&display=grid">New Items</a>, <a href="<?php print PATH_FROM_ROOT; ?>/suggest-a-purchase/">Recommend a Purchase</a></div>
                  </div>
                  <!-- end lib/cols mega menu -->
                </li>

                <!--SERVICES-->
                <li class="services mega"><a href="http://library.miami.edu/services/">SERVICES</a>
                  <!-- begin services mega menu -->
                    <div class="mega_child mega-lg mega-right-special">
                      <ul>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/borrowing/">Access &amp; Borrowing</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/ada/">ADA/Disability Services</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/computers/">Computers</a></li>  
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/course-reserves/">Course Reserves</a></li>                           
                          <li><a href="http://sp.library.miami.edu/subjects/digital-humanities">Digital Humanities</a></li>                 
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/medialab/">Digital Media Lab</a></li>
                          <li><a href="http://sp.library.miami.edu/subjects/etd">ETD Formatting Support</a></li> 
                          <li><a href="<?php print PATH_TO_SP; ?>subjects/gis">GIS Services</a></li>
                          <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/graduate-study/">Graduate Study Room</a></li>
                      </ul>
                       <ul>                               
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/instruction-tour-request-form/">Instruction/Tour Request</a></li>
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/interlibrary-loan/">Interlibrary Loan</a></li>
                          <li><a href="http://library.miami.edu/learningcommons/">Learning Commons</a></li> 
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/printing/">Printing</a></li>
                          <li><a href="http://library.miami.edu/datacuration/">Research Data Services</a></li> 
                          <li><a href="<?php print PATH_FROM_ROOT; ?>/reserve-equipment/">Reserve Equipment</a></li>
                          <li><a href="http://libcal.miami.edu/booking/richter-study">Reserve Group Study Room</a></li>
                          <li><a href="http://library.miami.edu/rooms-spaces/">Rooms &amp; Spaces</a></li> 
                          <li class="last"><a href="<?php print PATH_FROM_ROOT; ?>/teaching-support/">Teaching Support</a></li>
                        </ul>

                        <div class="mega_feature">
                          <img src="https://library.miami.edu/wp-content/themes/umiami/images/essential10-thumb.jpg" alt="Essential 10" /><br />
                          <p style="line-height: 1.3em;">Discover the <a href="https://library.miami.edu/wp-content/uploads/2015/11/Essential10.pdf" target="_blank">Essential 10</a> things to do at the Libraries!</p>
                        </div>
                     
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
                        <li><a href="http://miami-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/login.do?loginFn=signin&vid=uml&targetURL=http:%2F%2Fmiami-primo.hosted.exlibrisgroup.com%2Fprimo_library%2Flibweb%2Faction%2Fsearch.do%3Fvid%3Duml%26amp;dscnt%3D0%26amp;afterTimeout%3DE08029BDF7C2992FC31D8ACB97398E2E%26amp;dstmp%3D1463604331494%26amp;initializeIndex%3Dtrue&isMobile=false">MyLibrary</a></li>                        
                        <li><a href="https://www.courses.miami.edu/webapps/login/">Blackboard</a></li>
                        <li><a href="https://triton.library.miami.edu/">ILLiad (Interlibrary Loan)</a></li>
                        <li><a href="https://aeon.library.miami.edu/aeon/">Aeon</a></li>
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
