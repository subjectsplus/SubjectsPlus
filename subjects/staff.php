<?php

/**
 *   @file services/staff.php
 *   @brief staff listings
 *
 *   @author adarby
 *   @date august, 2010
 *   @todo
 */

use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\StaffDisplay;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

// If you have a theme set, but DON'T want to use it for this page, comment out the next line
if (isset($subjects_theme)  && $subjects_theme != "") { include("themes/$subjects_theme/staff.php"); exit;}

$page_title = "Library Staff";
$description = "Library contact list.";
$keywords = "staff list, librarians, contacts";

$use_jquery = array("ui", "ui_styles");

//////////
// Generate List
//////////

$intro = "<br />";

$our_cats = array("A-Z", "By Department","Subject Librarians A-Z", "Librarians by Subject Specialty");

if (!isset($_GET["letter"]) || $_GET["letter"] == "") {$_GET["letter"] = "A-Z";}

$selected_letter = scrubData($_GET["letter"]);

$alphabet = getLetters($our_cats, $selected_letter);

if ($selected_letter == "A-Z") {

$intro = "<p><img src=\"$IconPath/information.png\" alt=\"icon\" /> Click on a name for more information.</p>
<br />";

}

$staff_data = new StaffDisplay();
$out = $staff_data->writeTable($selected_letter);


// Assemble the content for our main pluslet
$display = $alphabet . $intro . $out;

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header.php");

?>
<div class="pure-g">
<div class="pure-u-1 pure-u-lg-2-3 pure-u-xl-4-5">
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print _("Staff Listing"); ?></div>
        </div>
        <div class="pluslet_body">
            <?php print $display; ?>
        </div>
    </div>
</div>
<div class="pure-u-1 pure-u-lg-1-3 pure-u-xl-1-5">
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print _("Find People"); ?></div>
        </div>
        <div class="pluslet_body">
          <?php
          $input_box = new CompleteMe("quick_search", "staff.php", "", "Quick Search", "admin", 20);
          $input_box->displayBox();
          ?>

        </div>
    </div>

    <br />

</div>
</div>

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

include("includes/footer.php");

?>