<?php
/**
 *   @file services/staff_details.php
 */

use SubjectsPlus\Control\Querier;

$subfolder = "services";

$db = new Querier;
$connection = $db->getConnection();

// Get array of acceptable users

$q = "SELECT email FROM staff WHERE user_type_id = '1' and active = '1'";

$statement = $connection->prepare($q);
$statement->execute();
$r = $statement->fetchAll();

foreach ($r as $okemail) {
    $names = explode("@", $okemail[0]);
    $ok_names[] = $names[0];
}

// Check if our user-submitted name is okay & active; else redirect to staff listing page

if (isset($_GET['name']) && in_array(($_GET['name']), $ok_names)) {
    // use the submitted name
    $check_this = $_GET['name'];
} else {
    // use the first good email address
    header("location:{$PublicPath}staff.php");
}

//////////////// Here's the Sidebar, be careful with the syntax! //////////////
// $staffpath is necessary because of mod_rewriting screwing up paths
$StaffPath = $PublicPath . "staff.php";

$statement = $connection->prepare("SELECT s.staff_id, lname, fname, title, tel, s.email, d.name, bio, subject_id
FROM staff s
LEFT JOIN department d on s.department_id = d.department_id
LEFT JOIN staff_subject ss ON s.staff_id = ss.staff_id
WHERE s.email LIKE ?
GROUP BY s.lname");

$statement->execute(array("$check_this@%"));
$staffmem = $statement->fetchAll();

$tel = !empty($tel_prefix) ? $tel_prefix . " " . $staffmem[0][4] : $tel_prefix . $staffmem[0][4];

$fullname = $staffmem[0][2] . " " . $staffmem[0][1];

$info = "<div class=\"staff-profile d-sm-flex flex-sm-row flex-sm-wrap\">
    <div class=\"staff_photo\">
        <img src=\"" . $UserPath . "/_$check_this/headshot_large.jpg\" alt=\"Picture: {$staffmem[0][2]} {$staffmem[0][1]}\"
title=\"Picture: {$staffmem[0][2]} {$staffmem[0][1]}\" />
    </div>
    <div class=\"staff-meta\">
        <h2>$fullname</h2>
        <p><em>{$staffmem[0][3]}</em></p>
        <p><a href=\"mailto:{$staffmem[0][5]}\">{$staffmem[0][5]}</a></p>
        <p>$tel</p>
    </div>";

if ($staffmem[0][7] != "") {
    $info .= "<div class=\"staff-bio\">" . $staffmem[0][7] . "</div>";
}

$info .= "</div>";

// If it's a ref librarian, show their subjects
$subject_listing = ""; // init in case they don't have subs
$li_subject_listing = "";


// Get a list of subjects for this person
if ($staffmem[0][8] != "") {

    $statement = $connection->prepare("SELECT s.subject_id, subject, shortform
                                        FROM staff_subject ss, subject s
                                        WHERE ss.subject_id = s.subject_id
	                                    AND ss.staff_id = :staff_id
	                                    AND active = '1'
	                                    AND s.type = 'Subject'
	                                    ORDER BY subject");

    $statement->bindParam(":staff_id", $staffmem[0][0]);
    $statement->execute();
    $r = $statement->fetchAll();

    $total_rows = count($r);
    $per_row = ceil($total_rows / 2);

    $row_count = 0;

    $subject_listing = "<p><strong>Subject Liaison for . . . </strong></p>";

    foreach ($r as $mysubs) {

        $linky = "/subjects/guide.php?subject=" . $mysubs[2];

        // li subject listing
        $li_subject_listing .= "<li><a href=\"$linky\">$mysubs[1]</a></li>";

        $subject_listing .= "<a href=\"$linky\">{$mysubs[1]}</a><br /> ";

        $row_count++;
    }
}

// Assemble the content for our main pluslet
$display = $info;

$page_title = _("Staff Profile: ") . $fullname;

//header
include("includes/header_um-new.php");
?>

<div class="feature section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h5 class="mt-3 mt-lg-0 mb-1"><a href="../staff.php" class="no-decoration default">Staff List</a></h5>
        <h1><?php print $page_title; ?></h1>
        <hr align="center" class="hr-panel">

        <div class="favorite-heart">
            <div id="heart" title="Add to Favorites" tabindex="0" role="button" data-type="favorite-page-icon"
                 data-item-type="Pages" alt="Add to My Favorites" class="uml-quick-links favorite-page-icon" ></div>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php print $display; ?>
            </div>
            <div class="col-lg-4">
                <?php if ($li_subject_listing != "") { ?>
                    <div class="feature popular-list">
                    <h4>- Subject Areas -</h4>
                    <ul>
                        <?php print $li_subject_listing; ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php
// Footer
include("includes/footer_um-new.php");  ?>