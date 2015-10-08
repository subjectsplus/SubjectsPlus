<?php
   namespace SubjectsPlus\Control;
/**
 *   @file sp_Talkback
 *   @brief manage talkback submissions (called by talkback/index.php)
 *
 *   @author agdarby
 *   @date Jan 2011
 *   @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */
class Talkback {

  private $_talkback_id;
  private $_question;
  private $_q_from;
  private $_date_submitted;
  private $_answer;
  private $_a_from;
  private $_display;
  private $_tbtags;
  private $_cattags;
  private $_message;

  public function __construct($talkback_id="", $flag="") {

    //print_r($_POST);

    if ($flag == "" && $talkback_id == "") {
      $flag = "empty";
    }

    switch ($flag) {
      case "empty":
        $this->_a_from = $_SESSION["staff_id"];
        break;
      case "post":
        // prepare record for insertion or update
        // data stored in subject table
        $this->_talkback_id = $_POST["talkback_id"];
        $this->_question = $_POST["question"];
        $this->_q_from = $_POST["q_from"];
        $this->_date_submitted = "";
        $this->_answer = $_POST["answer"];
        $this->_a_from = $_POST["a_from"];
        $this->_display = $_POST["display"];
        $this->_tbtags = $_POST["tbtags"]; // array
        $this->_cattags = $_POST["cattags"]; // array
        break;
      case "delete":
        // kind of redundant, but just set up to delete appropriate tables?
        // $this->_staffers needed to see if they have permission to delete this record
        $this->_talkback_id = $talkback_id;
        $this->_staffers = array(0 => array($_SESSION["staff_id"], $_SESSION["fname"] . " " . $_SESSION["lname"]));

        break;
      default:

        $this->_talkback_id = $talkback_id;

        /////////////
        // Get tb table info
        /////////////

        $querier = new Querier();
        $q1 = "SELECT talkback_id, question, q_from, date_submitted, DATE_FORMAT(date_submitted, '%b %D %Y') as date_entered, answer, a_from, display, tbtags, cattags
                    FROM talkback WHERE talkback_id = " . $this->_talkback_id;
        $guideArray = $querier->query($q1);

        $this->_debug .= "<p>TB query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($guideArray == FALSE) {
          $this->_message = _("There is no active record with that ID.  Weird.");
        } else {
          $this->_question = $guideArray[0]["question"];
          $this->_q_from = $guideArray[0]["q_from"];

            // Let's set an anonymous for empty asker
            if ($this->_q_from == "") {$this->_q_from  = _("Anonymous");}

          $this->_date_submitted = $guideArray[0]["date_submitted"];
          $this->_answer = $guideArray[0]["answer"];
          $this->_date_entered = $guideArray[0]["date_entered"];
          $this->_a_from = $guideArray[0]["a_from"];
          $this->_display = $guideArray[0]["display"];
          $this->_tbtags = $guideArray[0]["tbtags"];
          $this->_cattags = $guideArray[0]["cattags"];
        }

        ///////////////////
        // Query Staff table
        // used to get our staff associated
        // ////////////////

        $querier2 = new Querier();
        $q2 = "SELECT s.staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff s, talkback tb WHERE s.staff_id = tb.a_from AND tb.talkback_id = " . $this->_talkback_id;

        $this->_staffers = $querier2->query($q2);

        $this->_debug .= "<p>Staff query: $q2";

        break;
    }
  }

  public function outputForm($wintype="") {

    global $wysiwyg_desc;
    global $CKPath;
    global $CKBasePath;
    global $IconPath;
    global $guide_types;
    global $all_tbtags;
  	global $all_cattags;

    //print "<pre>";print_r($this->_staffers); print "</pre>";

    $action = htmlentities($_SERVER['PHP_SELF']) . "?talkback_id=" . $this->_talkback_id;

    if ($wintype != "") {
      $action .= "&wintype=pop";
    }


    $tb_title_line = _("Edit TalkBack");

    echo "
<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" class=\"pure-form pure-form-stacked\" accept-charset=\"UTF-8\">
<input type=\"hidden\" name=\"talkback_id\" value=\"" . $this->_talkback_id . "\" />
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">$tb_title_line</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">

<label for=\"question\">" . _("Question") . "</label>
<textarea name=\"question\" rows=\"4\" cols=\"50\" class=\"required_field\">" . stripslashes($this->_question) . "</textarea>

<label for=\"a_from\">" . _("Question By") . "</label>
<input type=\"text\" name=\"q_from\" size=\"20\" class=\"required_field\" value=\"" . $this->_q_from . "\">

<label for=\"answer\">" . _("Answer") . "</label>";

    if ($wysiwyg_desc == 1) {
    	include($CKPath);
    	global $BaseURL;

    	$oCKeditor = new CKEditor($CKBasePath);
    	$oCKeditor->timestamp = time();
    	$config['toolbar'] = 'SubsPlus_Narrow';// Default shows a much larger set of toolbar options
    	$config['height'] = '200';
    	$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

    	echo $oCKeditor->editor('answer', $this->_answer, $config);
		echo "<br />";
    } else {
      echo "<textarea name=\"answer\" rows=\"3\" cols=\"70\">" . stripslashes($this->_answer) . "</textarea>";
    }

/////////////////////
// Answer By
/////////////////////

    $qStaff = "select staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE ptags LIKE '%talkback%' ORDER BY lname, fname";

    $querierStaff = new Querier();
    $staffArray = $querierStaff->query($qStaff);

    // put in a default user
    if ($this->_a_from == "") {
      $selected_user = $_SESSION["staff_id"];
    } else {
      $selected_user = $this->_a_from;
    }

    $staffMe = new Dropdown("a_from", $staffArray, $selected_user, "50", "--Select--");
    $staff_string = $staffMe->display();

    $answerer = "<label=\"record_label\"></label>
            $staff_string
        ";
/////////////////////
// Is Live
////////////////////

    $is_live = "<label=\"display\"></label>
<input name=\"display\" type=\"radio\" value=\"1\"";
    if ($this->_display == 1) {
      $is_live .= " checked=\"checked\"";
    }
    $is_live .= " /> " . _("Yes") . " &nbsp;&nbsp;&nbsp; <input name=\"display\" type=\"radio\" value=\"0\"";
    if ($this->_display == 0) {
      $is_live .= " checked=\"checked\"";
    }
    $is_live .= " /> " . _("No") . "
<br class=\"clear-both\" /><br />";

/////////////////////
// tbtags
////////////////////

    $tb_tags = "<input type=\"hidden\" name=\"tbtags\" value=\"" . $this->_tbtags . "\" />
			<label=\"record_label\"></label>";

    $current_tbtags = explode("|", $this->_tbtags);

    $tag_count = 0; // added because if you have a lot of ctags, it just stretches the div forever


    foreach ($all_tbtags as $key => $value) {
      if ($tag_count == 3) {
        $tb_tags .= "<br />";
        $tag_count = 0;
      }

      if (in_array($key, $current_tbtags)) {
        $tb_tags .= "<span class=\"ctag-on\">$key</span>";
      } else {
        $tb_tags .= "<span class=\"ctag-off\">$key</span>";
      }
      $tag_count++;
    }

/////////////////////
// cattags
////////////////////

    $cat_tags = "<input type=\"hidden\" class=\"cattag-data\" name=\"cattags\" value=\"" . $this->_cattags . "\" />
			<label=\"record_label\"></label>";

    $current_cattags = explode("|", $this->_cattags);

    $tag_count = 0; // added because if you have a lot of ctags, it just stretches the div forever

    foreach ($all_cattags as $key => $value) {
      if ($tag_count == 3) {
        $cat_tags .= "<br />";
        $tag_count = 0;
      }

      if (in_array($value, $current_cattags)) {
        $cat_tags .= "<span class=\"ctag-on\">$value</span>";
      } else {
        $cat_tags .= "<span class=\"ctag-off\">$value</span>";
      }
      $tag_count++;
    }

    echo "

</div>
</div>
</div>
<!-- right hand column -->";

$last_mod = _("Last modified: ") . lastModded("talkback", $this->_talkback_id);
      $title_save_box = "<div id=\"last_edited\">$last_mod</div>";

  echo"<div class=\"pure-u-1-3\">
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">$title_save_box</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">
    <input type=\"submit\" name=\"submit_record\" class=\"button pure-button pure-button-primary\" value=\"" . _("Save Now") . "\" />";

    // if a) it's not a new record, and  b) we're an admin or c) we are listed as a librarian for this guide, show delete button
    if ($this->_talkback_id != "") {
      if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "1") {
        echo "<input type=\"submit\" name=\"delete_record\" class=\"button pure-button delete_button  pure-button-warning\" value=\"" . _("Delete Forever!") . "\" />";
      } 

    }

    echo "</div></div>";

