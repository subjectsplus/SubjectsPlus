
</div> <!--end #body_inner_wrap-->
</div> <!--end .pure-u-1-->
</div> <!--end .pure-g -->
</div> <!--end .wrapper-full-->

<div id="push"></div>
</div><!--end #wrap-->

<?php
if (!isset($our_site)) {$our_site="um";}// just in case

switch ($our_site) {
  case "chc":
     $library_address = "<strong>Cuban Heritage Collection | <a href=\"http://library.miami.edu\">UM Libraries</a></strong><br />1300 Memorial Drive, Coral Gables, Florida 33124-0320<br />
        (305) 284-4900<br />
        Email:  <a href=\"mailto:chc@miami.edu\">chc@miami.edu</a>";
    $social_icons = "<p id=\"social_icons_small\">
  <a href=\"" . PATH_TO_CHILD . "/feed/\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/rss_26.png\"  alt=\"Visit the CHC Blog\" title=\"Visit the CHC Blog\" /></a>
  <a href=\"" . PATH_TO_CHILD . "/community/subscribe/\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/mailing_list.png\"  alt=\"Join our Mailing List\" title=\"Join our Mailing List\" /></a>
      <a href=\"http://www.facebook.com/umchc\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/facebook.png\"  alt=\"Find Us on Facebook\" title=\"Find Us on Facebook\" /></a>
    <a href=\"https://twitter.com/umchc\" border=\"0\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/twitter.png\"  alt=\"Twitter\" title=\"Twitter\" /></a>
    <a href=\"http://vimeo.com/umchc\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/vimeo.png\"  alt=\"Find Us on Vimeo\" title=\"Find Us on Vimeo\" /></a>
      <a href=\"http://www.flickr.com/photos/umdigital/collections/72157623554504931/\" border=\"0\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/flickr.png\"  alt=\"Find us on Flickr\" title=\"Find Us on Flickr\" /></a>
    </p>";
  break;
  default:
    $library_address = "<strong>UM Libraries</strong> 1300 Memorial Drive<br />Coral Gables, Florida 33124-0320<br />
        (305) 284-3233";
    $social_icons = "<p id=\"social_icons_small\">
      <a href=\"http://m.library.miami.edu\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/mobile.png\"  alt=\"View Mobile Website\" title=\"View Mobile Website\" /></a>
      <a href=\"http://www.facebook.com/pages/University-of-Miami-Libraries/16409329419\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/facebook.png\"  alt=\"Find Us on Facebook\" title=\"Find Us on Facebook\" /></a>
      <a href=\"http://www.flickr.com/photos/umdigital/\" border=\"0\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/flickr.png\"  alt=\"Find us on Flickr\" title=\"Find Us on Flickr\" /></a>
     <a href=\"https://twitter.com/UMiamiLibraries\" border=\"0\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/twitter.png\"  alt=\"Twitter\" title=\"Twitter\" /></a><br />
        <a href=\"http://library.miami.edu/support-the-libraries/\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/support-uml.png\"  alt=\"Support UML\" title=\"Support UML\" style=\"margin-top: 10px;\" /></a><a href=\"http://library.miami.edu/uml-news-2014/\"><img src=\"http://library.miami.edu/wp-content/themes/umiami/images/uml-news.png\"  alt=\"UML in the News\" title=\"UML in the News\" style=\"margin-top: 10px;\" /></a>
    </p>";

  }
    ?>
