<?php
/**
 *   @file services/staff.php
 *   @brief staff listings -- um theme override
 */
use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\StaffDisplay;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

$page_title = "Library Staff";
$description = "Library contact list.";
$keywords = "staff list, librarians, contacts";
$legend = "Click on a name for more information.";


$intro = "";
$dept_intro = '<ul class="list-unstyled dept-intro">
  <li><a href="#101" class="dept-heading">Office of the Dean and University Librarian</a></li>
  <li>
    <div class="dept-sub">
      <a href="#141">Creative Services</a>
      <a href="#102">Financial Administration</a>
      <a href="#124">Human Resources</a>
    </div>
  </li>
  
  <li><a href="#122" class="dept-heading">Collection Strategies and Scholarly Communication</a></li>
  <li>
    <div class="dept-sub">
        <a href="#100">Acquisitions</a>
        <a href="#128">Preservation / Conservation</a>
    </div>    
  </li>
  
  <li><a href="#130" class="dept-heading">Digital Strategies</a></li>
  <li>
    <div class="dept-sub">
      <a href="#110">Digital Production</a>  
    </div>
  </li>
  
  <li class="dept-heading">Health Science Services</li>
  <li>
    <div class="dept-sub">
        <a href="http://calder.med.miami.edu/">Louis Calder Memorial Library</a>
    </div>
  </li>
  
  <li><a href="#126" class="dept-heading">Information Systems &amp; Access</a></li>
  <li>
    <div class="dept-sub">
        <a href="#99">Access Services</a>
        <a href="#132">Facilities</a>
        <a href="#113">Inter-Library Loan & Course Reserves</a>
        <a href="#106">Metadata & Discovery Services</a>
        <a href="#143">Systems Administration</a>
        <a href="#129">Systems Support</a>
        <a href="#140">Web & Application Development</a>
    </div>
  </li>
  
   <li><a href="#125" class="dept-heading">Learning & Research Services</a></li>
   <li>
    <div class="dept-sub">
        <a href="#107">Digital Media Lab</a>
        <a href="#105">Judi Prokop Newman Business Information Resource Center</a>
        <a href="#103">Marta and Austin Weeks Music Library & Technology Center</a>
        <a href="#117">Paul Buisson Architecture Library</a>
        <a href="#119">Rosenstiel School of Marine Science & Atmospheric Science Library</a>
    </div>
   </li>
  
  <li><a href="#109" class="dept-heading">Cuban Heritage Collection</a></li>
  <li><a href="#104" class="dept-heading">Special Collections</a></li>
  <li><a href="#133" class="dept-heading">University Archives</a></li>
  
</ul>';

// views set in StaffDisplay.php
$our_cats = array("Departments","Subject Librarians");

// sets initial default view
if (!isset($_GET["letter"]) || $_GET["letter"] == "") {$_GET["letter"] = "Departments";}

$selected_letter = scrubData($_GET["letter"]);

$alphabet = getLetters($our_cats, $selected_letter);

$staff_data = new StaffDisplay();
$display = $staff_data->writeTable($selected_letter);

// header
include("includes/header_um-new.php");
?>

<div class="feature section">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
        <hr align="center" class="hr-panel">
        <p class="mb-0"><?php print $legend; ?></p>

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon" ></div>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php print $alphabet; ?>
        <div class="row">
            <div class="col-lg-8 order-last order-lg-first">
                <?php print $display;  ?>
            </div>
            <div class="col-lg-4 order-first order-lg-last">
                <div class="feature popular-list">
                    <?php print $dept_intro;  ?>
                </div>
            </div>
        </div>

    </div>
</section>


<?php
//if ($selected_letter == "By Department") {
 //   print $dept_intro;
//}

//if ($selected_letter == "A-Z") {
 //   $intro = "";
//}

?>

<script type="text/javascript">

    //Clear filter A-Z
    $('.clear-filter').click(function (e) {
        e.preventDefault();
        $('.filter-status').val('');
        $('.footable').trigger('footable_clear_filter');
    });

</script>

<?php
// Footer
include("includes/footer_um-new.php"); ?>