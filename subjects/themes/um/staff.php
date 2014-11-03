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
          <li><a href="#125">Access, Information & Research Services</a></li>
          <ul>
            <li><a href="#99">Access Services</a></li>

            <li><a href="#107">Digital Media Lab</a></li>
            <li><a href="#118">Education & Outreach</a></li>
            <!--<li><a href="#Information_/_Learning_Commons">Information / Learning Commons</a></li>-->
            <li><a href="#113">Reserves/Inter-Library Loan</a></li>
          </ul>
          <li><a href="#122">Collection Strategies and Scholarly Communication</a></li>

          <ul>
            <li><a href="#100">Acquisitions</a></li>
            <li><a href="#128">Preservation / Conservation</a></li>
          </ul>
          <li><a href="#109">Cuban Heritage Collection</a></li>
          <li><a href="#130">Digital Scholarship & Programs</a></li>
          <ul>
            <li><a href="#110">Digital Production</a></li>
          </ul>
          <li><a href="#102">Financial Administration</a></li>
          <ul>
            <li><a href="#132">Facilities</a></li>
          </ul>
      </ul>
      </div> <!-- end breather -->  
</div>


<div class="pure-u-1  pure-u-md-1-2">   
      <div class="breather">
      <ul>
          <li><a href="#126">Information Management & Systems</a></li>
          <ul>
            <li><a href="#106">Cataloging & Metadata</a></li>
            <li><a href="#129">Systems Administration & Support</a></li>
            <li><a href="#140">Web & Emerging Technologies</a></li>
          </ul>
          <li><a href="#105">Judi Prokop Newman Business Information Resource Center</a></li>
          <li><a href="#141">Libraries Communications & Marketing</a></li>
          <li><a href="#124">Libraries Human Resources</a></li>
          <li><a href="#103">Marta and Austin Weeks Music Library & Technology Center</a></li>
          <li><a href="#117">Paul Buisson Architecture Library</a></li>
          <li><a href="#119">Rosenstiel School of Marine Science & Atmospheric Science Library</a></li>
          <li><a href="#104">Special Collections</a></li>
          <li><a href="#133">University Archives</a></li>
          <li><a href="#139">University Instructional Advancement Center</a></li>
      </ul>
    </div> <!-- end breather -->
</div>
</div><!--end pure-g-->

<br class="clear" />
<br />';


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

include("includes/header_um.php");

?>


<div class="panel-container panel-adj">
<div class="pure-g">
      <div class="pure-u-1">
          <div class="breather">
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
<?php

////////////
// Footer
///////////

include("includes/footer_um.php");

?>