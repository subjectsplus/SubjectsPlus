<?php
/**
 *   @file guide_collections.php
 *   @brief CRUD collections of guides for display on public page
 *
 *   @author adarby
 *   @date Aug 2015
 *   
 */

use SubjectsPlus\Control\Querier;

    
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Guide Collections";
$feedback = "";

//var_dump($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");

// Connect to database
$db = new Querier;

if (isset($_POST["add_collection"])) {

    ////////////////
    // Insert title table
    ////////////////
    
    $qInsertGuideCollection = "INSERT INTO collection (title, description, shortform) VALUES (
		" . $db->quote(scrubData($_POST["title"])) . ", 
		" . $db->quote(scrubData($_POST["description"])) . ", 
        " . $db->quote(scrubData($_POST["shortform"])) . "
		)";
    //print $qInsertGuideCollection;

    $rInsertGuideCollection = $db->exec($qInsertGuideCollection);

    if ($rInsertGuideCollection) {
        $feedback = _("Thy Will Be Done.  Guide Collection list updated.");

    } else {
        $feedback = _("Thwarted!  Something has gone wrong with insert.  Contact the admin.");
    }
}

if (isset($_POST["update_collections"])) {

// get our vars and tidy them
$our_collection_id = scrubData($_POST["update_collections"]);

// remove all assocations for this collection + this suject

$qEmpty = "DELETE FROM collection_subject WHERE collection_id = '$our_collection_id'";
//print $qEmpty;
$rEmpty = $db->exec($qEmpty);

// insert the new subs and sort order

foreach ($_POST["subject_id"] as $key => $value) {
    $our_subject_id = scrubData($value);

    $qInsert = "INSERT INTO collection_subject (collection_id, subject_id, sort) VALUES ($our_collection_id, $our_subject_id, $key)";
    //print $qInsert . "<br />";
    $rInsert = $db->exec($qInsert);
}

    $feedback = _("Thy Will Be Done.  Guide Collections Updated.");
    // Show feedback
    //$feedback = $record->getMessage();
    // See all the queries?
    //$record->deBug();
}

///////////////
// Delete Collection
///////////////

if (isset($_GET["delete_id"])) {
    $our_collection_id = scrubData($_GET["delete_id"], "integer");

    // delete associations in collection_subject
    $qEmpty = "DELETE FROM collection_subject WHERE collection_id = '$our_collection_id'";
    //print $qEmpty;
    $rEmpty = $db->exec($qEmpty);

    // delete item from collection; leave the subjects alone!
    $qDeleteColl = "DELETE FROM collection WHERE collection_id = '$our_collection_id'";
    //print $qEmpty;
    $rDeleteColl = $db->exec($qDeleteColl);    

    $feedback = _("Thy Will Be Done.  Collection Deleted.");

}

///////////////
// subject list 
///////////////

$subs_option_boxes = getSubBoxes("", "", 1);

$all_guides = "
<select name=\"item\" id=\"guides\" size=\"1\">
<option value=\"\">" . _("-- Choose Guide --") . "</option>
$subs_option_boxes
</select>
<input type=\"button\" name=\"add_subject\" class=\"add_subject\" value=\"" . _("Add") . "\" />";

///////////////
// Collections
///////////////

$querierColl = new Querier();
$qColl = "select c.collection_id, c.title, c.description, c.shortform 
FROM collection c
group by c.title";
$CollArray = $querierColl->query($qColl);

$ourlist = "";

// Loop through all of the collections, putting each in a pluslet

foreach ($CollArray as $value) {

$ourlist .= "
  <div class=\"pluslet no_overflow\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">$value[1]</div>
      <div class=\"titlebar_options\"><a href=\"guide_collections.php?delete_id={$value[0]}\"><i class=\"fa fa-trash-o\" title=\"Remove item from this guide\"></i></a></div>
    </div>
    <div class=\"pluslet_body\">
    <p>(Note: Guide must be public to display on the list)</p>
    <p><em><strong>$value[2]</strong></em></p>
    $all_guides
    <form id=\"collections$value[0]\" action=\"\" method=\"post\">
    <ul id=\"sortable-$value[0]\" class=\"sortable_list\">
    ";

    // now get our subjects
    $querierSubject = new Querier();
    $qSubject = "SELECT s.subject_id, s.subject FROM subject s INNER JOIN collection_subject cs ON s.subject_id = cs.subject_id WHERE cs.collection_id = '$value[0]'";
    //print $qSubject;
    $subjectArray = $querierSubject->query($qSubject);

    foreach ($subjectArray as $value2) {
        $ourlist .= "<li id=\"item-$value[0]_$value2[0]\" class=\"sortable_item collection-sortable-$value2[0]\"><a id=\"delete-$value[0]_$value2[0]\"><i class=\"fa fa-times\" title=\"" . _("Remove") . "\"></i></a> $value2[1] 
         <input type=\"hidden\" name=\"subject_id[]\" value=\"$value2[0]\" />
        </li>";
    }
$ourlist .="
</ul>
<button class=\"button pure-button pure-button-primary\" name=\"update_collections\" value=\"$value[0]\" style=\"display: block;\" >" . _("SAVE CHANGES") . "</button>
</form>
</div></div>";

}

