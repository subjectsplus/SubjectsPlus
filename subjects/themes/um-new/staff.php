<?php
/**
 *   @file services/staff.php
 *   @brief staff listings -- um theme override
 */
use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\StaffDisplay;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

global $PublicPath;
$staff_page_url = $PublicPath . 'staff.php';

$page_title = "Library Staff";
$description = "Library contact list.";
$keywords = "staff list, librarians, contacts";

$intro = "";
$dept_intro = '<ul class="list-unstyled dept-intro">
  <li><a href="'.$staff_page_url.'#101" class="dept-heading">Office of the Dean and University Librarian</a></li>
  <li>
    <div class="dept-sub">
      <a href="'.$staff_page_url.'#141">Creative Services</a>
      <a href="'.$staff_page_url.'#102">Financial Administration</a>
      <a href="'.$staff_page_url.'#124">Human Resources</a>
    </div>
  </li>
  
  <li><a href="'.$staff_page_url.'#122" class="dept-heading">Collection Strategies Division</a></li>
  <li>
    <div class="dept-sub">
        <a href="'.$staff_page_url.'#100">Acquisitions</a>
        <a href="'.$staff_page_url.'#128">Preservation Strategies</a>
        <a href="'.$staff_page_url.'#109">Cuban Heritage Collection</a>
        <a href="'.$staff_page_url.'#104">Special Collections</a>
        <a href="'.$staff_page_url.'#133">University Archives</a>
    </div>    
  </li>
  
  <li><a href="'.$staff_page_url.'#130" class="dept-heading">Digital Strategies Division</a></li>
  <li>
    <div class="dept-sub">
      <a href="'.$staff_page_url.'#110">Digital Production</a>  
      <a href="'.$staff_page_url.'#140">Web & Application Development</a>
    </div>
  </li>
  
  <li class="dept-heading">Health Sciences Division</li>
  <li>
    <div class="dept-sub">
        <a href="http://calder.med.miami.edu/">Louis Calder Memorial Library</a>
    </div>
  </li>
  
  <li><a href="'.$staff_page_url.'#126" class="dept-heading">Information Systems, Access &amp; Facilities Division</a></li>
  <li>
    <div class="dept-sub">
        <a href="'.$staff_page_url.'#99">Access Services</a>
        <a href="'.$staff_page_url.'#132">Facilities</a>
        <a href="'.$staff_page_url.'#113">Inter-Library Loan & Course Reserves</a>
        <a href="'.$staff_page_url.'#106">Metadata & Discovery Services</a>
        <a href="'.$staff_page_url.'#143">Systems Administration</a>
        <a href="'.$staff_page_url.'#129">Systems Support</a>        
    </div>
  </li>
  
   <li class="dept-heading">Learning & Research Services Division</li>
   <li>
    <div class="dept-sub">
        <a href="'.$staff_page_url.'#107">Creative Studio</a>
        <a href="'.$staff_page_url.'#105">Judi Prokop Newman Business Information Resource Center</a>
        <a href="'.$staff_page_url.'#125">Learning & Research Services</a>
        <a href="'.$staff_page_url.'#103">Marta and Austin Weeks Music Library & Technology Center</a>
        <a href="'.$staff_page_url.'#117">Paul Buisson Architecture Library</a>
        <a href="'.$staff_page_url.'#119">Rosenstiel School of Marine Science & Atmospheric Science Library</a>
    </div>
   </li>   
</ul>';

