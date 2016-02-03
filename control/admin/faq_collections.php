<?php
/**
 *   @file faq_collections.php
 *   @brief CRUD collections
 *
 *   @author adarby
 *   @date 2011;  updated may 2014
 *
 */
    
use SubjectsPlus\Control\Querier;


$subsubcat = "";
$subcat = "admin";
$page_title = "Admin FAQ Collections";

// print_r($_POST);

include("../includes/header.php");
$db = new Querier;
//init
$ourlist = "";
$feedback = "";

if (isset($_POST["add_collection"])) {

    ////////////////
    // Insert title table
    ////////////////
    
    $qInsert = "INSERT INTO faqpage (name, description) VALUES (
		" . $db->quote(scrubData($_POST["new_coll_name"])) . ", ''
		)";

    $rInsert = $db->exec($qInsert);

    if ($rInsert !== FALSE) {
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
		name = " . $db->quote(scrubData($value)) . "
		WHERE faqpage_id = " . scrubData($key, "integer");

        //print $qUp;
        $rUp = $db->query($qUp);

        if (!$rUp) {
            $error = 1;
        }
    }

/*
    if ($error !== 1) {
        $feedback = _("Thy Will Be Done.  Updated.");
    } else {
        $feedback = _("Thwarted!  Something has gone wrong with the update.  Contact the admin.");
    }
*/

    $feedback = _("Thy Will Be Done.  Updated.");
}



///////////////
// Collections
///////////////

$querierDept = new Querier();
$q = "select faqpage_id, name from faqpage order by name";
$resultArray = $querierDept->query($q);

if ($resultArray) {
    foreach ($resultArray as $value) {

        $ourlist .= "<p id=\"item-$value[0]\" style=\"margin-bottom: 1em;\"><a id=\"delete-$value[0]\"><i class=\"fa fa-times\" title=\"" . _("Remove") . "\"></i></a> &nbsp; <input type=\"text\" size=\"40\" name=\"name[]\" value=\"$value[1]\" /> <input type=\"hidden\" name=\"faqpage_id[]\" value=\"$value[0]\" /></p>";
    }
}

$collection_box = "<form id=\"sources\" action=\"\" method=\"post\">
<button class=\"button\" id=\"save_guide\"  class=\"button pure-button pure-button-primary\" style=\"display: block;\" name=\"update_collections\" >" . _("SAVE CHANGES") . "</button>

<p>" . _("Edit label or delete collection.") . "</p>
$ourlist
</form>";

$add_collection_box = "<form id=\"new_collection\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("Collection Name") . "</span><br />
<input type=\"text\" name=\"new_coll_name\" id=\"\" size=\"40\" class=\"required_field\" value=\"\">
<br /><br />
<button class=\"button pure-button pure-button-primary\" id=\"add_collection\" name=\"add_collection\">" . _("Add New Collection") . "</button>
</form>
<div>";

///////////////
// Print 'er out
///////////////

echo feedBack($feedback);

print "
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">  
";

makePluslet(_("Current Collections"), $collection_box, "no_overflow");

print "</div>"; // close pure-u-2-3
print "<div class=\"pure-u-1-3\">";

makePluslet(_("Add Collection"), $add_collection_box, "no_overflow");

print "</div>"; // close pure-u-1-3
print "</div>"; // close pure-g


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


        jQuery('.pluslet_body').on('click','a[id*=confirm-yes-]', function(event) {

            var delete_id = $(this).attr("id").split("-");
            var this_id = delete_id[2];

             // Remove the confirm zone, and the row from the table
             jQuery(this).parent().remove();
             jQuery("#item-"+this_id).remove();
/*
            $(".feedback").load("admin_bits.php", {action: 'delete_collection', delete_id:this_id},
            function() {

                $(".feedback").fadeIn();
            });
*/

             jQuery.post("admin_bits.php", {action: 'delete_collection', delete_id:this_id},
                     function(response) {
                   console.log(response);
                   $('#feedback').remove();
                   jQuery("#maincontent").prepend('<div id="feedback">' + response + '</div>');
                 jQuery("#feedback").show();
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
