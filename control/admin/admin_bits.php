<?php
use SubjectsPlus\Control\Querier;

/**
 *   @file admin_bits.php
 *   @brief Inserting elements via .load into record.php, and updating staff table
 *
 *   @author adarby
 *   @date updated Dec 2012
 *   @todo
 */
$subsubcat = "";
$page_title = "Admin Bits include";
$header = "noshow";
include("../includes/header.php");


//print "<pre>";
//print_r($_REQUEST);
//print_r($_POST);

$extra_q = "";
$success = FALSE;
$message = "";

$db = new Querier;

switch ($_REQUEST["action"]) {
  case "staff_mod":

    if ($_POST["selected"]) {
      // setup our extra query bit
      $extra_q = "WHERE s.subject_id IN (";

      // change the subject_id
      foreach ($_POST["selected"] as $value) {
        $q = "UPDATE staff_subject SET staff_id = " . $_POST["filter_key"] . " WHERE subject_id = $value";
        $r = $db->exec($q);
          
        $extra_q .= $value . ",";
      }

      // trim off final comma
      $extra_q = substr($extra_q, 0, -1);
      $extra_q .= ")";

      $message = _("Thy Will Be Done.");
      $success = TRUE;
    } else {

      $message = _("You must select something first.");
    }

    break;

  case "type_mod":
    // make sure the array exists
    if ($_POST["selected"]) {

      // setup our extra query bit
      $extra_q = "WHERE s.subject_id IN (";

      switch ($_POST["filter_key"]) {
        case "Public":
          // change the active field
          foreach ($_POST["selected"] as $value) {
            $q = "UPDATE subject SET active = '1' WHERE subject_id = $value";
            $r = $db->exec($q);
            $extra_q .= $value . ",";
          }

          break;
        case "Suppressed":
          // change the active field
          foreach ($_POST["selected"] as $value) {
            $q = "UPDATE subject SET active = '2' WHERE subject_id = $value";
            $r = $db->exec($q);
            $extra_q .= $value . ",";
          }

          break;
        case "Hidden":
          // change the active field
          foreach ($_POST["selected"] as $value) {
            $q = "UPDATE subject SET active = '0' WHERE subject_id = $value";
            $r = $db->exec($q);
            $extra_q .= $value . ",";
          }

          break;
        default:
          // Here we're changing the type field in Subject table
          foreach ($_POST["selected"] as $value) {
            $q = "UPDATE subject SET type = '" . trim($_POST["filter_key"]) . "' WHERE subject_id = $value";
            $r = $db->exec($q);
            $extra_q .= $value . ",";
          }

          break;
      }

      // trim off final comma
      $extra_q = substr($extra_q, 0, -1);
      $extra_q .= ")";

      $message = _("Thy Will Be Done.  Selected records now ") . $_POST["filter_key"];
      $success = TRUE;
    } else {

      $message = _("You must select something first.");
    }
    break;

  case "guide_type":

    // deal with "all"
    switch ($_POST["filter_key"]) {
      case "All":
        $extra_q = "";
        break;
      case "Public":
        $extra_q = "WHERE s.active = '1' ";
        break;
      case "Hidden":
        $extra_q = "WHERE s.active = '0' ";
        break;
      case "Suppressed":
        $extra_q = "WHERE s.active = '2' ";
        break;
      default:
        $extra_q = "WHERE type = '" . trim($_POST["filter_key"]) . "' ";
        break;
    }


    break;
  case "staffer":

    // deal with "all"
    if ($_POST["filter_key"] != "All") {
      $extra_q = "WHERE st.staff_id= '" . trim($_POST["filter_key"]) . "' ";
    }



    break;
  case "update_permissions":
    // Update ptags for users.  Called by user.php?browse
    $qPtagger = "UPDATE staff set ptags = '" . scrubData($_POST["ptags"]) . "' WHERE staff_id = " . scrubData($_POST["update_id"], "integer");
    $rPtagger = $db->exec($qPtagger);

    // @UM_only
    $qPtagger2 = "UPDATE staff set ptags = '" . scrubData($_POST["ptags"]) . "' WHERE staff_id = " . scrubData($_POST["update_id"], "integer");
    $rPtagger2 = $db->exec($qPtagger2);

    //print $qPtagger;

    if (!$rPtagger) {
      print _("Error");
    } else {
      print _("Permissions Updated.");
    }
    return; // return early so we don't show the stuff that follows
    break;
  case "delete_department":

    // Make sure no one is associated with this department
    $qChecker = "SELECT * FROM staff, department WHERE staff.department_id = department.department_id AND department.department_id = " . scrubData($_POST["delete_id"], "integer");

    $rChecker = $db->query($qChecker);

   //print $qChecker;

    if (count($rChecker) != 0) {
      $message = _("Your request cannot be completed:  There are one or more librarians associated with this subject");
    } else {

      $qDeleteDept = "DELETE FROM department WHERE department_id = " . scrubData($_POST["delete_id"], "integer");

      $rDeleteDept = $db->exec($qDeleteDept);

      $message = _("Thy Will Be Done.  Department list updated.");
      
    }

    print "<div class=\"master-feedback\" style=\"display:block;\">$message</div>";

    return; // return early so we don't show the stuff that follows
    break;

  case "delete_collection":
    // Make sure no one is associated with this department
    $qChecker = "SELECT * FROM faqpage f, faq_faqpage ff
            WHERE f.faqpage_id = ff.faqpage_id
            AND f.faqpage_id = " . scrubData($_POST["delete_id"], "integer");
            //print $qChecker;
    $rChecker = $db->query($qChecker);

    //print $qChecker;

    if (count($rChecker) != 0) {
      print _("Your request cannot be completed:  There are one or more records linked to this item.  Please unlink them--you can find them under FAQ > Browse by Subject, Browse by Collection. ");
    } else {

      $qDelete = "DELETE FROM faqpage WHERE faqpage_id = " . scrubData($_POST["delete_id"], "integer");

      $rDelete = $db->exec($qDelete);

      if (!$rDelete) {
        echo blunDer("We have a problem with the delete source query: $qDelete");
      } else {
        print _("Thy Will Be Done.  Offending item removed.");
      }
    }
    return; // return early so we don't show the stuff that follows
    break;
  case "delete_source":

    // Make sure no one is associated with this source
    $qChecker = "SELECT * FROM rank, source WHERE rank.source_id = source.source_id AND source.source_id = " . scrubData($_POST["delete_id"], "integer");

    $rChecker = $db->query($qChecker);

    //print $qChecker;

    if (count($rChecker) != 0) {
      print _("Your request cannot be completed:  There are one or more records linked to this source");
    } else {

      $qDeleteSource = "DELETE FROM source WHERE source_id = " . scrubData($_POST["delete_id"], "integer");

      $rDeleteSource = $db->exec($qDeleteSource);

      if (!$rDeleteSource) {
        echo blunDer("We have a problem with the delete source query: $qDeleteSource");
      } else {
        print _("Thy Will Be Done.  Source list updated.");
      }
    }
    return; // return early so we don't show the stuff that follows
    break;

  case "delete_discipline":
    
    // Make sure no one is associated with this discipline
    $qChecker = "SELECT * FROM subject, subject_discipline WHERE subject.subject_id = subject_discipline.subject_id
    AND subject_discipline.discipline_id = " . scrubData($_POST["delete_id"], "integer");

        $rChecker = $db->query($qChecker);

    //print $qChecker;

    if (count($rChecker) != 0) {
      print _("Your request cannot be completed:  There are one or more records linked to this source");
    } else {

      $qDeleteD = "DELETE FROM discipline WHERE discipline_id = " . scrubData($_POST["delete_id"], "integer");

      $rDeleteD = $db->exec($qDeleteD);

      if (!$rDeleteD) {
        echo blunDer("We have a problem with the delete source query: $qDeleteD");
      } else {
        print _("Thy Will Be Done.  Discipline list updated.");
      }
    }
    return; // return early so we don't show the stuff that follows
    break;
  case "address_lookup":
    //print urlencode($_REQUEST["address"]);
    $endpoint = "http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($_REQUEST["address"]) . "&sensor=false";
    $address = curl_get($endpoint);
    //print $address;
    $output = json_decode($address);
    //print $output->results[0]->geometry->location->lat;
    $lat = $output->results[0]->geometry->location->lat;
    $long = $output->results[0]->geometry->location->lng;
    $coords = $lat . "," . $long;

    print $coords;

    //print $address;
    return; // return early so we don't show the stuff that follows

    break;
} // End Action loop
// Now generate results, $extra_q set in action loop above
$q = "SELECT s.subject_id, subject, fname, lname, st.staff_id, type, shortform, s.active
FROM subject s
LEFT JOIN staff_subject ss ON s.subject_id = ss.subject_id
LEFT JOIN staff st ON ss.staff_id = st.staff_id
$extra_q
ORDER BY subject";

