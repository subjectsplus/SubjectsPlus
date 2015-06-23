<?php

/**
 *   @file services/staff.php
 *   @brief staff listings -- um theme override
 *
 *   @author adarby
 *   @date fall 2014
 *   @todo
 */
use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\StaffDisplay;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

$page_title = "Library Staff";
$description = "Library contact list.";
$keywords = "staff list, librarians, contacts";

$use_jquery = array("ui", "ui_styles");

//////////
// Generate List
//////////


//////////
// Generate List
//////////

$intro = "<br />";
$dept_intro = '
<div class="pure-g">
<div class="pure-u-1  pure-u-md-1-2">  
    <div class="breather">
    <ul>
          <li><a href="#101">Office of the Dean and University Librarian</a></li>

          <li><a href="#122">Collection Strategies and Scholarly Communication</a></li>

          <ul>
            <li><a href="#100">Acquisitions</a></li>
            <li><a href="#128">Preservation / Conservation</a></li>
          </ul>
                    <li><a href="#141">Communications & Marketing</a></li>
          <li><a href="#109">Cuban Heritage Collection</a></li>
          <li><a href="#130">Digital Strategies</a></li>
          <ul>
            <li><a href="#110">Digital Production</a></li>
          </ul>
          <li><a href="#102">Financial Administration</a></li>
          <ul>
            <li><a href="#132">Facilities</a></li>
          </ul>
          <li><a href="#124">Human Resources</a></li>
      </ul>
      </div> <!-- end breather -->  
</div>


<div class="pure-u-1  pure-u-md-1-2">   
      <div class="breather">
      <ul>
          <li><a href="#126">Information Systems &amp; Access</a></li>
          <ul>
          	<li><a href="#99">Access Services</a>
          	<ul>
          	<li><a href="#113">Inter-Library Loan & Course Reserves</a></li></li>
          	</ul>
            <li><a href="#106">Cataloging & Metadata</a></li>
            <li><a href="#143">Systems</a>
            <ul>
            <li><a href="#129">Systems Support</a></li>
            </ul>
            </li>
            <li><a href="#140">Web & Emerging Technologies</a></li>
          </ul>
          <li><a href="#105">Judi Prokop Newman Business Information Resource Center</a></li>
          <li><a href="#125">Learning & Research Services</a></li>
          <ul>
            

            <li><a href="#107">Digital Media Lab</a></li>
            <li><a href="#118">Education & Outreach</a></li>
            <!--<li><a href="#Information_/_Learning_Commons">Information / Learning Commons</a></li>-->
            
          </ul>

          <li><a href="#103">Marta and Austin Weeks Music Library & Technology Center</a></li>
          <li><a href="#117">Paul Buisson Architecture Library</a></li>
          <li><a href="#119">Rosenstiel School of Marine Science & Atmospheric Science Library</a></li>
          <li><a href="#104">Special Collections</a></li>
          <li><a href="#133">University Archives</a></li>
      </ul>
    </div> <!-- end breather -->
</div>
</div><!--end pure-g-->

<br class="clear" />
<br />';


$our_cats = array("A-Z", "By Department","Subject Librarians A-Z", "Librarians by Subject Specialty", "Faculty Profiles");

if (!isset($_GET["letter"]) || $_GET["letter"] == "") {$_GET["letter"] = "A-Z";}

$selected_letter = scrubData($_GET["letter"]);

$alphabet = getLetters($our_cats, $selected_letter);



if ($selected_letter == "A-Z") {
  $intro = "<p><img src=\"$IconPath/information.png\" alt=\"icon\" /> Click on a name for more information.</p><br />
<form class=\"pure-form\"><strong>Search:</strong> <input type=\"text\" placeholder=\"by name or title\" id=\"filter\" class=\"filter-status\"> <span class=\"clear-filter\"><button class=\"clear-filter-icon\">X</button> Clear</span></form><br />";
}

$staff_data = new StaffDisplay();
$out = $staff_data->writeTable($selected_letter);


// Assemble the content for our main pluslet
$display = $alphabet . $intro . $out;

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header_um.php");

?>


<div class="panel-container panel-adj">
<div class="pure-g">
      <div class="pure-u-1">
          <div class="breather">
          	<div id="backtotop">
			   <a href="#">
			      <img src="<?php print $IconPath;?>/top_rocket.png" border="0" alt="Back to TOP" />
			   </a>
			</div>
          <?php print $alphabet; 
          print $intro; ?>
          </div>
      </div>

      <?php
      if ($selected_letter == "By Department") {
        print $dept_intro;
      }  

      ?>
      <div class="pure-u-1">
          <div class="breather-single">            
             <?php print $out;  ?>             
          </div>
      </div>

