<?php
   namespace SubjectsPlus\Control;
/**
 *   @file RefStats
 *   @brief manage refstats submissions (called by stats/ref_stats.php)
 *
 *   @author agdarby
 *   @date Jan 2011
 *   @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */
class RefStats {

  private $_refstats_id;
  private $_location_id;
  private $_type_id;
  private $_mode_id;
  private $_date;
  private $_note;

  private $_locations;  // array of locations
  private $_types; // array of types
  private $_modes; // array of modes


  public function __construct($refstats_id="", $flag="") {

    if ($flag == "" && $refstats_id == "") {
      $flag = "empty";
    }

    switch ($flag) {
      case "empty":

        break;
      case "post":
        // prepare record for insertion or update
        // data stored in subject table
        $this->_refstats_id = $_POST["refstats_id"];
        $this->_location_id = $_POST["location_id"];
        $this->_type_id = $_POST["type_id"];
        $this->_mode_id = $_POST["mode_id"];
        $this->_date = $_POST["date"];
        $this->_note = $_POST["note"];



        break;
      case "delete":
        // kind of redundant, but just set up to delete appropriate tables?
        // $this->_staffers needed to see if they have permission to delete this record
        $this->_refstats_id = $refstats_id;
        $this->_staffers = array(0 => array($_SESSION["staff_id"], $_SESSION["fname"] . " " . $_SESSION["lname"]));

        break;
      default:

        $this->_refstats_id = $refstats_id;

        /////////////
        // Qyery uml_refstats_mode table for list of modes (e.g., email, in person, etc.)
        /////////////

        $querier1 = new Querier();
        $q1 = "SELECT mode_id, label
                FROM uml_refstats_mode
                ORDER BY label";

        $this->_modes = $querier1->getResult($q1);

        $this->_debug .= "<p>Modes query: $q1";

        ///////////////////
        // Query location table
        // used to get our set of subjects
        // ////////////////

        $querier2 = new Querier();
        $q2 = "SELECT location_id, label
                FROM uml_refstats_location
                ORDER BY label";

        $this->_locations = $querier2->getResult($q2);

        $this->_debug .= "<p>Locations query: $q2";

        ///////////////////
        // Query type table
        // used to get our list of types of ref requests (e.g., printing)
        // ////////////////

        $querier3 = new Querier();
        $q3 = "SELECT type_id, label
                FROM uml_refstats_type
                ORDER BY label";

        $this->_types = $querier3->getResult($q3);


        $this->_debug .= "<p>" . ("Types query:") . " $q3";
    }
  }

  public function outputForm($wintype="") {

    global $wysiwyg_desc;
    global $CKPath;
    global $CKBasePath;
    global $IconPath;
    global $PublicPath;
    global $guide_types;

    $action = htmlentities($_SERVER['PHP_SELF']);

    echo "
<form action=\"" . $action . "\" method=\"post\" id=\"new_transaction\" accept-charset=\"UTF-8\" class=\"pure-form\">
<h1>Add Transactions</h1>
<select class=\"form-control\" name=\"location_id\">
";
foreach ($this->_locations as $value) {
  echo "";
  }

echo "
</select>

</form>
    ";



    $faq_title_line = _("Edit FAQ") . " <span class=\"smallgrey\">{$this->_refstats_id}</span>
        <span style=\"float: right;font-size: 12px;\"><a href=\"" . $PublicPath . "faq.php?refstats_id=$this->_refstats_id\" target=\"_blank\">" . _("see live") . "</a></span>";

    echo "

<input type=\"hidden\" name=\"refstats_id\" value=\"" . $this->_refstats_id . "\" />
<div style=\"float: left; margin-right: 20px;\">
      <div class=\"box\">
<h2 class=\"bw_head\">$faq_title_line</h2>

<span class=\"record_label\">" . _("Question") . "</span><br />
<textarea name=\"question\" rows=\"4\" cols=\"50\" class=\"required_field\">" . stripslashes($this->_question) . "</textarea>
<br /><br />
<span class=\"record_label\">" . _("Answer") . "</span><br />";

    if ($wysiwyg_desc == 1) {
	   	include ($CKPath);
	   	global $BaseURL;

    	// Create and output object
    	$oCKeditor = new CKEditor($CKBasePath);
    	$oCKeditor->timestamp = time();
    	$config['toolbar'] = 'Basic';// Default shows a much larger set of toolbar options
    	$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

    	echo $oCKeditor->editor('answer', $this->_answer, $config);
		echo "<br />";
    } else {
      echo "<textarea name=\"answer\" rows=\"4\" cols=\"70\">" . stripslashes($this->_answer) . "</textarea>";
    }

    echo "<br />
<span class=\"record_label\">" . _("Keywords (comma separated please)") . "</span><br />
<input type=\"text\" name=\"keywords\"  size=\"40\" value=\"" . $this->_keywords . "\" />
<br />
</div>
</div>
<!-- right hand column -->
<div style=\"float: left;min-width: 50px;max-width: 400px\">
	<div id=\"record_buttons\" class=\"box\">
		<input type=\"submit\" name=\"submit_record\" class=\"button save_button\" value=\"" . _("Save Now") . "\" />";

    // if a) it's not a new record, and  b) we're an admin or c) we are listed as a librarian for this guide, show delete button
    if ($this->_refstats_id != "") {
      if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "1") {
        echo "<input type=\"submit\" name=\"delete_record\" class=\"delete_button\" value=\"" . _("Delete Forever!") . "\" />";
      } else {
        echo "<input type=\"submit\" name=\"recommend_delete\" class=\"recommend_delete\" value=\"" . _("Recommend Delete") . "\" />";
      }

      $last_mod = _("Last modified: ") . lastModded("faq", $this->_refstats_id);
      echo "<div id=\"last_edited\">$last_mod</div>";
    }

