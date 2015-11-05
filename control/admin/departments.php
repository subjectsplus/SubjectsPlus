<?php
/**
 *   @file departments.php
 *   @brief CRUD departments
 *
 *   @author adarby
 *   @date feb 2011
 *   
 */

use SubjectsPlus\Control\Querier;

    
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Departments";
$feedback = "";

//print_r($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");
// Connect to database
$db = new Querier;

if (isset($_POST["add_department"])) {

    ////////////////
    // Insert title table
    ////////////////
    
    $qInsertDept = "INSERT INTO department (name, telephone, department_sort, email, url) VALUES (
		" . $db->quote(scrubData($_POST["department"])) . ", 
		" . $db->quote(scrubData($_POST["telephone"])) . ", 
		0,
        " . $db->quote(scrubData($_POST["email"])) . ", 
        " . $db->quote(scrubData($_POST["url"])) . "
		)";

    $rInsertDept = $db->exec($qInsertDept);

    if ($rInsertDept) {
        $feedback = _("Thy Will Be Done.  Department list updated.");

    } else {
        $feedback = _("Thwarted!  Something has gone wrong with insert.  Contact the admin.");
    }
}

if (isset($_POST["update_departments"])) {

    //////////////////////////////////
    // Get the new dept data + sort order
    //////////////////////////////////
    // wipe out existing departments
    //////////////////////
    // Create new array of results
    /////////////////////

    $a = $_POST["dept_id"];
    $b = $_POST["dept"];
    $c = $_POST["tel"];
    $d = $_POST["email"];
    $e = $_POST["url"];


    $result = array();
    $values = array($b, $c, $d, $e);

    foreach ($a as $index => $key) {
        $t = array();
        foreach ($values as $value) {
            $t[] = $value[$index];
        }
        $result[$key] = $t;
    }

    /* 	print "<pre>";
      print_r($result);
      print "</pre>"; */

    // Loop through array, update departments table

    $row_count = 1;
    $error = FALSE;

    foreach ($result as $key => $value) {
        $qUpDept = "UPDATE department SET
        name = " . $db->quote(scrubData($value[0])) . ",
        telephone = " . $db->quote(scrubData($value[1])) . ",
        department_sort = " . $row_count . ",
        email = " . $db->quote(scrubData($value[2])) . ",
        url = " . $db->quote(scrubData($value[3])) . "
        WHERE department_id = " . scrubData($key, "integer");

        $rUpDept = $db->exec($qUpDept);

        $row_count++;

    }

    $feedback = _("Thy Will Be Done.  Department list order updated.");
    // Show feedback
    //$feedback = $record->getMessage();
    // See all the queries?
    //$record->deBug();
}



///////////////
// Departments
///////////////

$querierDept = new Querier();
$qDept = "select department_id, name, telephone, department_sort, email, url from department order by department_sort";
$deptArray = $querierDept->query($qDept);
$ourlist = "";

foreach ($deptArray as $value) {

    $ourlist .= "<li id=\"item-$value[0]\" class=\"sortable_item department-sortable\"><a id=\"delete-$value[0]\"><i class=\"fa fa-times\" title=\"" . _("Remove") . "\"></i></a>
  &nbsp; <input type=\"text\" size=\"40\" name=\"dept[]\" value=\"$value[1]\" /> 
  &nbsp; <input type=\"text\" size=\"10\" name=\"tel[]\" value=\"$value[2]\" /> 
  &nbsp; <input type=\"text\" size=\"20\" name=\"email[]\" value=\"$value[4]\" />
  &nbsp; <input type=\"text\" size=\"20\" name=\"url[]\" value=\"$value[5]\" />
  <input type=\"hidden\" name=\"dept_id[]\" value=\"$value[0]\" /></li>";
}

$dept_box ="
<p>" . _("Enter department name, telephone number, email, website url.  Drag departments to change display order.") . "</p>
<button id=\"save_guide\" class=\"button pure-button pure-button-primary\" style=\"display: block;\" name=\"update_departments\" >" . _("SAVE CHANGES") . "</button>
<form id=\"departments\" action=\"\" method=\"post\">

<ul id=\"sortable-\" class=\"sortable_list\">
$ourlist
</ul>
</form>";

$add_dept_box = "<form id=\"new_department\" action=\"\" class=\"pure-form pure-form-stacked\" method=\"post\">
<label for=\"department\">" . _("Department Name") . "</label>
<input type=\"text\" name=\"department\" id=\"\" size=\"40\" value=\"\">

<label for=\"telephone\">" . _("Telephone") . "</label>
<input type=\"text\" name=\"telephone\" id=\"\" size=\"10\" value=\"\">

<label for=\"email\">" . _("Email") . "</label>
<input type=\"text\" name=\"email\" id=\"\" size=\"20\" value=\"\">

<label for=\"url\">" . _("Website") . "</label>
<input type=\"text\" name=\"url\" id=\"\" size=\"40\" value=\"\">
<p></p>
<button class=\"button pure-button pure-button-primary\" id=\"add_dept\" name=\"add_department\" >" . _("Add New Department") . "</button>
</form>";

$view_depts_box = "<ul>
<li><a href=\"$PublicPath" . "/staff.php?letter=By Department\" target=\"_blank\">" . _("Staff by Department") . "</a></li>
</ul>";

print feedBack($feedback);
print "<div class=\"sort_feedback\"></div>";

print "

<form id=\"departments\" action=\"\" method=\"post\">

<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";

  makePluslet(_("Departments"), $dept_box, "no_overflow");

print "</div>
<div class=\"pure-u-1-3\">";

makePluslet(_("Add Department"), $add_dept_box, "no_overflow");

makePluslet(_("View Live!"), $view_depts_box, "no_overflow");

print "</div>"; // close pure-u-
print "</div>"; // close pure

include("../includes/footer.php");
?>
<script type="text/javascript">
    $(function() {

        ////////////////////////////
        // MAKE COLUMNS SORTABLE
        // Make "Save Changes" button appear on sorting
        ////////////////////////////
        // connectWith: '.sort-column',

        $('ul[id*=sortable-]').each(function() {

            var update_id = $(this).attr("id").split("-");

            update_id = update_id[1];

            $("#sortable-"+update_id).sortable({
                placeholder: 'ui-state-highlight',
                cursor: 'move',
                update: function() {
                    $("#response").hide();
                    $("#savour").fadeIn();
                }


            });
        });

        $('a[id*=delete-]').livequery('click', function(event) {


            var delete_id = $(this).attr("id").split("-");
            var item_id = delete_id[1];

            var confirm_yes = "confirm-yes-" + item_id;

            $("#item-"+item_id).after("<div class=\"confirmer\"><?php print $rusure; ?>  <a id=\"" + confirm_yes + "\"><?php print $textyes; ?></a> | <a id=\"confirm-no-"+item_id+"\"><?php print $textno; ?></a></div>");

            return false;
        });


        $('a[id*=confirm-yes-]').livequery('click', function(event) {

            var delete_id = $(this).attr("id").split("-");
            var this_id = delete_id[2];

            // Remove the confirm zone, and the row from the table
            $(this).parent().remove();
            $("#item-"+this_id).remove();

            $(".sort_feedback").load("admin_bits.php", {action: 'delete_department', delete_id:this_id},
            function() {

            $(".sort_feedback").fadeIn();

            });

            return false;
        });

        // Person doesn't wish to change/delete item; remove confirm zone.
        $('a[id*=confirm-no-]').livequery('click', function(event) {
            $(this).parent().remove();
            return false;
        });


    });
</script>
