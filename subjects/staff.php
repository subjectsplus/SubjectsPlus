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

use SubjectsPlus\Control\Querier;
    
$page_title = "Library Staff";
$description = "Library contact list.";
$keywords = "staff list, librarians, contacts";


include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");




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
<div class="pure-g-r">
<div class="pure-u-4-5">
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"><?php print _("Staff Listing"); ?></div>
        </div>
        <div class="pluslet_body">
            <?php print $display; ?>
        </div>
    </div>
</div>
<div class="pure-u-1-5">
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text">Other Information</div>
        </div>
        <div class="pluslet_body"> Could go right here.</div>
    </div>

    <br />

</div>
</div>
<?php

////////////
// Footer
///////////

include("includes/footer.php");

?>