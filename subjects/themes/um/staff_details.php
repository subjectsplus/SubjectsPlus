<?php
/**
 *   @file services/staff_details.php
 *   @brief  this is the um child theme
 *
 *   @author adarby
 *   @date 2014
 *   @todo
 */

use SubjectsPlus\Control\Querier;

$page_title = "Library Staff Details";
$subfolder = "services";

$db = new Querier;
    

// Get array of acceptable users

$q = "SELECT email FROM staff WHERE user_type_id = '1'";
$r = $db->query($q);

foreach ($r as $okemail) {

    $names = explode("@", $okemail[0]);
    $ok_names[] = $names[0];
}

// Check if our user-submitted name is okay; else use default
if (isset($_GET['name']) && in_array(($_GET['name']), $ok_names)) {
    // use the submitted name
    $check_this = $_GET['name'];
} else {
    // use the first good email address
    $check_this = $ok_names[0];
}

// agd 2011 using a LIKE in the $qstaffer query below
// this way it gets people with different email endings, e.g.,
// @miami.edu, @umiami.edu, @umail.miami.edu
//$full_email = $check_this . $email_key;

/* Set local variables */

//////////////// Here's the Sidebar, be careful with the syntax! //////////////
// $staffpath is necessary because of mod_rewriting screwing up paths
$StaffPath = $PublicPath . "staff.php";

$qstaffer = "SELECT s.staff_id, lname, fname, title, tel, s.email, d.name, bio, subject_id
FROM staff s
LEFT JOIN department d on s.department_id = d.department_id
LEFT JOIN staff_subject ss ON s.staff_id = ss.staff_id
WHERE s.email LIKE '$check_this@%'
GROUP BY s.lname";

//print $qstaffer;

$staffmem = $db->query($qstaffer);

$tel = $tel_prefix . $staffmem[0][4];

$fullname = $staffmem[0][2] . " " . $staffmem[0][1];

$info = "<img src=\"" . $UserPath . "/_$check_this/headshot_large.jpg\" alt=\"Picture: {$staffmem[0][2]} {$staffmem[0][1]}\"
title=\"Picture: {$staffmem[0][2]} {$staffmem[0][1]}\"  align=\"left\" class=\"staff_photo\" />
<p style=\"margin-top; 0; padding-top: 0; font-size: larger;\"><strong>$fullname</strong><br />
{$staffmem[0][3]}<br />
<img src=\"../assets/images/icons/email.gif\" style=\"vertical-align: bottom;\" />  <a href=\"mailto:$staffmem{[0][5]}\">{$staffmem[0][5]}</a><br />
<img src=\"../assets/images/icons/telephone.gif\" style=\"vertical-align: bottom;\" />  $tel";


$info .= "</p>";

if ($staffmem[0][7] != "") {
    $info .= "<br class=\"clear-both\" /><br />" . $staffmem[0][7];
}


// If it's a ref librarian, show their subjects
$subject_listing = ""; // init in case they don't have subs

if ($staffmem[0][8] != "") {

    // Get a list of subjects for this person
    // Maybe you could make a better query above to include this info

    $q = "SELECT s.subject_id, subject, shortform FROM staff_subject ss, subject s WHERE ss.subject_id = s.subject_id
	AND ss.staff_id = '" . $staffmem[0][0] . "'  AND active = '1'  AND s.type = 'Subject' ORDER BY subject";

    $r = $db->query($q);

    $total_rows = count($r);
    $per_row = ceil($total_rows / 2);

    $row_count = 0;
    $colour1 = "odd";
    $colour2 = "even";

    $subject_listing = "<p class=\"clear-both\"><br /><strong>Subject Liaison for . . . </strong></p>
<div style=\"float: left; width: 47%\">";

    foreach ($r as $mysubs) {

        if ($mod_rewrite == 1) {
            $linky = $mysubs[2];
        } else {
            $linky = "guide.php?subject=" . $mysubs[2];
        }

        if ($row_count == $per_row) {
            $subject_listing .= "</div><div style=\"float: left; width: 47%\">";
        }

        $subject_listing .= "<a href=\"$linky\">{$mysubs[1]}</a><br /> ";

        $row_count++;
    }

    $subject_listing .= "</div><br style=\"clear:both\" />";
}

// Assemble the content for our main pluslet
$display = $info . $subject_listing;

$page_title = _("Staff Listing: ") . $fullname;

////////////////////////////
// Now we are finally read to display the page
////////////////////////////

include("includes/header.php");

?>
<div class="pure-g">
<div class="pure-u-2-3">
    <div class="pluslet">
        <div class="titlebar">
            <div class="titlebar_text"></div>
        </div>
        <div class="pluslet_body">
<?php print $display; ?>
        </div>
    </div>
</div>
<div class="pure-u-1-3">
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