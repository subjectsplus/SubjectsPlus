<?php
/**
 *   @file faq.php
 *   @brief Create, modify, delete FAQs
 *
 *   @author adarby
 *   @date mar 2011
 *
 */

use SubjectsPlus\Control\FAQ;

$subcat = "faq";
$page_title = "Manage FAQ";

// Suppress header if it is to be shown in colorbox or popup window
if (isset($_REQUEST["wintype"]) && $_REQUEST["wintype"] == "pop") {
  $no_header = "yes";
}

include("../includes/header.php");

// Connect to database
try {
  } catch (Exception $e) {
  echo $e;
}


// Test our record_id, if it exists; must be integer
if (isset($_GET["faq_id"])) {
  $check_id = is_numeric($_GET["faq_id"]);
} else {
  $check_id = FALSE;
}

if ($check_id == TRUE) {
  $ok_record_id = $_GET["faq_id"];
} else {
  $ok_record_id = "";
}

if (isset($_POST["delete_record"]) || isset($_GET["delete_record"])) {
  // make sure there's a record_id

  if ($ok_record_id != "") {
    // do the delete
    $record = new FAQ($ok_record_id, "delete");
    $record->deleteRecord();
    //$record->deBug();
    // Show feedback
    $feedback = $record->getMessage();
    // don't display the form again
    $no_form = TRUE;
  } else {
    $feedback = "There is no record by that ID.";
  }
}

if (isset($_POST["submit_record"])) {

  // Submit form

  $record = new FAQ($_POST["faq_id"], "post");

  //////////////////////////////////
  // Is this an Insert or an update?
  //////////////////////////////////

  if ($_POST["faq_id"] == "") {
    $record->insertRecord();
    $ok_record_id = $record->getRecordId();
  } else {
    $record->updateRecord();
  }

  // Show feedback
  $feedback = $record->getMessage();
  // See all the queries?
  //$record->deBug();
}

$record = new FAQ($ok_record_id);

// show feedback if it isn't already set
if (!isset($feedback)) {
  $feedback = $record->getMessage();
}

if (isset($_GET["wintype"])) {
  if ($_GET["wintype"] == "pop") {
    print "<div id=\"maincontent\">";
  }
} else {
  $_GET["wintype"] = "";
}

echo feedBack($feedback);

/////////////////////////
// SHOW FORM
// If the form isn't suppressed, output it.  It will be blank if there's no id
/////////////////////////

if (!isset($no_form)) {
  $record->outputForm($_GET["wintype"]);
}

//$record->deBug();

include("../includes/footer.php");
?>

<script type="text/javascript">

  $(function (){

    ////////////////
    // Check Submit
    // When the form has been submitted, check required fields
    ////////////////

    $("#new_record").submit( function () {

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
      }

    });

    //////////////////
    // Recommend delete
    //////////////////
    $('.recommend_delete').livequery('click', function(event) {
      $(this).next().load("faq_bits.php", {type: 'recommend_delete', our_id: '<?php print $ok_record_id; ?>'}).fadeIn(1600);

      return false;
    });

    //////////////////
    // Make sure that delete was intentional
    //////////////////
    $('body').on('click', '.delete-button', function(event) {

      $(this).after('<div class="rec_delete_confirm"><?php print $rusure; ?>  <a id="confirm-yes-<?php print $ok_record_id; ?>"><?php print $textyes; ?></a> | <a id="confirm-no"><?php print $textno; ?></a></div>');

      return false;
    });


    $('a[id*=confirm-yes]').livequery('click', function(event) {
      var this_record_id = $(this).attr("id").split("-");
      // see if we're in a popup window, and tweak string accordingly
      var pop_status = "<?php print $_GET["wintype"]; ?>";

      var delete_url = "faq.php?faq_id=" + this_record_id[2] + "&delete_record=true&wintype=" + pop_status;

      window.location.replace(delete_url);

      return false;

    });

    $('a[id*=confirm-no]').livequery('click', function(event) {

      $(this).parent().remove();

      return false;
    });

    ///////////////////////////////////
    // add subject to list on dropdown
    ///////////////////////////////////

    $('select[name*=subject_id]').livequery ('change', function() {
      var our_sub_id = $(this).val();

      if (!our_sub_id) { return false;} // Only add subjects with values

      var our_sub_text = $('select[name*=subject_id] :selected').text();

      $('#subject_list').prepend('<div class="new_subject-'+our_sub_id+'"></div>');
      $('.new_subject-'+our_sub_id+'').hide().load("faq_bits.php", {type: 'add_subject', our_sub_id: our_sub_id, our_sub_text: our_sub_text}).fadeIn(1600);

    });

    ///////////////////////////////////
    // add collection to list on dropdown
    ///////////////////////////////////

    $('select[name*=collection_id]').livequery ('change', function() {

      var our_coll_id = $(this).val();

      if (!our_coll_id) { return false;} // Only add subjects with values

      var our_coll_text = $('select[name*=collection_id] :selected').text();

      $('#collection_list').prepend('<div class="new_collection-'+our_coll_id+'"></div>');
      $('.new_collection-'+our_coll_id+'').hide().load("faq_bits.php", {type: 'add_collection', our_sub_id: our_coll_id, our_sub_text: our_coll_text}).fadeIn(1600);

    });

    ///////////////////
    // delete a subject/collection from dropdown
    ///////////////////

    $(".delete_sub").livequery('click', function() {

      $(this).parent().parent().remove();

    });



  });


</script>
