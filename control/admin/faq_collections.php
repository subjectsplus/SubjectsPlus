<?php
/**
 *   @file faq_collections.php
 *   @brief CRUD collections
 *
 *   @author adarby
 *   @date march 2011
 *
 */
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin FAQ Collections";

//print_r($_POST);

include("../includes/header.php");

//init
$ourlist = "";
$feedback = "";

if (isset($_POST["add_collection"])) {

    ////////////////
    // Insert title table
    ////////////////

    $qInsert = "INSERT INTO faqpage (name, description) VALUES (
		'" . mysql_real_escape_string(scrubData($_POST["new_coll_name"])) . "', ''
		)";

    $rInsert = mysql_query($qInsert);

    if ($rInsert) {
        $feedback = _("Thy Will Be Done.  Updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong with the insert.  Contact the admin.");
    }
}

if (isset($_POST["update_collections"])) {

    //////////////////////////////////
    // Get the source dept data + sort order
    //////////////////////////////////
    //////////////////////
    // Create new array of results
    /////////////////////

    $a = $_POST["faqpage_id"];
    $b = $_POST["name"];

    $result = array_combine($a, $b);

    // Loop through array, update table

    $error = "";

    foreach ($result as $key => $value) {
        $qUp = "UPDATE faqpage SET
		name = '" . mysql_real_escape_string(scrubData($value)) . "'
		WHERE faqpage_id = " . scrubData($key, "integer");

        //print $qUp;
        $rUp = mysql_query($qUp);

        if (!$rUp) {
            $error = 1;
        }
    }

    if ($error != 1) {
        $feedback = _("Thy Will Be Done.  Updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong with the update.  Contact the admin.");
    }
}



///////////////
// Collections
///////////////

$querierDept = new sp_Querier();
$q = "select faqpage_id, name from faqpage order by name";
$resultArray = $querierDept->getResult($q);

if ($resultArray) {
    foreach ($resultArray as $value) {

        $ourlist .= "<p id=\"item-$value[0]\" style=\"margin-bottom: 1em;\"><a id=\"delete-$value[0]\"><img src=\"$IconPath/delete.png\" class=\"pointer\" /></a> &nbsp; <input type=\"text\" size=\"40\" name=\"name[]\" value=\"$value[1]\" /> <input type=\"hidden\" name=\"faqpage_id[]\" value=\"$value[0]\" /></p>";
    }
}

print "
<div class=\"feedback\">$feedback</div><br /><br />
<form id=\"sources\" action=\"\" method=\"post\">
<div id=\"savour\" style=\"clear: both;float:left; \">
	<div id=\"save_zone\" style=\"\">
		<button class=\"button\" id=\"save_guide\" name=\"update_collections\" >" . _("SAVE CHANGES") . "</button>
	</div>

</div>
<br />
<div class=\"box\" style=\"clear: both; float: left; min-width: 500px;\">
<p>" . _("Edit label or delete collection.") . "</p>
<br />
$ourlist
</form>
</div>
<div style=\"float: left; margin-left: 1em;\">
<div class=\"box\">
    <h2 class=\"bw_head\">" . _("Add Collection") . "</h2>

<form id=\"new_collection\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("Collection Name") . "</span><br />
<input type=\"text\" name=\"new_coll_name\" id=\"\" size=\"40\" class=\"required_field\" value=\"\">
<br /><br />
<button class=\"button\" id=\"add_collection\" name=\"add_collection\">" . _("Add New Collection") . "</button>
</form>
<div>";


include("../includes/footer.php");
?>
<script type="text/javascript">
    $(function() {


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

            $(".feedback").load("admin_bits.php", {action: 'delete_collection', delete_id:this_id},
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
