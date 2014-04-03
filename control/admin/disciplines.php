<?php
/**
 *   @file disciplines.php
 *   @brief CRUD disciplines.  These added for Serial Solutions ingest compatability
 *
 *   @author agdarby
 *   @date Dec 2012
 *   @todo
 */
use SubjectsPlus\Control\Querier;
$db = new Querier;

$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Discipline Types";

//print_r($_POST);

include("../includes/header.php");

//init
$ourlist = "";
$feedback = "";

if (isset($_POST["add_discipline"])) {

    ////////////////
    // Insert title table
    ////////////////

    $qInsertdiscipline = "INSERT INTO discipline (discipline) VALUES (
		'" . $db->quote(scrubData($_POST["discipline"])) . "')";

    $rInsertdiscipline = $db->query($qInsertdiscipline);

    if ($rInsertdiscipline) {
        $feedback = _("Thy Will Be Done.  Discipline list updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong.  Contact the admin.");
    }
}

if (isset($_POST["update_disciplines"])) {

    //////////////////////////////////
    // Get the discipline dept data + sort order
    //////////////////////////////////
    //////////////////////
    // Create new array of results
    /////////////////////

    $a = $_POST["discipline_id"];
    $b = $_POST["discipline"];

    $result = array_combine($a, $b);

    // Loop through array, update departments table

    $row_count = 1;
    $error = "";

    foreach ($result as $key => $value) {
        $qUpDept = "UPDATE discipline SET
		discipline = '" . $db->quote(scrubData($value)) . "', 
		sort = '" . $row_count . "' 
		WHERE discipline_id = " . scrubData($key, "integer");

        //print $qUpDept;
        $rUpDept = $db->query($qUpDept);

        if (!$rUpDept) {
            $error = 1;
        }

        $row_count++;
    }

    if ($error != 1) {
        $feedback = _("Thy Will Be Done.  discipline list updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong.  Contact the admin.");
    }
}



///////////////
// disciplines
///////////////

$querierDept = new Querier();
$qdiscipline = "select discipline_id, discipline, sort from discipline order by sort, discipline";
$disciplineArray = $querierDept->query($qdiscipline);

foreach ($disciplineArray as $value) {

    $ourlist .= "<li id=\"item-$value[0]\" class=\"sortable_item disc-sortable\"><a id=\"delete-$value[0]\"><img src=\"$IconPath/delete.png\" class=\"pointer\" /></a> &nbsp; <input type=\"text\" size=\"40\" name=\"discipline[]\" value=\"$value[1]\" /> <input type=\"hidden\" name=\"discipline_id[]\" value=\"$value[0]\" /></li>";
}

print "
<div class=\"feedback\">$feedback</div><br /><br />
<form id=\"disciplines\" action=\"\" method=\"post\">
<div id=\"savour\" class=\"department-save\">
	<div id=\"save_zone\">
		<button id=\"save_guide\" name=\"update_disciplines\" >" . _("SAVE CHANGES") . "</button>
	</div>
	
</div>
<br />
<div class=\"box disc-box\">
<p>" . _("NOTE:  Disciplines were added to facilitate Serials Solution ingest of data.  This original set was provided by SerSol in Nov 2012. 
    If you are a SerSol customer, you might not want to change these.  Sort may or may not be implemented in your version of SP.") . "</p>
<p>" . _("Enter discipline type label.") . "</p>
<br />

<ul id=\"sortable-\" class=\"sortable_list\">
$ourlist
</ul>
</form>
</div>
<div class=\"add-disc\">
<h2 class=\"bw_head\">" . _("Add discipline") . "</h2>
<div class=\"box\">
<form id=\"new_discipline\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("discipline Name") . "</span><br />
<input type=\"text\" name=\"discipline\" id=\"\" size=\"40\" class=\"required_field\" value=\"\">
<br /><br />
<button id=\"add_discipline\" name=\"add_discipline\">" . _("Add New discipline") . "</button>
</form>
<div>";


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

            $(".feedback").load("admin_bits.php", {action: 'delete_discipline', delete_id:this_id},
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
