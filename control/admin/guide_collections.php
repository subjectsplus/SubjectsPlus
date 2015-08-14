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

print_r($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");

// Connect to database
$db = new Querier;

if (isset($_POST["add_collection"])) {

print "Yes, let's add a collection!";
exit;
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

if (isset($_POST["update_collections"])) {

print "Let's update the collections!";
return;
    //////////////////////////////////
    // Get the new associated subjects + sort order
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
// subject list 
///////////////

$subs_option_boxes = getSubBoxes("", "", 1);

$all_guides = "
<select name=\"item\" id=\"guides\" size=\"1\">
<option value=\"\">" . _("-- Choose Guide --") . "</option>
$subs_option_boxes
</select>
<input type=\"button\" name=\"add_subject\" class=\"add_subject\" value=\"" . _("Add Guide to this Collection") . "\" />";

///////////////
// Departments
///////////////

$querierDept = new Querier();
$qDept = "select c.collection_id, c.title, c.description, c.shortform 
FROM collection c INNER JOIN collection_subject cs ON c.collection_id = cs.collection_id INNER JOIN subject s 
ON cs.subject_id = s.subject_id 
group by c.title";
$deptArray = $querierDept->query($qDept);

$ourlist = "<form id=\"departments\" action=\"\" method=\"post\">";

// Loop through all of the collections, putting each in a pluslet

foreach ($deptArray as $value) {

$ourlist .= "
  <div class=\"pluslet no_overflow\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">$value[1]</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">
<p><em>$value[2]</em></p>
$all_guides
<ul id=\"sortable-$value[0]\" class=\"sortable_list\">
";

    // now get our subjects
    $querierSubject = new Querier();
    $qSubject = "SELECT s.subject_id, s.subject FROM subject s INNER JOIN collection_subject cs ON s.subject_id = cs.subject_id WHERE cs.collection_id = '$value[0]'";
    //print $qSubject;
    $subjectArray = $querierSubject->query($qSubject);

    foreach ($subjectArray as $value2) {
        $ourlist .= "<li id=\"item-$value[0]_$value2[0]\" class=\"sortable_item department-sortable-$value2[0]\">$value2[1] <a id=\"delete-$value[0]_$value2[0]\"><img src=\"$IconPath/delete.png\" class=\"pointer\" /></a>
         <input type=\"hidden\" name=\"subject_id-$value[0][]\" value=\"$value2[0]\" />
        </li>";
    }
$ourlist .="
</ul>
<p>" . _("Drag guides within collections to change display order.") . "</p>

<button value=\"$value[0]\" class=\"button pure-button pure-button-primary\" name=\"update_collections\" style=\"display: block;\" >" . _("SAVE CHANGES") . "</button>

</div></div>";

}

$ourlist .= "</form>";

$add_collection_box = "<form id=\"new_collection\" action=\"\" class=\"pure-form pure-form-stacked\" method=\"post\">
<label for=\"department\">" . _("Collection Name") . "</label>
<input type=\"text\" name=\"title\" id=\"\" size=\"40\" value=\"\">
<label for=\"description\">" . _("Description") . "</label>
 <textarea name=\"description\" id=\"description\" rows=\"4\" cols=\"70\"></textarea>
 <label for=\"url\">" . _("Shortform") . "</label>
<input type=\"text\" name=\"url\" id=\"\" size=\"20\" value=\"\">
<p></p>
<button class=\"button pure-button pure-button-primary\" id=\"add_collection\" name=\"add_collection\" >" . _("Add New Collection") . "</button>
</form>";

$view_depts_box = "<ul>
<li><a href=\"$PublicPath" . "/collection.php\" target=\"_blank\">" . _("Guide Collections") . "</a></li>
</ul>";

print feedBack($feedback);
print "<div class=\"sort_feedback\"></div>";

print "

<form id=\"departments\" action=\"\" method=\"post\">

<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";
print $ourlist;
  //makePluslet(_("Departments"), $dept_box, "no_overflow");

print "</div>
<div class=\"pure-u-1-3\">";

makePluslet(_("Collection"), $add_collection_box, "no_overflow");

makePluslet(_("View Live!"), $view_depts_box, "no_overflow");

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
            var our_collection_id = $(this).next('ul').attr('id').split("-");

            //alert(our_collection_id[1]);

            var our_string = '<li id="item-' + our_collection_id[1] + '_' + our_id + '" class="sortable_item department-sortable-' 
            + our_id + '">' + our_subject + ' <a id="delete-' + our_collection_id[1] + '_' + our_id + '"><img class="pointer" src="../../assets/images/icons/delete.png"></a><input type="hidden" value="' + our_id + '" name="subject_id-' + our_collection_id[1] + '[]"></li>';

            // add in a new subject to the dom
            $(this).next('ul').append(our_string);

        });

    });
</script>
