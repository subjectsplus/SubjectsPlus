<?php
/**
 *   @file sources.php
 *   @brief CRUD sources
 *
 *   @author adarby
 *   @date feb 2011
 *   @todo
 */
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Source Types";

//print_r($_POST);

include("../includes/header.php");

//init
$ourlist = "";
$feedback = "";

if (isset($_POST["add_source"])) {

    ////////////////
    // Insert title table
    ////////////////

    $qInsertSource = "INSERT INTO source (source, rs) VALUES (
		'" . mysql_real_escape_string(scrubData($_POST["source"])) . "', 
		'0'
		)";

    $rInsertSource = mysql_query($qInsertSource);

    if ($rInsertSource) {
        $feedback = _("Thy Will Be Done.  Source list updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong.  Contact the admin.");
    }
}

if (isset($_POST["update_sources"])) {

    //////////////////////////////////
    // Get the source dept data + sort order
    //////////////////////////////////
    //////////////////////
    // Create new array of results
    /////////////////////

    $a = $_POST["source_id"];
    $b = $_POST["source"];

    $result = array_combine($a, $b);

    // Loop through array, update departments table

    $row_count = 1;
    $error = "";

    foreach ($result as $key => $value) {
        $qUpDept = "UPDATE source SET
		source = '" . mysql_real_escape_string(scrubData($value)) . "', 
		rs = '" . $row_count . "' 
		WHERE source_id = " . scrubData($key, "integer");

        //print $qUpDept;
        $rUpDept = mysql_query($qUpDept);

        if (!$rUpDept) {
            $error = 1;
        }

        $row_count++;
    }

    if ($error != 1) {
        $feedback = _("Thy Will Be Done.  Source list updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong.  Contact the admin.");
    }
}



///////////////
// Sources
///////////////

$querierDept = new sp_Querier();
$qSource = "select source_id, source, rs from source order by rs, source";
$sourceArray = $querierDept->getResult($qSource);

foreach ($sourceArray as $value) {

    $ourlist .= "<li id=\"item-$value[0]\" class=\"sortable_item department-sortable\"><a id=\"delete-$value[0]\"><img src=\"$IconPath/delete.png\" class=\"pointer\" /></a> &nbsp; <input type=\"text\" size=\"40\" name=\"source[]\" value=\"$value[1]\" /> <input type=\"hidden\" name=\"source_id[]\" value=\"$value[0]\" /></li>";
}

print "
<div class=\"feedback\">$feedback</div><br /><br />
<form id=\"sources\" action=\"\" method=\"post\">
<div id=\"savour\" class=\"department-save\">
	<div id=\"save_zone\" style=\"\">
		<button id=\"save_guide\" name=\"update_sources\" >" . _("SAVE CHANGES") . "</button>
	</div>
	
</div>
<br />
<div class=\"box\" class=\"department-box\">
<p>" . _("Enter source type label.  Drag sources to change sort order.") . "</p>
<br />

<ul id=\"sortable-\" class=\"sortable_list\">
$ourlist
</ul>
</form>
</div>
<div class=\"add-department\">
<h2 class=\"bw_head\">" . _("Add Source") . "</h2>
<div class=\"box\">
<form id=\"new_source\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("Source Name") . "</span><br />
<input type=\"text\" name=\"source\" id=\"\" size=\"40\" class=\"required_field\" value=\"\">
<br /><br />
<button id=\"add_source\" name=\"add_source\">" . _("Add New Source") . "</button>
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

            $(".feedback").load("admin_bits.php", {action: 'delete_source', delete_id:this_id},
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
