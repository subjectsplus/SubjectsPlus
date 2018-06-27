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
   
   <li class="dept-heading">Collections</li>
   <li>
    <div class="dept-sub">
        <a href="#109">Cuban Heritage Collection</a>
        <a href="#104">Special Collections</a>
        <a href="#133">University Archives</a>
    </div>
   </li>  
</ul>';

$dept_select = "<select id=\"select_dept\">
  <option></option>
  <optgroup label=\"Office of the Dean and University Librarian\">
      <option value=\"#101\" data-external=\"\">Office of the Dean and University Librarian</option>
      <option value=\"#141\" data-external=\"\">Creative Services</option>
      <option value=\"#102\" data-external=\"\">Financial Administration</option>
      <option value=\"#124\" data-external=\"\">Human Resources</option>
  </optgroup>

  <optgroup label=\"Collection Strategies and Scholarly Communication\">
    <option value=\"#122\" data-external=\"\">Collection Strategies and Scholarly Communication</option>
    <option value=\"#100\" data-external=\"\">Acquisitions</option>
    <option value=\"#128\" data-external=\"\">Preservation / Conservation</option>
  </optgroup>

  <optgroup label=\"Digital Strategies\">
    <option value=\"#130\" data-external=\"\">Digital Strategies</option>
    <option value=\"#110\" data-external=\"\">Digital Production</option>
  </optgroup>

  <optgroup label=\"Health Science Services\">
    <option value=\"http://calder.med.miami.edu/\" data-external=\"http://calder.med.miami.edu\">Louis Calder Memorial Library</option>
  </optgroup>

  <optgroup label=\"Information Systems &amp; Access\">
    <option value=\"#126\" data-external=\"\">Information Systems &amp; Access</option>
    <option value=\"#99\" data-external=\"\">Access Services</option>
    <option value=\"#132\" data-external=\"\">Facilities</option>
    <option value=\"#113\" data-external=\"\">Inter-Library Loan & Course Reserves</option>
    <option value=\"#106\" data-external=\"\">Metadata & Discovery Services</option>
    <option value=\"#143\" data-external=\"\">Systems Administration</option>
    <option value=\"#129\" data-external=\"\">Systems Support</option>
    <option value=\"#140\" data-external=\"\">Web & Application Development</option>
  </optgroup>

  <optgroup label=\"Learning & Research Services\">
    <option value=\"#125\" data-external=\"\">Learning & Research Services</option>
    <option value=\"#107\" data-external=\"\">Digital Media Lab</option>
    <option value=\"#105\" data-external=\"\">Judi Prokop Newman Business Information Resource Center</option>
    <option value=\"#103\" data-external=\"\">Marta and Austin Weeks Music Library & Technology Center</option>
    <option value=\"#117\" data-external=\"\">Paul Buisson Architecture Library</option>
    <option value=\"#119\" data-external=\"\">Rosenstiel School of Marine Science & Atmospheric Science Library</option>
  </optgroup>

  <optgroup label=\"Collections\">
    <option value=\"#109\" data-external=\"\">Cuban Heritage Collection</option>
    <option value=\"#104\" data-external=\"\">Special Collections</option>
    <option value=\"#133\" data-external=\"\">University Archives</option>    
  </optgroup>
</select>";

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
        <p class="mb-0"><a href="https://uml-e-wpapi.azurewebsites.net/wp-content/uploads/2018/04/UML_Org_Chart_January2018-v2.pdf" class="default">Organization Chart (pdf)</a></p>

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon" ></div>
        </div>
    </div>
</div>

<section class="search-area d-none d-lg-block">
    <div class="full-search">
        <div class="container text-center">
            <div class="search-group">
                <div id="uml-site-search-container"></div>
                <div class="adv-search d-none">
                    <a class="no-decoration default" href="#">Advanced Search</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-half-top">
    <div class="container">
        <?php print $alphabet; ?>

        <?php
        if ($selected_letter == "Departments") { ?>
            <div class="row">
                <div class="col-lg-8 order-last order-lg-first">
                    <?php print $display;  ?>
                </div>
                <div class="col-lg-4 order-first order-lg-last">
                    <div class="d-lg-none text-center">
                        <link href="<?php print $AssetPath;  ?>js/select2/select2.css" rel="stylesheet"/>
                        <script src="<?php print $AssetPath;  ?>js/select2/select2.js"></script>
                        <?php print $dept_select;  ?>
                    </div>
                    <div class="feature popular-list d-none d-lg-inline-block">
                        <?php print $dept_intro;  ?>
                    </div>
                </div>
            </div>
        <?php
            } else {
            print $display;
        }
        ?>

    </div>
</section>

<script>
    $( function(){
        // Select2 for Departments
        $('#select_dept').select2({
            width: "80%",
            containerCssClass: "tabs-select",
            dropdownCssClass: "tabs-select-dropdown",
            placeholder: "Select a department"
        });

        $("#select_dept").change(function() {

            // open external link on tab-select
            var option_external_link = $(this).find('option:selected').attr('data-external');

            if (option_external_link != "") {
                window.open(option_external_link, '_blank');
            }
            else {
                var dept_anchor = $(this).find('option:selected').val();
                console.log(dept_anchor);
                location = dept_anchor;
            }
        });
    });
</script>

<?php
// Footer
include("includes/footer_um-new.php"); ?>