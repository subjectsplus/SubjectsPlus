<?php
   namespace SubjectsPlus\Control;
/**
 *   @file FAQ
 *   @brief manage faq submissions (called by faq/index.php)
 *
 *   @author agdarby
 *   @date Jan 2011
 *   @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */
class FAQ {

  private $_faq_id;
  private $_question;
  private $_answer;
  private $_keywords;
  private $_collection;
  private $_collection_count;
  private $_subject;
  private $_subject_count;
  private $_staffers;
  private $_message;
  private $_collections;
  private $_subjects;
  private $_debug;


  public function __construct($faq_id="", $flag="") {

    if ($flag == "" && $faq_id == "") {
      $flag = "empty";
    }

    switch ($flag) {
      case "empty":

        break;
      case "post":
        // prepare record for insertion or update
        // data stored in subject table
        $this->_faq_id = $_POST["faq_id"];
        $this->_question = $_POST["question"];
        $this->_answer = $_POST["answer"];
        $this->_keywords = $_POST["keywords"];

        if (isset($_POST["collection"])) {
          $this->_collection = $_POST["collection"]; // array
        } else {
          $this->_collection = array(); // array
        }

        if (isset($_POST["subject"])) {
          $this->_subject = $_POST["subject"]; // array
        } else {
          $this->_subject = array(); // array
        }


        $this->_collection_count = count($this->_collection); // # of items in above arrays
        $this->_subject_count = count($this->_subject); // # of items in above arrays


        break;
      case "delete":
        // kind of redundant, but just set up to delete appropriate tables?
        // $this->_staffers needed to see if they have permission to delete this record
        $this->_faq_id = $faq_id;
        $this->_staffers = array(0 => array($_SESSION["staff_id"], $_SESSION["fname"] . " " . $_SESSION["lname"]));

        break;
      default:

        $this->_faq_id = $faq_id;

        /////////////
        // Get tb table info
        /////////////

        $querier = new Querier();
        $q1 = "SELECT faq_id, question, answer, keywords
                    FROM faq WHERE faq_id = " . $this->_faq_id;
        $faqArray = $querier->query($q1);

        $this->_debug .= "<p>FAQ query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($faqArray == FALSE) {
          $this->_message = _("There is no active record with that ID.  Weird.  Why not make one?");
        } else {
          $this->_question = $faqArray[0]["question"];
          $this->_answer = $faqArray[0]["answer"];
          $this->_keywords = $faqArray[0]["keywords"];
        }

        ///////////////////
        // Query Subject table
        // used to get our set of subjects
        // ////////////////

        $querier2 = new Querier();
        $q2 = "SELECT subject.subject_id, subject
                FROM faq_subject, subject
                WHERE faq_subject.subject_id = subject.subject_id
                AND faq_subject.faq_id =" . $this->_faq_id . "
		ORDER BY subject";

        $this->_subjects = $querier2->query($q2);

        $this->_debug .= "<p>Linked Subjects query: $q2";

        ///////////////////
        // Query Associated Collections
        // used to get our set of collections
        // ////////////////

        $querier3 = new Querier();
        $q3 = "SELECT f.faqpage_id, name
                FROM faqpage f, faq_faqpage ff
                WHERE ff.faqpage_id = f.faqpage_id
                AND ff.faq_id = " . $this->_faq_id . "
		ORDER BY name";

        $this->_collections = $querier3->query($q3);


        $this->_debug .= "<p>" . ("Linked collections query:") . " $q3";
    }
  }

  public function outputForm($wintype="") {

    global $wysiwyg_desc;
    global $CKPath;
    global $CKBasePath;
    global $IconPath;
    global $PublicPath;
    global $guide_types;

    $action = htmlentities($_SERVER['PHP_SELF']) . "?faq_id=" . $this->_faq_id;

    if ($wintype != "") {
      $action .= "&wintype=pop";
    }


    $faq_title_line = _("Edit FAQ") . " <span class=\"smallgrey\">{$this->_faq_id}</span>
        <div style=\"float: right; margin-left: 2em;font-size: 12px;\"><a href=\"" . $PublicPath . "faq.php?faq_id=$this->_faq_id\" target=\"_blank\">" . _("see live") . "</a></div>";

    echo "
<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" class=\"pure-form pure-form-stacked\" accept-charset=\"UTF-8\">
<input type=\"hidden\" name=\"faq_id\" value=\"" . $this->_faq_id . "\" />
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">$faq_title_line</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">

<label for=\"question\">" . _("Question") . "</label>
<textarea name=\"question\" rows=\"4\" cols=\"50\" class=\"required_field\">" . stripslashes($this->_question) . "</textarea>

<label for=\"answer\">" . _("Answer") . "</label>";

    if ($wysiwyg_desc == 1) {
	   	include ($CKPath);
	   	global $BaseURL;

    	// Create and output object
    	$oCKeditor = new CKEditor($CKBasePath);
    	$oCKeditor->timestamp = time();
    	$config['toolbar'] = 'SubsPlus_Narrow';// Default shows a much larger set of toolbar options
    	$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

    	echo $oCKeditor->editor('answer', $this->_answer, $config);

    } else {
      echo "<textarea name=\"answer\" rows=\"4\" cols=\"70\">" . stripslashes($this->_answer) . "</textarea>";
    }

    echo "
<label for=\"keywords\">" . _("Keywords (comma separated please)") . "</label>
<input type=\"text\" name=\"keywords\"  size=\"40\" value=\"" . $this->_keywords . "\" />
</div>
</div>
</div>
<!-- right hand column -->    ";

$last_mod = _("Last modified: ") . lastModded("faq", $this->_faq_id);
      $title_save_box = "<div id=\"last_edited\">$last_mod</div>";

  echo"<div class=\"pure-u-1-3\">
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">$title_save_box</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">
		<input type=\"submit\" name=\"submit_record\" class=\"button pure-button pure-button-primary save-button\" value=\"" . _("Save Now") . "\" />";

    // if a) it's not a new record, and  b) we're an admin or c) we are listed as a librarian for this guide, show delete button
    if ($this->_faq_id != "") {
      if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "1") {
        echo "<input type=\"submit\" name=\"delete_record\" class=\"button pure-button pure-button-warning delete-button\" value=\"" . _("Delete Forever!") . "\" />";
      } else {
        echo "<input type=\"submit\" name=\"recommend_delete\" class=\"button pure-button pure-button-warning\" value=\"" . _("Recommend Delete") . "\" />";
      }



    }

    echo "</div></div>";

    /////////////////
    // Collections
    /////////////////
    // All collections in array
    $querier4 = new Querier();
    $q4 = "SELECT faqpage_id, name FROM faqpage ORDER BY name";
    $this->_all_collections = $querier4->query($q4);
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
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">" . _("Relevant Subjects") . "</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">

        <select name=\"subject_id[]\"><option value=\"\">-- " . _("Select") . " --</option>
            $subject_string
        </select>
	     <div id=\"subject_list\">$subject_list</div> <!-- subjects inserted here -->
       <br />
        </div></div>

    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">" . _("Relevant Collections") . "</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">

        <select name=\"collection_id[]\"><option value=\"\">-- " . _("Select") . " --</option>
            $collection_string
        </select>
        <div id=\"collection_list\">$collection_list</div> <!-- subjects inserted here -->
        <br />
    </div></div>
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
                <i class=\"fa fa-times delete_sub\" alt=\"" . ("delete") . "\" title=\"" . ("remove subject") . "\"></i>
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
                <i class=\"fa fa-times delete_sub\" alt=\"" . ("delete") . "\" title=\"" . ("remove subject") . "\"></i>
            </div>
        </div>";

    return $ourcolls;
  }

  public function deleteRecord() {

    // make sure they're allowed to delete
    if ($_SESSION["admin"] != "1") {
      return FALSE;
    }

    $db = new Querier;

    // Delete the records from faq and linked tables
    $q = "DELETE FROM faq WHERE faq_id = '" . $this->_faq_id . "'";
    
    $delete_result = $db->exec($q);

    $this->_debug = "<p>Del query: $q";

    if (count($delete_result) != 0) {

    } else {
      // message
      $this->_message = _("There was a problem with your delete (stage 1 of 2).");
      return FALSE;
    }

      if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
        $this->_message = _("Thy will be done.  Offending FAQ deleted.  Close window to continue.");
      } else {
        $this->_message = _("Thy will be done.  Offending FAQ deleted.");
      }

      // /////////////////////
      // Alter chchchanges table
      // table, flag, item_id, title, staff_id
      ////////////////////

      $updateChangeTable = changeMe("faq", "delete", $this->_faq_id, $this->_question, $_SESSION['staff_id']);

      return TRUE;
    //} else {
      // message
      //$this->_message = _("There was a problem with your delete, stage 2 of 2.");
      //return FALSE;
    //}
  }

  public function insertRecord() {

    /////////////////////
    // update tb table
    /////////////////////
    $db = new Querier;
    $qInsert = "INSERT INTO faq (question, answer, keywords) VALUES (
	  " . $db->quote(scrubData($this->_question, 'text')) . ",
	  " . $db->quote(scrubData($this->_answer, 'richtext')) . ",
          " . $db->quote(scrubData($this->_keywords, 'text')) . "
          )";

    $rInsert = $db->exec($qInsert);

    $this->_faq_id = $db->last_id();

    /////////////////////
    // insert into rank
    ////////////////////

    self::modifySubjects();

    /////////////////////
    // insert/update locations
    ////////////////////

    self::modifyCollections();

    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("faq", "insert", $this->_faq_id, $this->_question, $_SESSION['staff_id']);

    // message
    $this->_message = _("Thy Will Be Done.");
  }

  public function updateRecord() {

    /////////////////////
    // update faq table
    /////////////////////
  	$db = new Querier;
    $qUpFAQ = "UPDATE faq SET question = " . $db->quote(scrubData($this->_question, "text")) . ",
	  answer = " . $db->quote(scrubData($this->_answer, 'richtext')) . ",
	  keywords = " . $db->quote(scrubData($this->_keywords, 'text')) . "
          WHERE faq_id = " . scrubData($this->_faq_id, 'integer');

    $rUpFAQ = $db->query($qUpFAQ);

    $this->_debug = "<p>1. update faq: $qUpFAQ</p>";


    /////////////////////
    // clear faq_subject
    /////////////////////

    $qClearSubs = "DELETE FROM faq_subject WHERE faq_id = " . $this->_faq_id;

    $rClearSubs = $db->query($qClearSubs);

    $this->_debug .= "<p>2. clear rank: $qClearSubs</p>";

    if ($rClearSubs === FALSE) {
      echo blunDer("We have a problem with the clear faq-subs query: $qClearSubs");
    }

    /////////////////////
    // insert into subject
    ////////////////////

    self::modifySubjects();

    // wipe entry from intervening table
    $qClearColls = "DELETE FROM faq_faqpage WHERE faq_id = " . scrubData($this->_faq_id, "integer");
    $rClearColls = $db->query($qClearColls);

    $this->_debug .= "<p>4. wipe faq_faqpage: $qClearColls</p>";
    if ($rClearColls === FALSE) {
      echo blunDer("We have a problem with the clear locations query: $qClearColls");
    }

    /////////////////////
    // insert/update collections
    ////////////////////

    self::modifyCollections();

    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("faq", "update", $this->_faq_id, $this->_question, $_SESSION['staff_id']);

    // message
    $this->_message = _("Thy Will Be Done.  Updated.");
  }

  function modifySubjects() {
    $db = new Querier;

    for ($i = 0; $i < $this->_subject_count; $i++) {
      $qUpSub = "INSERT INTO faq_subject (faq_id, subject_id) VALUES (
                " . scrubData($this->_faq_id, 'integer') . ",
                " . scrubData($this->_subject[$i], 'integer') . ")";

      $rUpSub = $db->query($qUpSub);

      $this->_debug .= "<p>3. (update faq_subject loop) : $qUpSub</p>";
      if ($rUpSub === FALSE) {
        echo blunDer("We have a problem with the faq_subject query: $qUpSub");
      }
    }
  }

  function modifyCollections() {
    $db = new Querier;
    for ($i = 0; $i < $this->_collection_count; $i++) {
      $qUpColl = "INSERT INTO faq_faqpage (faq_id, faqpage_id) VALUES (
                " . scrubData($this->_faq_id, "integer") . ",
                " . scrubData($this->_collection[$i], "integer") . ")";

      $rUpColl = $db->query($qUpColl);

      $this->_debug .= "<p>3. (update faq_faqpage loop) : $qUpColl</p>";
      if ($rUpColl === FALSE) {
        echo blunDer("We have a problem with the faq_faqpage query: $qUpColl");
      }
    }
  }

  function getMessage() {
    return $this->_message;
  }

  function getRecordId() {
    return $this->_faq_id;
  }

  function deBug() {
    print $this->_debug;
  }

}

?>
