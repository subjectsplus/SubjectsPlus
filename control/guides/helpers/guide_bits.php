<?php

/**
 *   @file guide_bits.php
 *   @brief Inserting elements via .load
 *
 *   @author adarby
 *   @date
 *   @todo
 */

use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\MailMessage;

    
$subsubcat = "";
$subcat = "records";
$page_title = "Guide Bits include";
$header = "noshow";


include("../../includes/header.php");

// Connect to database


//print_r($_POST);

switch ($_REQUEST["type"]) {

    case "add_item":

        $item_name = scrubData($_POST["our_item_text"]);
        $item_id = scrubData($_POST["our_item_id"], 'integer');

        echo "
        <div style=\"margin-bottom: 10px !important;padding: 5px !important;\">
		<div class=\"selected_item\">
			<input name=\"staff_id[]\" value=\"$item_id\" type=\"hidden\" />
			$item_name<br />
		</div>
		<div class=\"selected_item_options\">
            <i class=\"fa fa-times delete_item delete_staff pointer\"  alt=\"delete\" title=\"remove\"></i>
		</div>
        </div>";

        break;
    case "add_parent":

        $item_name = scrubData($_POST["our_item_text"]);
        $item_id = scrubData($_POST["our_item_id"], 'integer');

        echo "
    <div class=\"selected_item_wrapper\">
        <div class=\"selected_item\">
            <input name=\"parent_id[]\" value=\"$item_id\" type=\"hidden\" />
            $item_name<br />
        </div>
        <div class=\"selected_item_options\">
            <i class=\"fa fa-times delete_item pointer\" alt=\"delete\" title=\"remove\"></i>
        </div>
    </div>";

    break;

    case "add_department":

        $item_name = scrubData($_POST["our_item_text"]);
        $item_id = scrubData($_POST["our_item_id"], 'integer');

        echo "
    <div class=\"selected_item_wrapper\">
        <div class=\"selected_item\">
            <input name=\"parent_id[]\" value=\"$item_id\" type=\"hidden\" />
            $item_name<br />
        </div>
        <div class=\"selected_item_options\">
            <i class=\"fa fa-times delete_item pointer\" alt=\"delete\" title=\"remove\"></i>
        </div>
    </div>";

        break;        
    case "add_discipline":
        $item_name = scrubData($_POST["our_item_text"]);
        $item_id = scrubData($_POST["our_item_id"], 'integer');

        echo "
    <div class=\"selected_item_wrapper\">
        <div class=\"selected_item\">
            <input name=\"discipline_id[]\" value=\"$item_id\" type=\"hidden\" />
            $item_name<br />
        </div>
        <div class=\"selected_item_options\">
            <i class=\"fa fa-times delete_item pointer\" alt=\"delete\" title=\"remove\"></i>
        </div>
    </div>";

        break;
    case "test_shortform":

        if ($_GET["subject_id"] == "") {
            // INSERT
            $qcheck = "SELECT shortform FROM subject WHERE shortform = '" . $db->quote(scrubData($_GET["value"])) . "'";
        } else {
            // UPDATE
            $qcheck = "SELECT shortform FROM subject WHERE shortform = '" . $db->quote(scrubData($_GET["value"])) . "' AND subject_id != '" . $db->quote(scrubData($_GET["subject_id"])) . "'";
        }

        //print $qcheck;
        $rcheck = $db->query($qcheck);

        if (count($rcheck) == 0) {
            echo "ok";
        } else {
            echo "dupe";
        }
        break;
    case "email_link_report":
        $message_body = stripslashes($_POST["linkresults"]);
        $subject_line = _("LinkChecker Results for ") . $_POST["shortform"];

        if ($_POST["sendto"] == "send_report2all") {
            $q = "SELECT subject, email
                FROM subject s, staff_subject ss, staff st
                WHERE s.subject_id = ss.subject_id
                AND ss.staff_id = st.staff_id
                AND s.shortform = '" . $_POST["shortform"] . "'";
            //print $q;

            $db = new Querier;
            $r = $db->query($q);

            foreach ($r as $row) {

                $mail_to .= $row[1] . ",";
            }

            $mail_to = trim($mail_to, ',');
        } else {
            $mail_to = $_SESSION["email"];
        }

        print "Sending mail to: $mail_to";
        //print_r($_POST);

        $messageParams = array('from' => $administrator_email,
            'to' => $mail_to,
            'subjectLine' => $subject_line,
            'content' => $message_body);
        $message = new MailMessage($messageParams);
        $mailer = new Mailer();
        $mailer->send($message);
        break;
    case "delete_file":


        $unlinky = "../../../assets/users/" . $_POST["folder_hint"] . "/" . $_POST["path"];
        $delete_it = unlink($unlinky);

        if ($delete_it) {
            print _("They Will Be Done:  Deleted.");
        }
        break;
}
?>
