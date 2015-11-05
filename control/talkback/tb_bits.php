<?php

/**
 *   @file faq_bits.php
 *   @brief Inserting elements via .load into faq.php
 *
 *   @author adarby
 *   @date
 *   @todo scrub post vars
 */

use SubjectsPlus\Control\Mailer;
use SubjectsPlus\Control\MailMessage;

$subcat = "talkback";
$page_title = "TB Bits include";
$header = "noshow";

include("../includes/header.php");

// get our limit, if there is one
// Keep it safe by only accepting certain values

switch ($_POST["limit"]) {
  case "10":
  case "50":
      $our_limit = "LIMIT 0," . $_POST["limit"];
    break;
  case "all":
      $our_limit = "";
    break;
}
$querierTBYES = new Querier();
$qTBYES = "SELECT talkback_id, question, q_from, date_submitted, DATE_FORMAT(date_submitted, '%b %D %Y') as date_formatted, answer, a_from, display, last_revised_by, tbtags
    FROM talkback
    WHERE tbtags LIKE '%" . $_POST["filter"] . "%'
    ORDER BY date_submitted DESC
    $our_limit";

$tbArrayYes = $querierTBYES->query($qTBYES);

$tb_yes_answer = genTalkBacks($tbArrayYes, 1);

print $tb_yes_answer;

//print_r($_POST);

switch ($_POST["filter"]) {
  case "add_collection":

    $subject_name = Truncate($_POST["our_sub_text"], 25, '');

    echo "<div class=\"selected_item_wrapper\"><div class=\"selected_item\">
            <input type=\"hidden\" name=\"collection[]\" value=\"" . $_POST["our_sub_id"] . "\" />
            " . $subject_name . "</div>
        <div class=\"selected_item_options\"><i class=\"fa fa-times\" class=\"delete_sub\" alt=\"delete\"></i>
        </div></div>";

    break;
  case "add_subject":

    $subject_name = Truncate($_POST["our_sub_text"], 25, '');

    echo "<div class=\"selected_item_wrapper\"><div class=\"selected_item\" \">
            <input type=\"hidden\" name=\"subject[]\" value=\"" . $_POST["our_sub_id"] . "\" />
            " . $subject_name . "</div>
        <div class=\"selected_item_options\"><i class=\"fa fa-times\" class=\"delete_sub\" alt=\"delete\"></i>
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

/////////////////
// genTalkBacks
// format our tb data
////////////////

function genTalkBacks($tbArray, $show_response = 1) {
  global $IconPath;

  $row_count1 = 0;
  $row_count2 = 0;
  $colour1 = "evenrow";
  $colour2 = "oddrow";
  $tb_answer = "";

  foreach ($tbArray as $value) {
    $row_colour = ($row_count2 % 2) ? $colour1 : $colour2;

    if ($value[2]) {
      $q_from = $value[2];
    } else {
      $q_from = _("Anonymous");
    }

    if (isset($show_response) && $show_response == 0) {
      $first_div_width = "90%";
      $last_mod_tb = "";
    } else {
      $first_div_width = "45%";
      $short_answer = stripslashes(htmlspecialchars_decode(TruncByWord($value["answer"], 15)));
      $last_mod_tb = lastModded("talkback", $value["talkback_id"], 0, 1);
    }

    $short_question = Truncate($value["question"], 200);


    if ($last_mod_tb) {
      $mod_line = _("--") . $last_mod_tb;
    } else {
      $mod_line = "";
    }
    $tb_answer .= "
            <div style=\"clear: both; float: left;  padding: 3px 5px; width: 98%;\" class=\"striper $row_colour\">
                <div style=\"float: left; width: 32px; max-width: 5%;\"><a class=\"showcustom\" style=\"color: #333;\" href=\"talkback.php?talkback_id=$value[0]&amp;wintype=pop\"><i class=\"fa fa-pencil fa-lg\" alt=\"" . _("Edit") . "\"></i></a></div>
                <div style=\"float: left; width: $first_div_width;\">
                 <strong>Q:</strong> $short_question <span style=\"color: #666; font-size: 10px;\">($q_from, $value[date_formatted])</span>
                </div>";

    if (isset($show_response) && $show_response == 1) {
      $tb_answer .= "<div style=\"float: left; width: 45%; margin-left: 4%;\">
                 <strong>A:</strong> $short_answer <span style=\"color: #666; font-size: 10px;\">$mod_line</span>
                </div>
            ";
    }
    $tb_answer .= "</div>";


    $row_count2++;
  }
  return $tb_answer;
}

?>