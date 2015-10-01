<?php
/**
 *   @file metadata.php
 *   @brief Create the metadata for a guide (and thus, the guide); also update and delete
 *
 *   @author adarby
 *   @date Jan 2011; updated 2015 (and surely in the interim)
 */

use SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Dropdown;
$subcat = "guides";
$page_title = "Manage Guide Metadata";

$use_jquery = array("ui_styles");

// Suppress header if it is to be shown in colorbox or popup window
if (isset($_REQUEST["wintype"]) && $_REQUEST["wintype"] == "pop") {
  $no_header = "yes";
  $bonus_css = "relative";
}

include("../includes/header.php");

// The following is just for testing purposes, to turn on/off the delete functionality
//$_SESSION["eresource_mgr"] = 1;
// Connect to database
try {
} catch (Exception $e) {
  echo $e;
}


// Test our record_id, if it exists; must be integer

if (isset($_GET["subject_id"])) {
  $check_id = is_numeric($_GET["subject_id"]);

  if ($check_id == TRUE) {
    $ok_record_id = $_GET["subject_id"];
  } else {
    $ok_record_id = "";
  }
} else {
  $check_id = FALSE;
  $ok_record_id = "";
}

if (isset($_POST["delete_record"]) || isset($_GET["delete_record"])) {
  // make sure there's a record_id

  if ($ok_record_id != "") {
    // do the delete
    $record = new Guide($ok_record_id, "delete");
    $record->deleteRecord();
    //$record->deBug();
    // Show feedback
    $feedback = $record->getMessage();
    // don't display the form again
    $no_form = TRUE;
  } else {
    $feedback = _("There is no record by that ID.");
  }
  //exit;
}

if (isset($_POST["submit_record"])) {
    //$feedback = $record->getMessage();
   
    
  // 1.  Make sure we have minimum non-dupe data
  // 1a. Make sure there is a title, location, and subject
  // we're using [staff_id][1] because the first value is an empty "--Select--"

  if ($_POST["shortform"] == "" || $_POST["staff_id"][1] == "" || $_POST["type"][0] == "" || $_POST["subject"] == "") {

    echo "<div class=\"feedback\">" . _("You must have a title, shortform, type, and one associated staff member.  Please go back and fix these omissions.  And turn on JavaScript, for goodness sakes!") . "</div><br /><br />";

    exit;
  }
//print_r($_POST);exit;
  // Submit form

  $record = new Guide($_POST["subject_id"], "post");

  //////////////////////////////////
  // Is this an Insert or an update?
  //////////////////////////////////

  if ($_POST["subject_id"] == "") {
    $record->insertRecord();
    $ok_record_id = $record->getRecordId();
  } else {
    $record->updateRecord();
  }

  // Show feedback
  $feedback = $record->getMessage();
  // See all the queries?
  //$record->deBug();
    print   "<div class=\"master-feedback\" style=\"display:block;\">" . $feedback . "</div>";
}

if (!isset($no_form)) {
  $record = new Guide($ok_record_id);
}

// show feedback if it isn't already set
if (!isset($feedback)) {
  $feedback = $record->getMessage();
}



/////////////////////////
// SHOW FORM
// If the form isn't suppressed, output it.  It will be blank if there's no id
/////////////////////////


    
If (!isset($_REQUEST["wintype"])) {
  $_GET["wintype"] = "";
}

if (!isset($no_form)) {
  print "<div id=\"maincontent\">";
  $record->outputMetadataForm($_GET["wintype"]);
}

//$record->deBug();

print $record->getMessage();

print "</div>"; // end #maincontent

include("../includes/footer.php");
?>