$querier = new Querier();
$subsArray = $querier->query($q);

if (!empty($subsArray)) {

  // set up striping
  $row_count = 0;
  $colour1 = "oddrow";
  $colour2 = "evenrow";
  $staff_list = "";

  foreach ($subsArray as $value) {
    if ($value[7] != "1") {
      $active = " <span style=\"font-size:smaller; color: #666;\">" . _("inactive") . "</span>";
    } else {
      $active = "";
    }
    $row_colour = ($row_count % 2) ? $colour1 : $colour2;
    $staff_list .= "<div class=\"$row_colour striper\" style=\"clear: both; float: left; min-width: 500px;\"><input type=\"checkbox\" name=\"guide-$value[0]\" value=\"$value[0]\"><a class=\"showmedium-reloader\" href=\"../guides/metadata.php?subject_id=$value[0]&wintype=pop\"><i class=\"fa fa-gear fa-lg\" alt=\"" . _("delete") . "\"></i></a> &nbsp;&nbsp;
        <a target=\"_blank\" href=\"../../subjects/guide.php?subject=$value[0]\"><i class=\"fa fa-eye fa-lg\" alt=\"" . _("see live") . "\"></i></a> &nbsp;&nbsp;
        <a href=\"../guides/guide.php?subject_id=$value[0]\">$value[1]</a> $active</div> <div class=\"$row_colour striper\" style=\"float: left; min-width: 100px; font-size: smaller;\">$value[2] $value[3]</div>  <div class=\"$row_colour striper\" style=\"float: left; min-width: 75px;font-size: smaller;\">$value[5]</div>";
    $row_count++;
  }
} else {
  $staff_list = _("Sorry, there were no results.");
}

print "<div class=\"box_feedback\">$message</div><br /><br />";

print $staff_list;

include("../includes/footer.php");
