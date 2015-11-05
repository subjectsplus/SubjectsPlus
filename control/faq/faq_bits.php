<?php

use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\MailMessage;


/**
 *   @file faq_bits.php
 *   @brief Inserting elements via .load into faq.php
 *
 *   @author adarby
 *   @date
 *   @todo scrub post vars
 */
$subcat = "faq";
$page_title = "FAQ Bits include";
$header = "noshow";


include("../includes/header.php");

// Connect to database


//print_r($_POST);

switch ($_POST["type"]) {
    case "add_collection":

        $subject_name = Truncate($_POST["our_sub_text"], 25, '');

        echo "<div class=\"selected_item_wrapper\"><div class=\"selected_item\">
            <input type=\"hidden\" name=\"collection[]\" value=\"" . $_POST["our_sub_id"] . "\" />
            " . $subject_name . "</div>
        <div class=\"selected_item_options\"><i class=\"fa fa-times\" class=\"delete_sub\" alt=\"delete\" border=\"0\"></i>
        </div></div>";

        break;
    case "add_subject":

        $subject_name = Truncate($_POST["our_sub_text"], 25, '');

        echo "<div class=\"selected_item_wrapper\"><div class=\"selected_item\" \">
            <input type=\"hidden\" name=\"subject[]\" value=\"" . $_POST["our_sub_id"] . "\" />
            " . $subject_name . "</div>
        <div class=\"selected_item_options\"><i class=\"fa fa-times\" class=\"delete_sub\" alt=\"delete\" border=\"0\"></i>
        </div></div>";

        break;
    case "recommend_delete":
        $del_record = $CpanelPath . "faq/faq.php?faq_id=" . $_POST["our_id"];
        $message_body = "<p>" . _("The following record is recommended for delete") . ":</p>
            <p><a href=\"$del_record\">$del_record</a></p>";
        $messageParams = array('from' => $_SESSION['email'],
                                'to' => $administrator_email,
                                'subjectLine' => _("SubjectsPlus: FAQ Delete Recommendation"),
                                'content' => $message_body);
        $message = new MailMessage($messageParams);
        $mailer = new Mailer();
        $mailer->send($message);
        echo "<div class=\"rec_delete_confirm\">" . _("Delete request sent to ") . "$administrator_email</div>";
        break;
}
?>