</div><!--end pure-g-->
</div> <!--end panel-container-->


<!--Data Table-->
<link type="text/css" rel="stylesheet" href="<?php print $AssetPath; ?>css/shared/footable.core.css">
<script src="http://sp.library.miami.edu/subjects/themes/um/js/footable.js" type="text/javascript"></script>
<script src="http://sp.library.miami.edu/subjects/themes/um/js/footable.sort.js" type="text/javascript"></script>
<script src="http://sp.library.miami.edu/subjects/themes/um/js/footable.filter.js" type="text/javascript"></script>

<script type="text/javascript">     

    //set breakpoints 
    $('.footable').footable({
        breakpoints: {
            mid: 600,
            phone:480
        }
    });

    //A-Z list scripts
    $rowcolor = $(".foo1 .footable-row-detail").prev(".evenrow");
    $('.foo1').trigger('footable_expand_first_row').addClass("evenrow");

    $("tr").on("click", function () {
	    if ($(this).hasClass("evenrow")) {
	
	        $(this).next(".footable-row-detail").addClass("evenrow");
	
	    } else if ($(this).hasClass("oddrow")) {
	
	        $(this).next(".footable-row-detail").addClass("oddrow");
	
	    }
	});

    
    //Bind functions for responsive/resizing A-Z
    $('.foo1').bind('footable_breakpoint', function() {

          $('.foo1').trigger('footable_expand_first_row');
          
          $("tr").on("click", function () {
		        if ($(this).hasClass("evenrow")) {
		
		            $(this).next(".footable-row-detail").addClass("evenrow");
		
		        } else if ($(this).hasClass("oddrow")) {
		
		            $(this).next(".footable-row-detail").addClass("oddrow");
		
		        }
		    });

    });
     
    //Clear filter A-Z
    $('.clear-filter').click(function (e) {
      e.preventDefault();
      $('.filter-status').val('');
      $('.footable').trigger('footable_clear_filter');
    });  

    

    //Department scripts
    $('.foo2').bind('footable_breakpoint', function() {        
			$("tr").on("click", function () {
		        if ($(this).hasClass("evenrow")) {
		
		            $(this).next(".footable-row-detail").addClass("evenrow");
		
		        } else if ($(this).hasClass("oddrow")) {
		
		            $(this).next(".footable-row-detail").addClass("oddrow");
		
		        }
		    });           
    });


    //Subject Librarians A-Z scripts
    $('.foo3').trigger('footable_expand_first_row').addClass("evenrow"); 

    $('.foo3').bind('footable_breakpoint', function() {

          $("tr").on("click", function () {
			    if ($(this).hasClass("evenrow")) {
			
			        $(this).next(".footable-row-detail").addClass("evenrow");
			
			    } else if ($(this).hasClass("oddrow")) {
			
			        $(this).next(".footable-row-detail").addClass("oddrow");
			
			    }
			});   

    });  




</script>

<?php

////////////
// Footer
///////////

include("includes/footer_um.php");

?>

<style type="text/css">
#backtotop {
	position: fixed;
	/*padding-left: 961px;*/
    margin-left: -11px;
	top:60%;
	display:none;/*hid the button first*/
}
#backtotop a {
	text-decoration:none;
	border:0 none;
	display:block;
	width:31px;
	height:155px;
}
#backtotop a:hover {
	opacity:.8; /*mouse over fade effect*/
}

</style>
<script type="text/javascript">



	jQuery(document).ready(function(){
      // script from http://typicalwhiner.com/116/effortless-jquery-floating-back-to-top-script-v2/
		var pxShow = 400;//height on which the button will show
		var fadeInTime = 1000;//how slow/fast you want the button to show
		var fadeOutTime = 1000;//how slow/fast you want the button to hide
		var scrollSpeed = 1000;//how slow/fast you want the button to scroll to top. can be a value, 'slow', 'normal' or 'fast'
		
		
		
		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop() >= pxShow){
				jQuery("#backtotop").fadeIn(fadeInTime);
			}else{
				jQuery("#backtotop").fadeOut(fadeOutTime);
			}
		});
		 
		jQuery('#backtotop a').click(function(){
			jQuery('html, body').animate({scrollTop:0}, scrollSpeed); 
			return false; 
		}); 
	});
</script>