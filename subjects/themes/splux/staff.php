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

$page_title = "People";
$description = "Library contact list.";
$keywords = "staff list, librarians, contacts";

$intro = "";
$dept_intro = '';

$dept_select = "";

// views set in StaffDisplay.php
$our_cats = array("A-Z", "By Department","Subject Librarians A-Z","Librarians by Subject Specialty");

// sets initial default view
if (!isset($_GET["letter"]) || $_GET["letter"] == "") {$_GET["letter"] = "A-Z";}

$selected_letter = scrubData($_GET["letter"]);

$alphabet = getLetters($our_cats, $selected_letter);

$staff_data = new StaffDisplay();
$display = $staff_data->writeTable($selected_letter);

// header
include( "includes/header_splux.php" );
?>

<div class="section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
    </div>
</div>

<section class="section section-half-top listing-staff">
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

        // back to top
        $('#backtotop a').click(function () {
           $('html, body').animate({scrollTop:0}, scrollSpeed);
        });

    });
</script>

<?php
// Footer
include( "includes/footer_splux.php" ); ?>