<div class="footer-container">
  <div id="wide_footer">	
  	    <div class="pure-g">
    	        <div class="pure-u-1 pure-u-md-1-5 footer-adj">
    				    <a href="http://www.miami.edu/"><img src="http://library.miami.edu/wp-content/themes/umiami/images/umiami_logo.png" alt="University of Miami" border="0" id="umiami_logo" /></a>
              </div>
    				  <div class="pure-u-1 pure-u-md-3-5 footer-adj">
                <p style=""><?php print $library_address; ?><br />
                <a href="http://www.miami.edu/index.php/copyright_notice/">&copy; <?php print date("Y"); ?></a> |
                <a href="http://www.miami.edu/index.php/privacy_statement/">Privacy</a> |
                <a href="<?php print PATH_FROM_ROOT; ?>/report-website-issue/">Report Site Issue</a> |
                <a href="<?php print PATH_FROM_ROOT; ?>/support-the-libraries/">Make a Gift</a>
                </p>
            	</div>
    				  <div class="pure-u-1 pure-u-md-1-5 footer-adj">
    					       <?php print $social_icons; ?>
    				  </div>
  	     </div> <!-- end pure-g -->     
  </div> <!-- end wide-footer -->
</div> <!--footer-container-->

</body>
</html>
<script type="text/javascript" language="javascript">

/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);

  jQuery(document).ready(function(){
  // check window height for making footer ok
  var win_height = $(window).height();
  if (win_height > 900) {
    // tweak the size of the footer area so it extends to bottom of page
    var extra_height = (win_height - 800) + "px";
    //alert(extra_height);
    //$("#wide_footer").css("min-height", extra_height);
  }

  $("#menu_button").click(function() {
    $("#spum_nav").toggle();
});

   //$navmenu = $("#nav_menu");

  function addMega(){

    $(this).children("a").addClass("selected_href");
    $(this).find("[class^=mega]").stop().fadeTo('fast', 1).show();
  }

  function removeMega(){

    $(this).children("a").removeClass("selected_href");
    $(this).find(".mega_child").hide();

  }

  var megaConfig = {
    interval: 50,
    sensitivity: 4,
    over: addMega,
    timeout: 100,
    out: removeMega
  };

// Only want hoverintent to fire if window is a certain size
var $window = $(window);

var $rowcolor = $(".footable-row-detail").prev(".evenrow");

function checkWidth() {
  windowsize = $window.width();

  if (windowsize >= 768) {
    $("#spum_nav").show();
    $("li.mega").hoverIntent(megaConfig);
  }

  if (windowsize <= 747) {
      $("#spum_nav").hide();
       $("li.mega").unbind();   
     }
}

$(window).resize(checkWidth).resize();

// end hoverintent loader by window size

/*
if (jQuery("body").width() >= 1024) {
  $("li.mega").hoverIntent(megaConfig);
}

$(window).on('resize', function(e){
  var win = $(this); //this = window
  if (win.width() >= 1024) { 
      $("li.mega").hoverIntent(megaConfig);
  } else {
      $("li.mega").addClass("offer").removeClass("mega");
  }

});

*/


$(".mega_child select").mouseout(function(e) {
        e.stopPropagation();
});
  // end hover //

  // Search box dropdown //

  var $searchme = $("#search_container");

  function addSearchme() {
    $("#search_options").stop().fadeTo('fast', 1).show();
  }

  function removeSearchme() {
    $("#search_options").stop().fadeTo('fast', 1).hide();
  }

  var searchmeConfig = {
    interval: 200,
    sensitivity: 4,
    over: addSearchme,
    timeout: 300,
    out: removeSearchme
  };

  $searchme.hoverIntent(searchmeConfig);

  var our_option = $('#search_options input:radio:checked').val();

  $("#search_options li").click(function() {
    $("#search_options li").removeClass("active");
    $(this).children().attr('checked', 'checked');
    $(this).addClass("active");
  //alert ($(this).prev().html());

  });

  // End Search Dropdown zone //
});
</script>
<script>
function printView() {
    var visible_tab;

    $('#tab-body').children().each(function () {
  if ($(this).is(":visible")) {
      visible_tab = $(this);
      
  } else {
      $(this).show();
      
  }


    });
    window.print();
    
    
    $('#tab-body').children().each(function () {
  $(this).hide(); 
  
    });
    
    $(visible_tab).show();
    
}

</script>
