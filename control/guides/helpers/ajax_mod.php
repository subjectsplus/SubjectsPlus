<?php

/**
 *   @file ajax_mod.php
 *   @brief Handles jquery-ish moving and delete of information in manage_items.php.  (Called by that file)
 *
 *   @author adarby
 *   @date Sep 17, 2009
 */
$subsubcat = "";
$subcat = "guides";
$page_title = "Ajax mod include";
$header = "noshow";

use SubjectsPlus\Control\Querier;

include("../../includes/header.php");

//print_r($_REQUEST);

switch ($_POST["action"]) {
  case "delete_rank":
    $q = "DELETE FROM rank WHERE rank_id = '" . $_POST["delete_id"] . "'";

    //print $q;
    $r = $db->exec($q);

    if ($r !== FALSE ) {
      print _("This item has been removed from your guide");
    } else {
      print _("There was a problem with the delete");
    }

    return;
    break;

  case "update_rank":

    $error = "";
    $our_data = explode("&", $_POST["our_data"]);
    $this_sub_id = $_POST["subject_id"];


    // loop through our items
    // order them up from 0
    $count = 0;
    $source_id = "";
    $prev_source = "";

    foreach ($our_data as $value) {

      // check if we're still within the same subcat
      $ourvals = explode("=", $value);

      $source_id = $ourvals[0];
      $rank_id = $ourvals[1];

      // If the source_id is new, reset the counter
      if ($source_id != $prev_source) {
        $count = 0;
      }

      $q = "UPDATE rank SET rank = '$count' WHERE rank.rank_id = '$rank_id'";

      $r = $db->exec($q);

      if ($r === FALSE) {
        $error .= $q . "<br />";
      }

      $count++;
      $prev_source = $source_id;
    }


    if ($r !== FALSE && $error == "") {
      print _("Thy will be done.  Ranks updated.");
    } else {
      print _("There was a problem with your update.");
      print $error;
    }
    break;
}
?>