<script type="text/javascript">

 $(function (){

   ///////////////////////////////////
   // add staffer to list on dropdown
   ///////////////////////////////////

   $('select[name*=staff_id]').livequery('change', function() {
     var our_item_id = $(this).val();
     var our_item_text = $('select[name*=staff_id] :selected').text();
     //var remove_me = ".box_no_overflow option[value=" + our_item_id = "]";
     var remove_me = $('#staff_menu option[value='+our_item_id+']');

     $('#item_list').prepend('<div class="new_item-'+our_item_id+'"></div>');
     $('.new_item-'+our_item_id+'').hide().load("../guides/helpers/guide_bits.php",
                                                {type: 'add_item', our_item_id: our_item_id, our_item_text: our_item_text}).fadeIn(1600);
     // now remove from the dropdown
     remove_me.remove();

   });

   ///////////////////////////////////
   // add parent to list on dropdown
   ///////////////////////////////////

   $('select[name*=parent_id]').livequery('change', function() {
     var our_item_id = $(this).val();
     var our_item_text = $('select[name*=parent_id] :selected').text();
     //var remove_me = ".box_no_overflow option[value=" + our_item_id = "]";
     var remove_me = $('#parent_menu option[value='+our_item_id+']');

     $('#parent_list').prepend('<div class="new_item-'+our_item_id+'"></div>');
     $('.new_item-'+our_item_id+'').hide().load("../guides/helpers/guide_bits.php",
                                                {type: 'add_parent', our_item_id: our_item_id, our_item_text: our_item_text}).fadeIn(1600);
     // now remove from the dropdown
     remove_me.remove();

   });

   ///////////////////////////////////
   // add department to list on dropdown
   ///////////////////////////////////

   $('select[name*=department_id]').livequery('change', function() {
     var our_item_id = $(this).val();
     var our_item_text = $('select[name*=department_id] :selected').text();
     var remove_me = $('#department_menu option[value='+our_item_id+']');
     //alert(our_item_text);
     $('#department_list').prepend('<div class="new_item-'+our_item_id+'"></div>');
     $('.new_item-'+our_item_id+'').hide().load("../guides/helpers/guide_bits.php",
                                                {type: 'add_department', our_item_id: our_item_id, our_item_text: our_item_text}).fadeIn(1600);
     // now remove from the dropdown
     remove_me.remove();

   });

   ///////////////////////////////////
   // add discipline to list on dropdown
   // this is for sersol; might not be used for your lib
   ///////////////////////////////////

   $('select[name*=discipline_id]').livequery('change', function() {
     var our_item_id = $(this).val();
     var our_item_text = $('select[name*=discipline_id] :selected').text();
     var remove_me = $('#metadata_menu option[value='+our_item_id+']');
     //alert(our_item_text);
     $('#discipline_list').prepend('<div class="new_item-'+our_item_id+'"></div>');
     $('.new_item-'+our_item_id+'').hide().load("../guides/helpers/guide_bits.php",
                                                {type: 'add_discipline', our_item_id: our_item_id, our_item_text: our_item_text}).fadeIn(1600);
     // now remove from the dropdown
     remove_me.remove();

   });

   ///////////////////
   // delete a staffmember
   ///////////////////

   $(".delete_staff").livequery('click', function() {

     // make sure this isn't the only location first
     var numsub = $(".staffwrapper").length;

     if (numsub >1) {

       $(this).parent().parent().remove();
     } else {
       alert("<?php print _("Thwarted!  You must have at least one staff member for a guide."); ?>");
     }

   });

   ///////////////////
   // delete a department
   ///////////////////

   $(".delete_department").livequery('click', function() {
       $(this).parent().parent().remove();
   });

   ///////////////////
   // delete a parent
   ///////////////////

   $(".delete_parent").livequery('click', function() {
       $(this).parent().parent().remove();
   });

   ///////////////////
   // delete a discipline
   ///////////////////

   $(".delete_discipline").livequery('click', function() {
       $(this).parent().parent().remove();
   });

   ////////////////
   // Check Submit
   // When the form has been submitted, check required fields
   ////////////////

   $("#new_record").submit( function () {

     // Check that there is at least one subject
     //    var numsub = $(".selected_item_wrapper").length;

     // if (numsub < 1) {
     //   alert("<?php print _("You must have at least one subject."); ?>");
     //  return false;
     // };

     // check required fields
     // make sure the record isn't a dupe--check for title and location

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



     if (alerter == 1) {
       alert("<?php print _("You must complete all required form fields."); ?>");
       return false;
     } else {
       ///////////////////////
       // now check that the shortform is unique and kill spaces
       ////////////////////////

       var shortform = $("#record_shortform").val();
       var subject_id = $('input[name=subject_id]').val();
       // clean up shortform

       // make sure it's unique

       var dupe_check = (function () {
         var dupe_check = null;
         $.ajax({
           'async': false,
           'global': false,
           'url': "helpers/guide_bits.php?type=test_shortform&value=" + shortform + "&subject_id=" + subject_id,
           'success': function (data) {
             dupe_check = data;
           }
         });
         return dupe_check;
       })();

       if (dupe_check == "dupe") {
         alert("<?php print _("There is already a guide with that short form!  Try again."); ?>");
         $("#record_shortform").attr_("style", "background-color:#FFDFDF");
         return false;
       } else {
         //alert(dupe_check);
         return true;
       }

     }

   });

   //////////////////
   // Make sure that delete was intentional
   //////////////////
   $('body').on('click', '.delete-guide' ,function(event) {

     $(this).after('<div class="rec_delete_confirm"><?php print $rusure; ?>  <a id="confirm-yes-<?php print $ok_record_id; ?>"><?php print $textyes; ?></a> | <a id="confirm-no"><?php print $textno; ?></a></div>');

     return false;
   });


   $('body').on('click', 'a[id*=confirm-yes]' , function(event) {
     var this_record_id = $(this).attr("id").split("-");
     // see if we're in a popup window, and tweak string accordingly
     var pop_status = "<?php if(isset($_REQUEST["wintype"]))print $_REQUEST["wintype"]; ?>";

     var delete_url = "metadata.php?subject_id=" + this_record_id[2] + "&delete_record=true&wintype=" + pop_status;

     top.location.href = delete_url;

     return false;

   });

   $('body').on('click','a[id*=confirm-no]', function(event) {

     $(this).parent().remove();

     return false;
   });


 });


</script>



