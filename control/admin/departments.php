<?php
/**
 *   @file departments.php
 *   @brief CRUD departments
 *
 *   @author adarby
 *   @date feb 2011
 *   
 */
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Departments";
$feedback = "";

//print_r($_POST);

include("../includes/header.php");

// Connect to database
try {
    $dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}

if (isset($_POST["add_department"])) {

    ////////////////
    // Insert title table
    ////////////////

    $qInsertDept = "INSERT INTO department (name, telephone, department_sort, email, url) VALUES (
		'" . mysql_real_escape_string(scrubData($_POST["department"])) . "', 
		'" . mysql_real_escape_string(scrubData($_POST["telephone"])) . "', 
		'0',
        '" . mysql_real_escape_string(scrubData($_POST["email"])) . "', 
        '" . mysql_real_escape_string(scrubData($_POST["url"])) . "'
		)";

    $rInsertDept = mysql_query($qInsertDept);

    if ($rInsertDept) {
        $feedback = _("Thy Will Be Done.  Department list updated.");

    } else {
        $feedback = _("Thwarted!  Something has gone wrong.  Contact the admin.");
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
    $error = "";

    foreach ($result as $key => $value) {
        $qUpDept = "UPDATE department SET
        name = '" . mysql_real_escape_string(scrubData($value[0])) . "',
        telephone = '" . mysql_real_escape_string(scrubData($value[1])) . "',
        department_sort = '" . $row_count . "',
        email = '" . mysql_real_escape_string(scrubData($value[2])) . "',
        url = '" . mysql_real_escape_string(scrubData($value[3])) . "'
        WHERE department_id = " . scrubData($key, "integer");

        //print $qUpDept;
        $rUpDept = mysql_query($qUpDept);

        if (!$rUpDept) {
            $error = 1;
        }

        $row_count++;
    }

    if ($error != 1) {
        $feedback = _("Thy Will Be Done.  Department list updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong.  Contact the admin.");
    }

    // Show feedback
    //$feedback = $record->getMessage();
    // See all the queries?
    //$record->deBug();
}



///////////////
// Departments
///////////////

$querierDept = new sp_Querier();
$qDept = "select department_id, name, telephone, department_sort, email, url from department order by department_sort";
$deptArray = $querierDept->getResult($qDept);
$ourlist = "";

foreach ($deptArray as $value) {

    $ourlist .= "<li id=\"item-$value[0]\" class=\"sortable_item department-sortable\"><a id=\"delete-$value[0]\"><img src=\"$IconPath/delete.png\" class=\"pointer\" /></a>
  &nbsp; <input type=\"text\" size=\"40\" name=\"dept[]\" value=\"$value[1]\" /> 
  &nbsp; <input type=\"text\" size=\"10\" name=\"tel[]\" value=\"$value[2]\" /> 
  &nbsp; <input type=\"text\" size=\"20\" name=\"email[]\" value=\"$value[4]\" />
  &nbsp; <input type=\"text\" size=\"20\" name=\"url[]\" value=\"$value[5]\" />
  <input type=\"hidden\" name=\"dept_id[]\" value=\"$value[0]\" /></li>";
}

print "
<div class=\"feedback\">$feedback</div><br /><br />
<form id=\"departments\" action=\"\" method=\"post\">
<div id=\"savour\" class=\"department-save\">
	<div id=\"save_zone\">
		<button id=\"save_guide\" name=\"update_departments\" >" . _("SAVE CHANGES") . "</button>
	</div>
	
</div>
<br />
<div class=\"box department-box\">
<p>" . _("Enter department name, telephone number, email, website url.  Drag departments to change display order.") . "</p>
<br />

<ul id=\"sortable-\" class=\"sortable_list\">
$ourlist
</ul>
</form>
</div>
<div class=\"add-department">
<h2 class=\"bw_head\">" . _("Add Department") . "</h2>
<div class=\"box\">
<form id=\"new_deptartment\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("Department Name") . "</span><br />
<input type=\"text\" name=\"department\" id=\"\" size=\"40\" class=\"required_field\" value=\"\">
<br /><br />
<span class=\"record_label\">" . _("Telephone") . "</span><br />
<input type=\"text\" name=\"telephone\" id=\"\" size=\"10\" class=\"required_field\" value=\"\">
<br /><br />
<span class=\"record_label\">" . _("Email") . "</span><br />
<input type=\"text\" name=\"email\" id=\"\" size=\"20\" class=\"required_field\" value=\"\">
<br /><br />
<span class=\"record_label\">" . _("Website") . "</span><br />
<input type=\"text\" name=\"url\" id=\"\" size=\"40\" class=\"required_field\" value=\"\">
<br /><br />
<button id=\"add_dept\" name=\"add_department\" >" . _("Add New Department") . "</button>
</form>
</div>
<h2 class=\"bw_head\">" . _("View Live!") . "</h2>
<div class=\"box\">
<ul>
<li><a href=\"$PublicPath" . "/staff.php?letter=By Department\" target=\"_blank\">" . _("Staff by Department") . "</a></li>
</ul>
</div>
</div>";


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
                    $("#save_zone").fadeIn();
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

            $(".feedback").load("admin_bits.php", {action: 'delete_department', delete_id:this_id},
            function() {

                $(".feedback").fadeIn();
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