$dept_select = "<select id=\"select_dept\">
  <option></option>
  <optgroup label=\"Office of the Dean and University Librarian\">
      <option value=\"$staff_page_url#101\" data-external=\"\">Office of the Dean and University Librarian</option>
      <option value=\"$staff_page_url#141\" data-external=\"\">Creative Services</option>
      <option value=\"$staff_page_url#102\" data-external=\"\">Financial Administration</option>
      <option value=\"$staff_page_url#124\" data-external=\"\">Human Resources</option>
  </optgroup>

  <optgroup label=\"Collection Strategies Division\">
    <option value=\"$staff_page_url#122\" data-external=\"\">Collection Strategies</option>
    <option value=\"$staff_page_url#100\" data-external=\"\">Acquisitions</option>
    <option value=\"$staff_page_url#128\" data-external=\"\">Preservation Strategies</option>
    <option value=\"$staff_page_url#109\" data-external=\"\">Cuban Heritage Collection</option>
    <option value=\"$staff_page_url#104\" data-external=\"\">Special Collections</option>
    <option value=\"$staff_page_url#133\" data-external=\"\">University Archives</option> 
  </optgroup>

  <optgroup label=\"Digital Strategies Division\">
    <option value=\"$staff_page_url#110\" data-external=\"\">Digital Production</option>
    <option value=\"$staff_page_url#130\" data-external=\"\">Digital Strategies</option>    
    <option value=\"$staff_page_url#140\" data-external=\"\">Web & Application Development</option>
  </optgroup>

  <optgroup label=\"Health Sciences Division\">
    <option value=\"http://calder.med.miami.edu/\" data-external=\"http://calder.med.miami.edu\">Louis Calder Memorial Library</option>
  </optgroup>

  <optgroup label=\"Information Systems, Access &amp; Facilities Division\">
    <option value=\"$staff_page_url#126\" data-external=\"\">Information Systems, Access &amp; Facilities</option>
    <option value=\"$staff_page_url#99\" data-external=\"\">Access Services</option>
    <option value=\"$staff_page_url#132\" data-external=\"\">Facilities</option>
    <option value=\"$staff_page_url#113\" data-external=\"\">Inter-Library Loan & Course Reserves</option>
    <option value=\"$staff_page_url#106\" data-external=\"\">Metadata & Discovery Services</option>
    <option value=\"$staff_page_url#143\" data-external=\"\">Systems Administration</option>
    <option value=\"$staff_page_url#129\" data-external=\"\">Systems Support</option>    
  </optgroup>

  <optgroup label=\"Learning & Research Services Division\">    
    <option value=\"$staff_page_url#107\" data-external=\"\">Creative Studio</option>
    <option value=\"$staff_page_url#105\" data-external=\"\">Judi Prokop Newman Business Information Resource Center</option>
    <option value=\"$staff_page_url#125\" data-external=\"\">Learning & Research Services</option>
    <option value=\"$staff_page_url#103\" data-external=\"\">Marta and Austin Weeks Music Library & Technology Center</option>
    <option value=\"$staff_page_url#117\" data-external=\"\">Paul Buisson Architecture Library</option>
    <option value=\"$staff_page_url#119\" data-external=\"\">Rosenstiel School of Marine Science & Atmospheric Science Library</option>
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

<input id="jekyll-category" value="sp-staff" type="hidden">
<div class="feature section-minimal">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
        <hr align="center" class="hr-panel">
        <p class="mb-0"><a href="https://umlibraryeast.blob.core.windows.net/uploads/2018/07/UML-Org-Chart-October-2018-v2.pdf" class="default">Organization Chart (pdf)</a></p>

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
                <div class="uml-site-search-container"></div>
                <div class="adv-search d-none">
                    <a class="no-decoration default" href="#">Advanced Search</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-half-top">
    <div class="container">
        <div id="backtotop">
            <a href="#" class="default no-decoration">
                <i class="fas fa-arrow-alt-circle-up" title="Back to top"></i>
                <span>Top</span>
            </a>
        </div>
        <?php print $alphabet; ?>

        <?php
        if ($selected_letter == "Departments") { ?>
            <div class="row">
                <div class="col-lg-8 order-last order-lg-first  ">
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
            } else {
            print $display;
        }
        ?>

    </div>
</section>

<script>
    $(function () {
        var pxShow = 400;//height on which the button will show
        var fadeInTime = 1000;//how slow/fast you want the button to show
        var fadeOutTime = 1000;//how slow/fast you want the button to hide
        var scrollSpeed = 1000;//how slow/fast you want the button to scroll to top. can be a value, 'slow', 'normal' or 'fast'

        $(window).scroll(function(){
            if($(window).scrollTop() >= pxShow){
                $('#backtotop').fadeIn(fadeInTime);
            }else{
                $('#backtotop').fadeOut(fadeOutTime);
            }
        });

        // show all db details
        $('#backtotop a').click(function () {
           $('html, body').animate({scrollTop:0}, scrollSpeed);
        });


    });
</script>

<?php
// Footer
include("includes/footer_um-new.php"); ?>