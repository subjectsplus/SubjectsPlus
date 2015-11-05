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
		" . $db->quote(scrubData($_POST["source"])) . ")";

  
  $rInsertdiscipline = $db->exec($qInsertdiscipline);

  if ($rInsertdiscipline) {
    $feedback = _("Thy Will Be Done.  Discipline list updated.");
  } else {
    echo $qInsertdiscipline;
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
  
  foreach ($result as $key => $value) {
    $qUpDept = "UPDATE discipline SET
		discipline = " . $db->quote(scrubData($value)) . ", 
		sort = " . $row_count . " 
		WHERE discipline_id = " . scrubData($key, "integer");
    
    $rUpDept = $db->exec($qUpDept);

    $row_count++;
  }

  
  $feedback = _("Thy Will Be Done.  discipline list updated.");
  
  
}

///////////////
// disciplines
///////////////

$querierDept = new Querier();
$qdiscipline = "select discipline_id, discipline, sort from discipline order by sort, discipline";
$disciplineArray = $querierDept->query($qdiscipline);

foreach ($disciplineArray as $value) {

  $ourlist .= "<li id=\"item-$value[0]\" class=\"sortable_item disc-sortable\"><a id=\"delete-$value[0]\"><i class=\"fa fa-times fa-lg\"></i></a> &nbsp; <input type=\"text\" size=\"40\" name=\"discipline[]\" value=\"$value[1]\" /> <input type=\"hidden\" name=\"discipline_id[]\" value=\"$value[0]\" /></li>";
}


$discipline_box = "
<form id=\"disciplines\" action=\"\" method=\"post\">
<button class=\"button\" id=\"save_guide\"  class=\"button pure-button pure-button-primary\" style=\"display: block;\" name=\"update_disciplines\" >" . _("SAVE CHANGES") . "</button>

<p>" . _("NOTE:  Disciplines were added to facilitate Serials Solution ingest of data.  This original set was provided by SerSol in Nov 2012. 
    If you are a SerSol customer, you might not want to change these.  Sort may or may not be implemented in your version of SP.") . "</p>
<p>" . _("Enter discipline type label.") . "</p>
<br />

<ul id=\"sortable-\" class=\"sortable_list\">
$ourlist
</ul>
</form>
";

$add_discipline_box = "
<form id=\"new_discipline\" action=\"\" method=\"post\">
<span class=\"record_label\">" . _("Source Name") . "</span><br />
<input type=\"text\" name=\"source\" id=\"\" size=\"40\" class=\"\" value=\"\">
<br /><br />
<button class=\"button\" id=\"add_discipline\" name=\"add_discipline\">" . _("Add New Discipline") . "</button>
</form>";

print feedBack($feedback);

print "

<form id=\"disciplines\" action=\"\" method=\"post\">

<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
";

makePluslet(_("Disciplines"), $discipline_box, "no_overflow");

print "</div>
<div class=\"pure-u-1-3\">";

makePluslet(_("Add Discipline"), $add_discipline_box, "no_overflow");


print "</div>"; // close pure-u-
print "</div>"; // close pure


include("../includes/footer.php");
?>
<script type="text/javascript">
 jQuery(document).ready(function() {

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

 
     
     var delete_id = $(this).attr("id").split("-");
     var item_id = delete_id[1];

     var confirm_yes = "confirm-yes-" + item_id;

     jQuery("#item-"+item_id).append("<div class=\"confirmer\"><?php print $rusure; ?><div class=\"confirm_yes button\"id=\"" + confirm_yes + "\"><?php print $textyes; ?></div> <div class=\"confirm_no button\" id=\"confirm-no-"+item_id+"\"><?php print $textno; ?></div></div>");
     
   });




   jQuery('.pluslet_body').on('click', '.confirm_yes', 
			function(event) {
 
       console.log("ahhh!");
       var delete_id = $(this).attr("id").split("-");
       var this_id = delete_id[2];
       
       // Remove the confirm zone, and the row from the table
       jQuery(this).parent().remove();
       jQuery("#item-"+this_id).remove();

       jQuery.post("admin_bits.php", 
			   {
	   action: 'delete_discipline', 
	   delete_id:this_id
	 },
			   
			   function() {
           jQuery(".feedback").fadeIn();
	 });
       
       
     });
   
   // Person doesn't wish to change/delete item; remove confirm zone.
   jQuery('.pluslet_body').on('click', '.confirm_no' , function(event) {
     jQuery(this).parent().remove();
     
   });



   
   
   
 });
</script>