makePluslet(_("Answered By"), $answerer, "no_overflow");

makePluslet(_("Is this comment live?"), $is_live, "no_overflow");

makePluslet(_("Site Tags (relevant library sites)"), $tb_tags, "no_overflow");

makePluslet(_("Topic Tags (relevant topics)"), $cat_tags, "no_overflow");


  }

  public function deleteRecord() {

    $db = new Querier;

    // make sure they're allowed to delete
    if ($_SESSION["admin"] != "1") {
      return FALSE;
    }

    // Delete the records from talkback table
    $q = "DELETE FROM talkback WHERE talkback_id = '" . $this->_talkback_id . "'";

    $delete_result = $db->exec($q);

    $this->_debug = "<p>Del query: $q";

    if (isset($delete_result)) {
      // message
      if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
        $this->_message = _("Thy will be done.  Offending Talkback deleted.  Close window to continue.");
      } else {
        $this->_message = _("Thy will be done.  Offending Talkback deleted.");
      }

      // /////////////////////
      // Alter chchchanges table
      // table, flag, item_id, title, staff_id
      ////////////////////

      $updateChangeTable = changeMe("talkback", "delete", $this->_talkback_id, $this->_question, $_SESSION['staff_id']);

      return TRUE;
    } else {
      // message
      $this->_message = _("There was a problem with your delete.");
      return FALSE;
    }
  }

  public function insertRecord() {

    /////////////////////
    // update tb table
    /////////////////////

    $db = new Querier;

    $qInsertTB = "INSERT INTO talkback (question, q_from, date_submitted, answer, a_from, display, tbtags, cattags) VALUES (
	  " . $db->quote(scrubData($this->_question, "text")) . ",
	  " . $db->quote(scrubData($this->_q_from, "text")) . ",
      NOW(),
	  " . $db->quote(scrubData($this->_answer, "richtext")) . ",
	  " . $db->quote(scrubData($this->_a_from, "text")) . ",
      " . $db->quote(scrubData($this->_display, "integer")) . ",
      " . $db->quote(scrubData($this->_tbtags, "text")) . ",
      " . $db->quote(scrubData($this->_cattags, "text")) . "
          )";

    $rInsertTB = $db->exec($qInsertTB);

    $this->_talkback_id = $db->last_id();

    $this->_debug = "<p>1. insert: $qInsertTB</p>";
    if (!$rInsertTB) {
      echo blunDer("We have a problem with the tb query: $qInsertTB");
    }



    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("talkback", "insert", $this->_talkback_id, $this->_question, $_SESSION['staff_id']);

    // message
    //$this->_message = _("Thy Will Be Done.") . " <a href=\"guide.php?talkback_id=" . $this->_talkback_id . "\">" . _("View Your Guide") . "</a>";
  }

  public function updateRecord() {

    /////////////////////
    // update talkback table
    /////////////////////

    $db = new Querier;

    $qUpTB = "UPDATE talkback SET question = " . $db->quote(scrubData($this->_question, 'text')) . ",
	  q_from = " . $db->quote(scrubData($this->_q_from, 'text')) . ",
	  answer = " . $db->quote(scrubData($this->_answer, 'richtext')) . ",";
        if($this->_a_from == '') $qUpTB .= "a_from = NULL,";
      
        else 	$qUpTB .= "a_from = " . $db->quote(scrubData($this->_a_from, 'text')) . ",";
  	  
      $qUpTB .= "display = " . $db->quote(scrubData($this->_display, 'integer')) . ",
      
      tbtags = " . $db->quote(scrubData($this->_tbtags, 'text')) . ",
      cattags = " . $db->quote(scrubData($this->_cattags, 'text')) . "
      
      WHERE talkback_id = " . scrubData($this->_talkback_id, 'integer');

      //print $qUpTB;

    $rUpTB = $db->exec($qUpTB);


    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("talkback", "update", $this->_talkback_id, $this->_question, $_SESSION['staff_id']);

    // message
    $this->_message = _("Thy Will Be Done.  Updated.");
  }

  function getMessage() {
    return $this->_message;
  }

  function getRecordId() {
    return $this->_talkback_id;
  }

  function deBug() {
    print $this->_debug;
  }

}

?>