    echo "</div>";

    /////////////////
    // Collections
    /////////////////
    // All collections in array
    $querier4 = new Querier();
    $q4 = "SELECT faqpage_id, name FROM faqpage ORDER BY name";
    $this->_all_collections = $querier4->getResult($q4);
    $collection_list = "";

    if ($this->_collections == FALSE) {
      // No results
      $collection_list = "";
    } else {
      // loop through results
      foreach ($this->_collections as $value) {

        $collection_list .= self::outputCollection($value);
      }
    }

    $collection_string = "";
    //get our string
    if ($this->_all_collections) {
      foreach ($this->_all_collections as $value) {
        $collection_string .= "<option value=\"$value[0]\">$value[1]</option>";
      }
    } else {
      $collection_string = "";
    }

    /////////////////
    // Subjects
    /////////////////
    $subject_list = "";

    if ($this->_subjects == FALSE) {
      // No results
      $subject_list = "";
    } else {
      // loop through results
      foreach ($this->_subjects as $value) {

        $subject_list .= self::outputSubject($value);
      }
    }


    $subject_string = getSubBoxes('', 50);

    echo "
        <div class=\"box no_overflow\">
    <h2 class=\"bw_head\">" . _("Relevant Subjects") . "</h2>

        <select name=\"subject_id[]\"><option value=\"\">-- Select --</option>
            $subject_string
        </select>
	<div id=\"subject_list\">$subject_list</div> <!-- subjects inserted here -->
    </div>
        <div class=\"box no_overflow\">
    <h2 class=\"bw_head\">" . _("Relevant Collections") . "</h2>

        <select name=\"collection_id[]\"><option value=\"\">-- Select --</option>
            $collection_string
        </select>
        <div id=\"collection_list\">$collection_list</div> <!-- subjects inserted here -->
    </div>
    </form>";
  }

  public function outputSubject($value) {
    global $IconPath;

    $subject_name = Truncate($value["subject"], 25, '');

    $oursubjects = "
        <div class=\"selected_item_wrapper\">
            <div class=\"selected_item\">
                <input name=\"subject[]\" value=\"$value[subject_id]\" type=\"hidden\" />
                $subject_name
            </div>
            <div class=\"selected_item_options\">
                <img src=\"$IconPath/delete.png\" class=\"delete_sub\" alt=\"" . ("delete") . "\" title=\"" . ("remove subject") . "\" border=\"0\">
            </div>
        </div>";

    return $oursubjects;
  }

  public function outputCollection($value) {
    global $IconPath;

    $collection_name = Truncate($value["name"], 25, '');

    $ourcolls = "
        <div class=\"selected_item_wrapper\">
            <div class=\"selected_item\">
                <input name=\"collection[]\" value=\"$value[faqpage_id]\" type=\"hidden\" />
                $collection_name
            </div>
            <div class=\"selected_item_options\">
                <img src=\"$IconPath/delete.png\" class=\"delete_sub\" alt=\"" . ("delete") . "\" title=\"" . ("remove subject") . "\" border=\"0\">
            </div>
        </div>";

    return $ourcolls;
  }

  public function insertRecord() {

    /////////////////////
    // update tb table
    /////////////////////

    $qInsert = "INSERT INTO faq (question, answer, keywords) VALUES (
	  '" . mysql_real_escape_string(scrubData($this->_question, "text")) . "',
	  '" . mysql_real_escape_string(scrubData($this->_answer, "richtext")) . "',
          '" . mysql_real_escape_string(scrubData($this->_keywords, "text")) . "'
          )";

    $rInsert = mysql_query($qInsert);

    $this->_refstats_id = mysql_insert_id();

    $this->_debug = "<p>1. insert: $qInsert</p>";
    if (!$rInsert) {
      echo blunDer("We have a problem with the tb query: $qInsert");
    }


    // message
    $this->_message = _("Thy Will Be Done.");
  }

  
  function modifySubjects() {
    for ($i = 0; $i < $this->_subject_count; $i++) {
      $qUpSub = "INSERT INTO faq_subject (refstats_id, subject_id) VALUES (
                '" . scrubData($this->_refstats_id, "integer") . "',
                '" . scrubData($this->_subject[$i], "integer") . "')";

      $rUpSub = mysql_query($qUpSub);

      $this->_debug .= "<p>3. (update faq_subject loop) : $qUpSub</p>";
      if (!$rUpSub) {
        echo blunDer("We have a problem with the faq_subject query: $qUpSub");
      }
    }
  }



  function getMessage() {
    return $this->_message;
  }

  function getRecordId() {
    return $this->_refstats_id;
  }

  function deBug() {
    print $this->_debug;
  }

}

?>

