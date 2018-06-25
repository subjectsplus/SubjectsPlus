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
            <li><a href="#106">Metadata & Discovery Services</a></li>
            <li><a href="#143">Systems</a>
            <ul>
            <li><a href="#129">Systems Support</a></li>
            </ul>
            </li>
            <li><a href="#140">Web & Application Development</a></li>
          </ul>
          <li><a href="#105">Judi Prokop Newman Business Information Resource Center</a></li>
          <li><a href="#125">Learning & Research Services</a></li>
          <ul>
            <li><a href="#107">Digital Media Lab</a></li>
            <li><a href="#118">Learning & Research Services</a></li>
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
        <?php print $display;  ?>
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