
</div> <!--end #body_inner_wrap-->
</div> <!--end .pure-u-1-->
</div> <!--end .pure-g -->
</div> <!--end .wrapper-full-->

<div id="push"></div>
</div><!--end #wrap-->

<div class="footer-container">
  <div id="wide_footer">
		    <div class="pure-g">
    	        <div class="pure-u-1 pure-u-md-1-5 pure-u-lg-5-24 footer-adj" id="umiami_logo">
    				    <a href="http://www.miami.edu/"><img src="<?php print $AssetPath; ?>images/med-um-logo.jpg" alt="University of Miami" border="0"  /></a>
              </div>
    				  <div class="pure-u-1 pure-u-md-2-5 pure-u-lg-1-3 footer-adj">
                  <p>Louis Calder Memorial Library<br />
                  1601 NW 10th Ave Miami, FL 33136 <br />
                  P.O. Box 016950 (R-950) <br />
                  Miami, FL 33101 <br />
                  Tel: 305-243-6403 &nbsp;&nbsp;&nbsp;&nbsp; Fax: 305-325-9670<br />
                  <a href="http://calder.med.miami.edu/directions.html" class="directions-btn">Directions</a>
                  </p>
            	</div>
    				  <div class="pure-u-1 pure-u-md-2-5 pure-u-lg-1-3 footer-adj">
    					    <p>
                  <a href="http://www.miami.edu/ref/index.php/ep/">Emergency Preparedness</a><br />
                  <a href="http://www.miami.edu/blackboard">Blackboard</a><br />
                  <a href="http://www.miami.edu/employment">Employment</a><br />
                  <a href="http://www.miami.edu/index.php/privacy_statement/">Privacy Statement and Legal Notices</a><br />
                  <a href="mailto:sxh2751@med.miami.edu">Feedback</a><br />
                  Copyright 2016 University of Miami. All Rights Reserved.
                  </p>
    				  </div>
              <div class="pure-u-1 pure-u-lg-1-8 footer-adj">
                  <p id="social_icons_small"><a href="https://www.facebook.com/caldermedlibrary"><img src="https://calder.med.miami.edu/images/facebook.png" alt="Find Us on Facebook" title="Facebook" width="40px" height="40px"/></a></p>
              </div>
	     </div> <!-- end pure-g -->
  </div> <!-- end wide-footer -->
</div> <!--footer-container-->


<script type="text/javascript" src="includes/js/dropdowntabs.js">
/***********************************************
  * Drop Down Tabs Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
  * This notice MUST stay intact for legal use
  * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
  ***********************************************/  
</script>

<script type="text/javascript" language="javascript">

jQuery(document).ready(function(){


 //Mobile menu click
 $("#menu_button").click(function() {
    $("#spum_nav").toggle();
  });    
  
// Resize function
var $window = $(window);

function checkWidth() {
  windowsize = $window.width();

  if (windowsize >= 748) {
    $("#spum_nav").show();    
  }
  else {
    $("#spum_nav").hide();
  }

  if ((windowsize >= 748) && (windowsize <= 1000)) {
    $("#biomed-link").html("BIOMED COMM");    
  }
  else {
    $("#biomed-link").html("BIOMEDICAL COMMUNICATIONS");
  }

} // end checkWidth()


$(window).resize(checkWidth).resize();

}); 

</script>


<script>
  function printView() {
      var visible_tab;

    $('#tab-body').children().each(function () {
        if ($(this).is(":visible")) {
            visible_tab = $(this);        
        } 
        else {
            $(this).show();        
        }

        
      });
      window.print();
      
      
      $('#tab-body').children().each(function () {
    $(this).hide(); 
    
      });
      
      $(visible_tab).show();

  } //end printView()


//show print dialog
function showPrintDialog() {
  
  $(".printer_tabs").colorbox({html: "<h1>Print Selection</h1><div class=\"printDialog\"><ul><li><a onclick=\"window.print();\" class=\"pure-button pure-button-topsearch\">Print Current Tab</a></li><li><a onclick=\"printView();\" class=\"pure-button pure-button-topsearch\">Print All Tabs</a></li></ul></div>", innerWidth:640, innerHeight:480});

}


$(".print-img-tabs").click(function() {    
    showPrintDialog()
});

$('.print-img-no-tabs').click(function(){ window.print(); });  
  
</script>


<script type="text/javascript">
tabdropdown.init("spum_nav", 3);
 </script>

</body>
</html>
