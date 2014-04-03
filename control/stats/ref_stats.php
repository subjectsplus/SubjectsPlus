<?php

/**
 *   @file ref_stats.php
 *   @brief Transactions statistics
 *
 *   @author adarby
 *   @date mar 2014
 */


use SubjectsPlus\Control\RefStat;
$subcat = "stats";
$page_title = "Stats in SP";

include("../includes/config.php");
include("../includes/header.php");


try {
  } catch (Exception $e) {
  echo $e;
}
// look for wildcard or array keys

// loop through the modes and find which one was submitted

// only choose those values
if (isset($_POST["mode_id"]) && $_POST["mode_id"] != "") {

  //var_dump($_POST);

  // Submit form

  $record = new RefStat("", "post");

  //////////////////////////////////

    $record->insertRecord();
    $ok_record_id = $record->getRecordId();

  // Show feedback
  $feedback = $record->getMessage();
  
  // See all the queries?
  //$record->deBug();

echo "<div class=\"feedback\">$feedback</div><br /><br />";

}

$record = new RefStat();
$record->outputForm();

include("../includes/footer.php");
?>

<script>
jQuery(document).ready(function(){

$('input[type="submit"]').click(function()
{
	var lstrName = $(this).attr('name');
	window.clickedSubmit = lstrName.replace('submit_record-', '');
});

$( "form#new_transaction" ).submit(function( evt ) {
	$('input[name="mode_id"]').val(window.clickedSubmit);
});

});


</script>