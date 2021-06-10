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

$this_fname = "staff.php";
$that_fname = theme_file($this_fname, $subjects_theme);
if ( $this_fname != $that_fname ) { include($that_fname); exit; }


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

$intro = "<p><i class=\"fa fa-info-circle\" aria-hidden=\"true\"></i> " . _("Click on a name for more information.") ."</p><br />";

}

$staff_data = new StaffDisplay();
$out = $staff_data->writeTable($selected_letter);


// Assemble the content for our main pluslet
$display = $alphabet . $intro . $out;

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include(theme_file("includes/header.php", $subjects_theme));

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

<?php

////////////
// Footer
///////////

include(theme_file("includes/footer.php", $subjects_theme));

?>