$ourlist .= "";

$add_help_box =  _("<p>You can create 'collections' as a way of grouping together similar guides, and presenting them separately from the 
    full list of guides.  E.g., you want guides only for your music library.</p><p>You can add a new collection below, and then add guides
    to your collection via the dropdown list.  You can rearrange the order of guides within a collection by dragging them.</p>");

$add_collection_box = "<form id=\"new_collection\" action=\"\" class=\"pure-form pure-form-stacked\" method=\"post\">
<label for=\"title\">" . _("Collection Name") . "</label>
<input type=\"text\" name=\"title\"size=\"40\" class=\"required_field\">
<label for=\"description\">" . _("Description") . "</label>
 <textarea name=\"description\" id=\"description\" rows=\"4\" cols=\"50\"></textarea>
 <label for=\"url\">" . _("Shortform") . "</label>
<input type=\"text\" name=\"shortform\" size=\"20\"  class=\"required_field\">
<button class=\"button pure-button pure-button-primary\" id=\"add_collection\" name=\"add_collection\" >" . _("Add New Collection") . "</button>
</form>";

$view_Colls_box = "<ul>
<li><a href=\"$PublicPath" . "collection.php\" target=\"_blank\">" . _("Guide Collections") . "</a></li>
</ul>";

print feedBack($feedback);
print "<div class=\"sort_feedback\"></div>";

print "

<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";
print $ourlist;
  //makePluslet(_("Departments"), $Coll_box, "no_overflow");

print "</div>
<div class=\"pure-u-1-3\">";

makePluslet(_("Instructions/Help"), $add_help_box, "no_overflow");

makePluslet(_("Collection"), $add_collection_box, "no_overflow");

makePluslet(_("View Live!"), $view_Colls_box, "no_overflow");

print "</div>"; // close pure-u-
print "</div>"; // close pure

include("../includes/footer.php");
?>
<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>

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

            return false;
        });

        // Person doesn't wish to change/delete item; remove confirm zone.
        $('a[id*=confirm-no-]').livequery('click', function(event) {
            $(this).parent().remove();
            return false;
        });

        // this is to add the selected subject from the DOM to the sortable LI
        $('.add_subject').click(function() {

            var our_subject = $(this).parent().find(":selected").text();
            var our_id = $(this).parent().find(":selected").val();
            var our_collection_id = $(this).parent().find('ul').attr('id').split("-");
            /*
            alert(our_subject);
            alert(our_id);
            alert(our_collection_id[1]);
            */

            var our_string = '<li id="item-' + our_collection_id[1] + '_' + our_id + '" class="sortable_item collection-sortable-' 
            + our_id + '"><a id="delete-' + our_collection_id[1] + '_' + our_id + '"><i class="fa fa-times" title="<?php print _("Remove") ?>"></i></a><input type="hidden" value="' + our_id + '" name="subject_id[]"> ' + our_subject + ' </li>';

            // add in a new subject to the dom
            $(this).parent().find('ul').append(our_string);
            // show save button
            //$("button[name*=update_collections").show();
        });

        ////////////////
        // Check Submit
        // When the form has been submitted, check required fields
        ////////////////

        $("#new_collection").submit( function () {

            // If a required field is empty, set zonk to 1, and change the bg colour
            // of the offending field
            var alerter = 0;

            $("*[class*=required_field]").each(function() {
                // get contents of string, trim off whitespace
                var our_contents = $(this).val();
                var our_contents  = jQuery.trim(our_contents );

                if (our_contents  == '') {
                    $(this).attr("style", "background-color:#FFDFDF");
                    alerter = 1;
                } else {
                    $(this).attr("style", "background-color:none");
                }

                return alerter;

            });

            // Popup warning if required fields not complete
            if (alerter == 1) {
                alert("<?php print _("You must complete all required form fields."); ?>");
                return false;
            }

        });        

    });
</script>
