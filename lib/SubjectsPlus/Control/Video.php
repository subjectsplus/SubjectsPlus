<?php
namespace SubjectsPlus\Control;
/**
 *   @file sp_Video
 *   @brief manage video metadata submissions (called by video/index.php)
 *
 *   @author agdarby
 *   @date Feb 2012
 *   @todo delete image files from server upon delete
 */
class Video {

  private $_video_id;
  private $_title;
  private $_description;
  private $_source;
  private $_foreign_id;
  private $_duration;
  private $_date;
  private $_display;
  private $_vtags;

  public function __construct($video_id="", $flag="") {

    if ($flag == "" && $video_id == "") {
      $flag = "empty";
    }

    switch ($flag) {
      case "empty":
        $this->_a_from = $_SESSION["staff_id"];
        $this->_message = _("Have you tried ingesting the data for this video?  Much faster/easier.");
        break;
      case "post":
        // prepare record for insertion or update
        // data stored in subject table
        $this->_video_id = $_POST["video_id"];
        $this->_title = $_POST["title"];
        $this->_description = $_POST["description"];
        $this->_source = $_POST["source"];
        $this->_foreign_id = $_POST["foreign_id"];
        $this->_duration = $_POST["duration"];
        $this->_date = $_POST["date"];
        $this->_display = $_POST["display"];
        $this->_vtags = $_POST["vtags"];


        break;
      case "delete":
        // kind of redundant, but just set up to delete appropriate tables?
        // $this->_staffers needed to see if they have permission to delete this record
        $this->_video_id = $video_id;
        $this->_staffers = array(0 => array($_SESSION["staff_id"], $_SESSION["fname"] . " " . $_SESSION["lname"]));

        break;
      default:

        $this->_video_id = $video_id;
        $this->_message = "";

        /////////////
        // Get tb table info
        /////////////

        $querier = new Querier();
        $q1 = "SELECT video_id, title, description, source, foreign_id, duration, date as vid_date, display, vtags
                    FROM video WHERE video_id = " . $this->_video_id;
        $vidArray = $querier->query($q1);

        $this->_debug .= "<p>TB query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($vidArray == FALSE) {
          $this->_message = _("There is no active record with that ID.  Weird.");
        } else {
          $this->_title = $vidArray[0]["title"];
          $this->_description = $vidArray[0]["description"];
          $this->_source = $vidArray[0]["source"];
          $this->_foreign_id = $vidArray[0]["foreign_id"];
          $this->_duration = $vidArray[0]["duration"];
          $this->_date = $vidArray[0]["vid_date"];
          $this->_display = $vidArray[0]["display"];
          $this->_vtags = $vidArray[0]["vtags"];
        }

        break;
    }
  }

  public function outputForm($wintype="") {

    global $wysiwyg_desc;
    global $CKPath;
    global $CKBasePath;
    global $IconPath;
    global $AssetPath;
    global $guide_types;
    global $video_storage_types;
    global $all_vtags;

    //print "<pre>";print_r($this->_staffers); print "</pre>";

    $action = htmlentities($_SERVER['PHP_SELF']) . "?video_id=" . $this->_video_id;

    if ($wintype != "") {
      $action .= "&wintype=pop";
    }


    $vid_title_line = _("Edit Video Info");

    echo "
<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" class=\"pure-form pure-form-stacked\" accept-charset=\"UTF-8\">
<input type=\"hidden\" name=\"video_id\" value=\"" . $this->_video_id . "\" />
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">$vid_title_line</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">

<label for=\"title\">" . _("Title") . "</label>
<textarea name=\"title\" rows=\"2\" cols=\"50\">" . stripslashes($this->_title) . "</textarea>

<label for=\"description\">" . _("Description") . "</label>";

    if ($wysiwyg_desc == 1) {
		include($CKPath);
    	global $BaseURL;

    	$oCKeditor = new CKEditor($CKBasePath);
    	$oCKeditor->timestamp = time();
    	$config['toolbar'] = 'Basic';// Default shows a much larger set of toolbar options
    	$config['height'] = '100';
    	$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

    	echo $oCKeditor->editor('description', $this->_description, $config);
		echo "<br />";
    } else {
      echo "<textarea name=\"description\" rows=\"4\" cols=\"70\">" . stripslashes($this->_description) . "</textarea>";
    }

    // Generate our dropdown
        $guideMe = new Dropdown("source", $video_storage_types, $this->_source, "50");
        $guide_string = $guideMe->display();

    echo "
    <label for=\"source\">" . _("Video file storage location") . "</label>
$guide_string

<label for=\"foreign_id\">" . _("Foreign ID") . "</label>
<input name=\"foreign_id\" value=\"" . stripslashes($this->_foreign_id) . "\" size=\"15\" />
  <span class=\"smaller\">* " . ("Enter the embed code id for youtube or vimeo") . "</span><br />
<label for=\"duration\">" . _("Duration in seconds") . "</label>
<input name=\"duration\" value=\"" . stripslashes($this->_duration) . "\" size=\"5\" />
<br />";

/////////////////////
// Tags
////////////////////
    echo "<input type=\"hidden\" name=\"vtags\" value=\"" . $this->_vtags . "\" />
			<span class=\"record_label\">vtags: </span> ";

    $current_vtags = explode("|", $this->_vtags);
    $tag_count = 0; // added because if you have a lot of ctags, it just stretches the div forever
    $vid_tags = "";


    foreach ($all_vtags as $value) {
      if ($tag_count == 5) {
        $vid_tags .= "<br />";
        $tag_count = 0;
      }

      if (in_array($value, $current_vtags)) {
        $vid_tags .= "<span class=\"ctag-on\">$value</span>";
      } else {
        $vid_tags .= "<span class=\"ctag-off\">$value</span>";
      }
      $tag_count++;
    }

    print $vid_tags;

/////////////////////
// Is Live
////////////////////

    $is_live = "<label for=\"display\">" . _("Live?") . "</label>
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
// Thumbnail
////////////////////


    $this->_vid_loc = $AssetPath . "images/video_thumbs/" . $this->_video_id . "_medium.jpg";
    $thumbnail = "<img src=\"" . $this->_vid_loc . "\" alt=\"" . _("Thumbnail") . "\" />";

    if ($this->_video_id != "") {
      $thumbnail .= "<p><a href=\"../includes/set_image.php?video_id=$this->_video_id\" id=\"load_photo\">" . _("Click to update thumbnail") . "</a></p>";
    } else {
      $thumbnail .= "<p>" . _("You can change the thumbnail after saving.") . "</p>";
    }
    echo "

</div>
</div>
</div>
<!-- right hand column -->";

$last_mod = _("Last modified: ") . lastModded("video", $this->_video_id);
$title_save_box = "<div id=\"last_edited\">$last_mod</div>";
print "<div class=\"pure-u-1-3\">
    <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\">$title_save_box</div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">
    <input type=\"submit\" name=\"submit_record\" class=\"button pure-button pure-button-primary\" value=\"" . _("Save Now") . "\" />";

    // if a) it's not a new record, and  b) we're an admin or c) we are listed as a librarian for this guide, show delete button
    if ($this->_video_id != "") {
      if ($_SESSION["admin"] == "1" || $_SESSION["eresource_mgr"] == "1") {
        echo "<input type=\"submit\" name=\"delete_record\" class=\"button pure-button delete_button pure-button-warning\" value=\"" . _("Delete Forever!") . "\">";
      }
    }

    echo "</div></div>
        <div class=\"pluslet\">
      <div class=\"titlebar\">
        <div class=\"titlebar_text\"></div>
        <div class=\"titlebar_options\"></div>
      </div>
      <div class=\"pluslet_body\">
            <label for=\"date\">" . _("Create Date") . "</label>
            <input type=\"text\" name=\"date\" value=\"" . $this->_date . "\" />
            <span class=\"smaller\">" . ("YYYY-MM-DD") . "</span>
            <br /><br />
            $is_live
            </div></div>
            </form>";

    makePluslet(_("Thumbnail"), $thumbnail, "no_overflow");
  }

  public function deleteRecord() {

    // make sure they're allowed to delete
    if ($_SESSION["admin"] != "1") {
      return FALSE;
    }

    $db = new Querier;

    // Delete the records from video table
    $q = "DELETE FROM video WHERE video_id = '" . $this->_video_id . "'";

    $delete_result = $db->exec($q);

    $this->_debug = "<p>Del query: $q";

    if ($delete_result) {
      // Delete image files from server?

      // message
      if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
        $this->_message = _("Thy will be done.  Offending video metadata deleted.  Close window to continue.");
      } else {
        $this->_message = _("Thy will be done.  Offending video metadata deleted.");
      }

      // /////////////////////
      // Alter chchchanges table
      // table, flag, item_id, title, staff_id
      ////////////////////

      $updateChangeTable = changeMe("video", "delete", $this->_video_id, $this->_title, $_SESSION['staff_id']);

      return TRUE;
    } else {
      // message
      $this->_message = _("There was a problem with your delete.");
      return FALSE;
    }
  }

  public function insertRecord() {

    /////////////////////
    // add to vid table
    /////////////////////
      $db = new Querier;
      
    $qInsertVid = "INSERT INTO video (title, description, source, foreign_id, duration, date, display, vtags) VALUES (" .
	  $db->quote(scrubData($this->_title, 'text')) . ","  .
      $db->quote(scrubData($this->_description, 'richtext')) . "," .
	  $db->quote(scrubData($this->_source, 'text')) . "," .
      $db->quote(scrubData($this->_foreign_id, 'text')) . "," .
      $db->quote(scrubData($this->_duration, 'text')) . "," .
      $db->quote(scrubData($this->_date, 'text')) . "," .
      $db->quote(scrubData($this->_display, 'integer')) . "," .
      $db->quote(scrubData($this->_vtags, 'text')) . ")";

            
    $rInsertVid = $db->exec($qInsertVid);

    $this->_video_id = $db->last_id();

    $this->_debug = "<p>1. insert: $qInsertVid</p>";
    if (!$rInsertVid) {
      echo blunDer("We have a problem with the tb query: $qInsertVid");
    }



    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("video", "insert", $this->_video_id, $this->_title, $_SESSION['staff_id']);

    // message
    $this->_message = _("Thy Will Be Done. Added.");
  }

  public function updateRecord() {

      $db = new Querier;
    /////////////////////
    // update video table
    /////////////////////
   //   print "UPDATE RECORD!!!";
      
    $qUpVid = "UPDATE video SET title = " . $db->quote($this->_title) .
	  
      ", description = " . $db->quote(scrubData($this->_description, 'text')) . "," .
      "source = " . $db->quote($this->_source) . "," .
      "foreign_id = " . $db->quote($this->_foreign_id) . "," .
      "duration = " . $db->quote($this->_duration) . "," .
      "date =  " . $db->quote($this->_date) . "," .
      "display = " . $db->quote($this->_display) . "," .
      "vtags =  ". $db->quote($this->_vtags) .
      
      "WHERE video_id = " . $db->quote($this->_video_id);

      //print $qUpVid;
      
    $rUpVid = $db->exec($qUpVid);




    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

    $updateChangeTable = changeMe("video", "update", $this->_video_id, $this->_title, $_SESSION['staff_id']);

    // message
    $this->_message = _("Thy Will Be Done.  Updated.");
  }

  function getMessage() {
    return $this->_message;
  }

  function getRecordId() {
    return $this->_video_id;
  }

  function deBug() {
    print $this->_debug;
  }

}

?>