<?php
/**
 *   @file sources.php
 *   @brief CRUD sources
 *
 *   @author adarby
 *   @date feb 2011
 *   @todo
 */
use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\Querier;


$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Source Types";
//print_r($_POST);

include("../includes/header.php");

$db = new Querier;

//init
$ourlist = "";
$feedback = "";

if (isset($_POST["add_source"])) {

  ////////////////
  // Insert title table
  ////////////////

  $qInsertSource = "INSERT INTO source (source, rs) VALUES (
		" . $db->quote(scrubData($_POST["source"])) . ", 
		0
		)";

  $rInsertSource = $db->query($qInsertSource);

  $feedback = _("Thy Will Be Done.  Source list updated.");
  
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
		source = " . $db->quote(scrubData($value)) . ", 
		rs = " . $row_count . "
		WHERE source_id = " . scrubData($key, "integer");

    //print $qUpDept;
    $rUpDept = $db->query($qUpDept);

    if (!$rUpDept) {
      $error = 1;
    }

    $row_count++;
  }

  
    $feedback = _("Thy Will Be Done.  Source list updated.");
  
}



///////////////
// Sources
///////////////

$querierDept = new Querier();
$qSource = "select source_id, source, rs from source order by rs, source";
$sourceArray = $querierDept->query($qSource);

foreach ($sourceArray as $value) {

  $ourlist .= "<li id=\"item-$value[0]\" class=\"sortable_item\" style=\"margin-bottom: .5em;\"><a id=\"delete-$value[0]\"><i class=\"fa fa-times\" title=\"" . _("Remove") . "\"></i></a> &nbsp; <input type=\"text\" size=\"40\" name=\"source[]\" value=\"$value[1]\" /> <input type=\"hidden\" name=\"source_id[]\" value=\"$value[0]\" /></li>";
}

$source_box = "
<form id=\"sources\" action=\"\" method=\"post\">
<button class=\"button\" id=\"save_guide\"  class=\"button pure-button pure-button-primary\" style=\"display: block;\" name=\"update_sources\" >" . _("SAVE CHANGES") . "</button>

<p>" . _("Enter source type label.  Drag sources to change sort order.") . "</p>
<br />

<ul id=\"sortable-\" class=\"sortable_list\">
$ourlist
</ul>
</form>
";

$add_source_box = "
<form id=\"new_source\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("Source Name") . "</span><br />
<input type=\"text\" name=\"source\" id=\"\" size=\"40\" class=\"\" value=\"\">
<br /><br />
<button class=\"button\" id=\"add_source\" name=\"add_source\">" . _("Add New Source") . "</button>
</form>";



print feedBack($feedback);

print "

<form id=\"sources\" action=\"\" method=\"post\">

<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";

makePluslet(_("Sources"), $source_box, "no_overflow");

print "</div>
<div class=\"pure-u-1-3\">";

makePluslet(_("Add Source"), $add_source_box, "no_overflow");


print "</div>"; // close pure-u-
print "</div>"; // close pure

include("../includes/footer.php");
?>
<script type="text/javascript">
 jQuery(function() {

   ////////////////////////////
   // MAKE COLUMNS SORTABLE
   // Make "Save Changes" button appear on sorting
   ////////////////////////////
   // connectWith: '.sort-column',

   jQuery('ul[id*=sortable-]').each(function() {

     var update_id = $(this).attr("id").split("-");

     update_id = update_id[1];

    jQuery("#sortable-"+update_id).sortable({
       placeholder: 'ui-state-highlight',
       cursor: 'move',
       update: function() {
         jQuery("#response").hide();
         jQuery("#save_zone").fadeIn();
       }


     });
   });

   jQuery('a[id*=delete-]').on('click', function(event) {


     var delete_id = jQuery(this).attr("id").split("-");
     var item_id = delete_id[1];

     var confirm_yes = "confirm-yes-" + item_id;

     jQuery("#item-"+item_id).after("<div class=\"confirmer\"><?php print $rusure; ?><a id=\"" + confirm_yes + "\"><?php print $textyes; ?></a> | <a id=\"confirm-no-"+item_id+"\"><?php print $textno; ?></a></div>");

     return false;
   });


   jQuery('.pluslet_body').on('click','a[id*=confirm-yes-]', function(event) {

     var delete_id = jQuery(this).attr("id").split("-");
     var this_id = delete_id[2];

     // Remove the confirm zone, and the row from the table
     jQuery(this).parent().remove();
     jQuery("#item-"+this_id).remove();

     jQuery.post("admin_bits.php", {action: 'delete_source', delete_id:this_id},
			 function(response) {
	       console.log(response);
	       $('#feedback').remove();
	       jQuery("#maincontent").prepend('<div id="feedback">' + response + '</div>');
         jQuery("#feedback").show();
       });

     return false;
   });

   // Person doesn't wish to change/delete item; remove confirm zone.
   jQuery('.pluslet_body').on('click','a[id*=confirm-no-]', function(event) {
     jQuery(this).parent().remove();
     return false;
   });
 });
</script>
