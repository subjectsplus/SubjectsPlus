<?php

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
try {
    $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}

//print_r($_POST);

switch ($_POST["type"]) {
    case "add_collection":

        $subject_name = Truncate($_POST["our_sub_text"], 25, '');

        echo "<div class=\"selected_item_wrapper\"><div class=\"selected_item\">
            <input type=\"hidden\" name=\"collection[]\" value=\"" . $_POST["our_sub_id"] . "\" />
            " . $subject_name . "</div>
        <div class=\"selected_item_options\"><img src=\"$IconPath/delete.png\" class=\"delete_sub\" alt=\"delete\" border=\"0\">
        </div></div>";

        break;
    case "add_subject":

        $subject_name = Truncate($_POST["our_sub_text"], 25, '');

        echo "<div class=\"selected_item_wrapper\"><div class=\"selected_item\" \">
            <input type=\"hidden\" name=\"subject[]\" value=\"" . $_POST["our_sub_id"] . "\" />
            " . $subject_name . "</div>
        <div class=\"selected_item_options\"><img src=\"$IconPath/delete.png\" class=\"delete_sub\" alt=\"delete\" border=\"0\">
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
        $message = new sp_MailMessage($messageParams);
        $mailer = new sp_Mailer();
        $mailer->send($message);
        echo "<div class=\"rec_delete_confirm\">" . _("Delete request sent to ") . "$administrator_email</div>";
        break;
}
?>
