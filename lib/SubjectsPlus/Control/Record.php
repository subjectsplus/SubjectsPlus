<?php
   namespace SubjectsPlus\Control;
/**
 *   @file sp_Record
 *   @brief manage records
 *
 *   @author agdarby, rgilmour
 *   @date Nov 2010
 *   @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */
class Record {

	private $_record_id;
	private $_prefix;
	private $_title;
	private $_alternate_title;
	private $_description;
	private $_location_id;
	private $_location;
	private $_call_number;
	private $_format;
	private $_access_restrictions;
	private $_display_note;
	private $_eres_display;
	private $_ctags;
	private $_subject;
	private $_rank;
	private $_source;
	private $_description_override;
	private $_subject_count;
	private $_subjects;
	private $_az_display;
	private $_def_source;
	private $_note;
	private $_message;
	private $_locations;
	private $_helpguide;
	public $_debug;

	public function __construct($record_id="", $flag="") {

		if ($flag == "" && $record_id == "") {
			$flag = "empty";
		}

		switch ($flag) {
			case "empty":
        // don't set anything; this will create an empty record
			break;
			case "post":
        // prepare record for insertion or update
        // data stored in title table
			$this->_title_id = $_POST["title_id"];
			$this->_prefix = $_POST["prefix"];
			$this->_title = $_POST["title"];
			$this->_alternate_title = $_POST["alternate_title"];
			$this->_description = $_POST["description"];

        // data stored in location table
        $this->_location_id = $_POST["location_id"]; // array
        $this->_location = $_POST["location"]; // array
        $this->_call_number = $_POST["call_number"]; // array, will be stored in location field
        $this->_format = $_POST["format"]; // array INT
        $this->_access_restrictions = $_POST["access_restrictions"]; // array
        $this->_display_note = $_POST["display_note"]; // array
        $this->_eres_display = $_POST["eres_display"]; // array
        $this->_ctags = $_POST["ctags"]; // array
        $this->_helpguide = $_POST["helpguide"]; // array
        // data stored in rank table
        $this->_subject = $_POST["subject"]; // array
        $this->_rank = $_POST["rank"]; // array
        $this->_source = $_POST["source"]; // array
        $this->_description_override = $_POST["description_override"]; // array

        $this->_subject_count = count($this->_subject); // # of items in above arrays

        break;
        case "delete":
        // kind of redundant, but just set up to delete appropriate tables?
        // title_id only needed?
        $this->_record_id = $record_id;

        break;
        default:
        $this->_record_id = $record_id;

        /////////////
        // Get title table info (title, description)
        /////////////
        $querier = new Querier();
        $q1 = "select title_id, pre, title, alternate_title, description from title where title_id = " . $this->_record_id;
        $titleArray = $querier->getResult($q1);

        $this->_debug .= "<p>Title query: $q1";
        // Test if these exist, otherwise go to plan B
        if ($titleArray == FALSE) {
        	$this->_message = "There is no active record with that ID.  Why not create a new one?";
        } else {
        	$this->_prefix = $titleArray[0]["pre"];
        	$this->_title = $titleArray[0]["title"];
        	$this->_alternate_title = $titleArray[0]["alternate_title"];
        	$this->_description = $titleArray[0]["description"];
        }

        ///////////////////
        // Query Rank table
        // used to get our set of subjects
        // ////////////////

        $querier2 = new Querier();
        $q2 = "select rank, rank.subject_id, title_id, so.source_id, rank_id, description_override, subject, source
        FROM rank, subject, source so
        WHERE rank.subject_id = subject.subject_id
        AND rank.source_id = so.source_id
        AND title_id = " . $this->_record_id . "
        ORDER BY subject";

        $this->_subjects = $querier2->getResult($q2);

        $this->_debug .= "<p>Rank query: $q2";

        /////////////
        // Locations
        /////////////

        $querier3 = new Querier();
        $q3 = "SELECT l.location_id, format, call_number, location, access_restrictions, eres_display, display_note, ctags, helpguide FROM location_title lt, location l WHERE lt.location_id = l.location_id AND lt.title_id = " . $this->_record_id;
        //print $q3;
        $this->_locations = $querier3->getResult($q3);

        $this->_debug .= "<p>Location query: $q3";

        /////////////
        // Default Source
        /////////////

        $querier4 = new Querier();
        $q4 = "SELECT rank.source_id, source.source, count(rank.source_id) as counter
        FROM `rank`, source
        WHERE rank.source_id = source.source_id
        AND title_id = " . $this->_record_id . "
        GROUP BY rank.source_id
        ORDER BY counter DESC, source ASC
        LIMIT 0, 1";
        $this->_def_source = $querier4->getResult($q4);

        $this->_debug .= "<p>Source query: $q4";

        break;
     }
  }

  public function outputForm($wintype="") {

  	global $wysiwyg_desc;
  	global $CKPath;
  	global $CKBasePath;
  	global $IconPath;

  	$action = htmlentities($_SERVER['PHP_SELF']) . "?record_id=" . $this->_record_id;

  	if ($wintype != "") {
  		$action .= "&wintype=pop";
  	}

  	echo "
  	<form action=\"" . $action . "\" method=\"post\" id=\"new_record\" accept-charset=\"UTF-8\">
  	<input type=\"hidden\" name=\"title_id\" value=\"" . $this->_record_id . "\" />
  	<div style=\"float: left; margin-right: 20px; \">
  	<div class=\"box\">
  	<span class=\"record_label\">" . _("Record Title") . "</span><br />
  	<input type=\"text\" name=\"prefix\" id=\"prefix\" size=\"1\" value=\"" . $this->_prefix . "\" /><input type=\"text\" name=\"title\" id=\"record_title\" size=\"60\" class=\"required_field\" value=\"" . $this->_title . "\" />
  	<br /><br />
  	<span class=\"record_label\">" . _("Alternate Title") . "</span><br />
  	<input type=\"text\" name=\"alternate_title\" id=\"alternate_record_title\" size=\"60\" value=\"" . $this->_alternate_title . "\" />
  	<br /><br />
  	<span class=\"record_label\">" . _("Description") . "</span><br />

  	";

  	if ($wysiwyg_desc == 1) {
  		include($CKPath);
  		//global $BaseURL;

		// Create and output object
  		//$oCKeditor = new CKEditor($CKBasePath);
  		//$oCKeditor->timestamp = time();
		//$config['toolbar'] = 'Basic';// Default shows a much larger set of toolbar options
		//$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

		//echo $oCKeditor->editor('description', $this->_description, $config);
        echo "<textarea name=\"description\"  rows=\"4\" cols=\"70\">" . stripslashes($this->_description) . "</textarea>";
        echo "<script src='../../CKEditor/ckeditor.js'></script>";
		echo "<script type='text/javascript'> window.onload = function(){ CKEDITOR.replace( 'description' ); };  </script>";
        echo "<br />";
	} else {
		echo "<textarea name=\"description\" id=\"description\" rows=\"4\" cols=\"70\">" . stripslashes($this->_description) . "</textarea>";
	}

	echo "</div>
	<!--<h2 class=\"bw_head\">" . _("Location") . "</h2>-->
	";


    // Loop through locations
	self::buildLocation();

	echo "
	<div class=\"add_location\"><img src=\"$IconPath/list-add.png\" alt=\"add new location\"  border=\"0\" /> Add another location</div>
	</div>
	<!-- right hand column -->
	<div style=\"float: left;min-width: 270px;\">
	<div id=\"record_buttons\" class=\"box\">
	<input type=\"submit\" name=\"submit_record\" class=\"save_button\" value=\"" . _("Save Record Now") . "\" />";
    // if it's not a new record, and we're authorized, show delete button
	if ($this->_record_id != "") {
		if (isset($_SESSION["eresource_mgr"]) && $_SESSION["eresource_mgr"] == "1") {
			echo "<input type=\"submit\" name=\"delete_record\" class=\"delete_button\" value=\"" . _("Delete Forever!") . "\" />";
		} else {
			echo "<input type=\"submit\" name=\"recommend_delete\" class=\"recommend_delete\" value=\"" . _("Recommend Delete") . "\" />";
		}
	}
    // get edit history
	$last_mod = _("Last modified: ") . lastModded("record", $this->_record_id);
	echo "<div id=\"last_edited\">$last_mod</div>
	</div>";

    /////////////////
    // Default Source
    /////////////////

	$querierSource = new Querier();
	$qSource = "select source_id, source from source order by source";
	$defsourceArray = $querierSource->getResult($qSource);

	$sourceMe = new Dropdown("default_source_id", $defsourceArray, $this->_def_source[0][0]);
	$source_string = $sourceMe->display();

	echo " <div class=\"box\">
	<h2 class=\"bw_head\"><img src=\"$IconPath/source_override.png\" alt=\"delete\" border=\"0\"> " . _("Default Source Type") . "</h2>
	
	$source_string
	</div>";

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

	if (isset($_SESSION["eresource_mgr"]) && $_SESSION["eresource_mgr"] == "1") {
		$subject_string = getSubBoxes('', 50, 1);
	} else {
		$subject_string = getSubBoxes('', 50);
	}

	echo "
      <div class=\"box no_overflow\">
	<h2 class=\"bw_head\">" . _("Subjects") . "</h2>
	
	<select name=\"subject_id[]\"><option value=\"\">" . _("-- Select --") . "</option>
	$subject_string
	</select>
	<div id=\"subject_list\">$subject_list</div> <!-- subjects inserted here -->
	</div>


	</div>
	</form>";
}

public function buildLocation() {

	$this->_boxcount = 1;

    ///////////////
    // Location > Format
    ///////////////

	$querierLoc = new Querier();
	$qLoc = "select format_id, format from format order by format_id";
	$formatArray = $querierLoc->getResult($qLoc);

    ////////////////
    // Location Restrictions
    ////////////////

	$querierRes = new Querier();
	$qRes = "select restrictions_id, restrictions from restrictions order by restrictions_id";
	$restrictionsArray = $querierRes->getResult($qRes);

    // Test if these exist, otherwise go to plan B
	if ($this->_locations == FALSE) {
      // no location
      // create format box for later
		$formatMe = new Dropdown("format[]", $formatArray);
		$this->_formats = $formatMe->display();

      // create restrictions box for later
		$restrictMe = new Dropdown("access_restrictions[]", $restrictionsArray);
		$this->_restrictions = $restrictMe->display();

		$new_loc = self::outputLocation();
	} else {

		foreach ($this->_locations as $value) {
        //print "<pre>";print_r($value);print "</pre>";
// SELECT l.location_id, format, call_number, location, access_restrictions, eres_display, display_note, ctags
        // FROM location_title lt, location l
        // WHERE lt.location_id = l.location_id AND lt.title_id = " . $this->_record_id;
        /////////////////
        // Hidden location id
        ///////////////////

			$this->_location_id = $value[0];

        /////////////////
        // Location > Url (or call number)
        /////////////////

			$this->_location = $value["location"];

        // create format box
			$formatMe = new Dropdown("format[]", $formatArray, $value["format"]);
			$this->_formats = $formatMe->display();

			$this->_format = $value["format"];

        // create restrictions box
			$restrictMe = new Dropdown("access_restrictions[]", $restrictionsArray, $value["access_restrictions"]);
			$this->_restrictions = $restrictMe->display();

        ///////////////
        // Display Note
        ///////////////

			$this->_note = $value["display_note"];
			$this->_display_note = $value["display_note"];

        ///////////////
        // A-Z List
        ///////////////

			$this->_az_display = $value["eres_display"];

        ///////////////
        // Secret? call number
        ///////////////

			$this->_call_number = $value["call_number"];

        //////////////
        // Ctags
        //////////////

			$this->_ctags = $value["ctags"];

        //////////////
        // Help Guide
        //////////////

			$this->_helpguide = $value["helpguide"];

			$new_loc = self::outputLocation();

			$this->_boxcount++;

      } // End location inner loop
    } // End location test outer loop
 }

 private function outputLocation() {
 	global $IconPath;
 	global $all_ctags;
 	global $wysiwyg_desc;
 	global $BaseURL;
 	global $CKPath;
 	global $CKBasePath;

 	if ($this->_call_number) {
 		$input_callnum_class = "call_num_box_vis";
 	} else {
 		$input_callnum_class = "call_num_box";
 	}

 	// do we want the checkurl image?
 	$checkurl_icon = "<span class=\"checkurl_img_wrapper\"><img src=\"$IconPath/linkcheck.png\" alt=\"check url\" title=\"check url\" border=\"0\" class=\"checkurl_img\" /></span>";

   // get appropriate text for format box title line
   $format_label_text = _("Location (Enter URL)");  // default for new record

 	switch ($this->_format) {
 		case 1:
 		$format_label_text = _("Location (Enter URL)");
 		break;
 		case 2:
 		$format_label_text = _("Location (Enter Call Number)");
 		$checkurl_icon = "";
 		break;
 		case 3:
 		$format_label_text = _("Location (Enter Persistent Catalog URL--include http://)");
 		break;
 	}
 	echo "
 	<div class=\"box no_overflow location_box\">
 	<span class=\"record_label\">$format_label_text</span><br />
 	<input type=\"hidden\" value=\"{$this->_location_id}\" name=\"location_id[]\" />
 	<input type=\"text\" class=\"record_location check_url required_field\" name=\"location[]\" size=\"60\" value=\"{$this->_location}\" />$checkurl_icon<br />
 	<span class=\"smaller url_feedback\"></span>
 	<div class=\"$input_callnum_class\"><span class=\"record_label\">" . _("Call Number") . "</span><br /><input type=\"text\" value=\"{$this->_call_number}\" name=\"call_number[]\" size=\"20\" /></div>
 	<br style=\"clear: both;\" />
 	<div style=\"float: left; margin-right: 1em;\"><span class=\"record_label\">" . _("Format") . "</span><br />
 	{$this->_formats}</div>
 	<div style=\"float: left; margin-right: 1em;\"><span class=\"record_label\">" . _("Access Restrictions") . "</span><br />
 	{$this->_restrictions}<br /></div>";

 	if (isset($_SESSION["eresource_mgr"]) && $_SESSION["eresource_mgr"] == "1") {
 		echo "<div style=\"float: left; width: 33%;\"><br />";

      // A-Z DB List
 		$a_z_string = "<input type=\"hidden\" name=\"eres_display[]\" value=\"" . $this->_az_display . "\" />";
 		$az_text = _("A-Z DB List");
 		if ($this->_az_display == "Y") {
 			$a_z_string .= "<span class=\"aztag-on\">$az_text</span>";
 		} else {
 			$a_z_string .= "<span class=\"aztag-off\">$az_text</span>";
 		}

 		echo "
 		$a_z_string<br /></div>
 		<div style=\"clear: both;\"><br /><span class=\"record_label\">" . _("Display Note") . "</span><br />";

 		if ($wysiwyg_desc == 1 && $this->_boxcount == 1) {
 		//	include ($CKPath);
    	// Create and output object
            /*
 			$oCKeditor = new CKEditor($CKBasePath);
 			$oCKeditor->timestamp = time();
    	$config['toolbar'] = 'Basic';// Default shows a much larger set of toolbar options
    	$config['height'] = 75;
    	$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

    	echo $oCKeditor->editor('display_note[]', $this->_display_note, $config);
            */
            
            echo "<textarea name=\"display_note\"  rows=\"4\" cols=\"70\">" . stripslashes($this->_description) . "</textarea>";
            echo "<script src='../../CKEditor/ckeditor.js'></script>";
            echo "<script type='text/javascript'> window.onload = function(){ CKEDITOR.replace( 'description' ); };  </script>";
    	echo "<br />";
    } else {
    	echo "<textarea name=\"display_note[]\" rows=\"2\" cols=\"50\">" . stripslashes($this->_display_note) . "</textarea>";
    }

    echo "</div>
    <div style=\"clear: both;\"><br /><span class=\"record_label\">" . _("Help Guide Location") . "</span>  <br /><input type=\"text\" value=\"{$this->_helpguide}\" name=\"helpguide[]\" size=\"60\" /></div>
    ";
 } else {
 	echo "<br /><br /><input type=\"hidden\" name=\"eres_display[]\" value=\"{$this->_az_display}\">
 	<input type=\"hidden\" name=\"display_note[]\" value=\"{$this->_note}\" />";
 }

 echo "<br />";

 echo "<input type=\"hidden\" name=\"ctags[]\" value=\"" . $this->_ctags . "\" />
 <span class=\"record_label\">ctags:</span> ";

 $current_ctags = explode("|", $this->_ctags);
    $tag_count = 0; // added because if you have a lot of ctags, it just stretches the div forever

    foreach ($all_ctags as $value) {
    	if ($tag_count == 5) {
    		echo "<br />";
    		$tag_count = 0;
    	}

    	if (in_array($value, $current_ctags)) {
    		echo "<span class=\"ctag-on\">$value</span>";
    	} else {
    		echo "<span class=\"ctag-off\">$value</span>";
    	}
    	$tag_count++;
    }

    echo "<div class=\"delete_location\">" . _("X Delete this location") . "</div>
    </div>";
 }

 public function outputSubject($value) {
 	global $IconPath;

 	$subject_name = Truncate($value[6], 25, '');
 	$source_name = Truncate($value[7], 15, '');

    // check if the note override icon should be active or in
 	if ($value[5] != "") {
 		$note_icon = "note_override.png";
 	} else {
 		$note_icon = "note_override_inactive.png";
 	}

    // check if the source override icon should be active or in
 	if ($value[3] != $this->_def_source[0][0]) {
 		$source_icon = "source_override.png";
 	} else {
 		$source_icon = "source_override_inactive.png";
 	}

 	$oursubjects = "
 	<div class=\"selected_item_wrapper\">
 	<div class=\"selected_item\">
 	<input name=\"subject[]\" value=\"$value[1]\" type=\"hidden\" />
 	<input name=\"rank[]\" value=\"$value[0]\" type=\"hidden\" />
 	<input name=\"source[]\" value=\"$value[3]\" id=\"hidden_source-$value[1]-$value[3]\" type=\"hidden\" />
 	$subject_name <span class=\"small_extra\">$source_name</span><br />
 	<textarea style=\"display: none; clear: both;\" class=\"desc_override\" name=\"description_override[]\" rows=\"4\" cols=\"35\">$value[5]</textarea>
 	</div>
 	<div class=\"selected_item_options\">
 	<img src=\"$IconPath/delete.png\" class=\"delete_sub\" alt=\"" . _("remove subject") . "\" title=\"" . _("remove subject") . "\" border=\"0\" />
 	<img src=\"$IconPath/$source_icon\" class=\"source_override\" id=\"source_override-$value[1]-$value[3]\" alt=\"" . _("change source type") . "\" title=\"" . _("change source type") . "\" border=\"0\" />
 	<img src=\"$IconPath/$note_icon\" class=\"note_override\" id=\"note_override-$value[1]-$value[3]\" alt=\"" . _("add description override") . "\" title=\"" . _("add description override") . "\" border=\"0\" />
 	</div>
 	</div>";

 	return $oursubjects;
 }

 public function deleteRecord() {

    // make sure they're allowed to delete
 	if (!isset($_SESSION["eresource_mgr"]) || $_SESSION["eresource_mgr"] != "1") {
 		$this->_debug = _("Permission denied to delete.");
 		return FALSE;
 	}

    // Delete the location, location_title and title records
 	$q = "DELETE location , location_title, title
 	FROM location,location_title, title
 	WHERE location.location_id = location_title.location_id
 	AND title.title_id = location_title.title_id
 	AND title.title_id = '" . $this->_record_id . "'";

 	$delete_result = mysql_query($q);

 	$this->_debug = "<p>Del query: $q";

 	if (mysql_affected_rows() != 0) {
 		$q2 = "DELETE FROM rank WHERE title_id = '" . $this->_record_id . "'";
 		$delete_result2 = mysql_query($q2);
 		$this->_debug .= "<p>Del query 2: $q2";
 	} else {
      // message
 		$this->_message = _("There was a problem with your delete (stage 1 of 2).");
 		return FALSE;
 	}

 	if ($delete_result2) {
      // message
 		$this->_message = _("Thy will be done.  Offending record deleted.");

      // /////////////////////
      // Alter chchchanges table
      // table, flag, item_id, title, staff_id
      ////////////////////

 		$updateChangeTable = changeMe("record", "delete", $this->_record_id, $this->_title, $_SESSION['staff_id']);

 		return TRUE;
 	} else {
      // message
 		$this->_message = _("There was a problem with your delete (stage 2 of 2).");
 		return FALSE;
 	}
 }

 public function insertRecord($notrack = 0) {

    // dupe check
    ////////////////
    // Insert title table
    ////////////////
 	$our_title = mysql_real_escape_string(scrubData($this->_title));
 	$our_alternate_title = mysql_real_escape_string(scrubData($this->_alternate_title));
 	$our_prefix = mysql_real_escape_string(scrubData($this->_prefix));

 	$qInsertTitle = "INSERT INTO title (title, alternate_title, description, pre) VALUES (
 		'" . $our_title . "',
 		'" . $our_alternate_title . "',
 		'" . mysql_real_escape_string(scrubData($this->_description, "richtext")) . "',
 		'" . $our_prefix . "'
 		)";

$rInsertTitle = mysql_query($qInsertTitle);

$this->_debug .= "<p>1. insert title: $qInsertTitle</p>";
if (!$rInsertTitle) {
	echo blunDer("We have a problem with the insert title query: $qInsertTitle");
}

$this->_record_id = mysql_insert_id();
$this->_title_id = $this->_record_id;

    /////////////////////
    // insert into rank
    ////////////////////

self::modifyRank();

    /////////////////////
    // insert/update locations
    ////////////////////

self::modifyLocation();

    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////
if ($notrack != 1) {
	$updateChangeTable = changeMe("record", "insert", $this->_record_id, $our_title, $_SESSION['staff_id']);
}


    // message
$this->_message = _("Thy Will Be Done.  Record added.");
}

public function updateRecord($notrack = 0) {

    // dupe check
    /////////////////////
    // update title table
    /////////////////////

	$our_title = mysql_real_escape_string(scrubData($this->_title));
	$our_alternate_title = mysql_real_escape_string(scrubData($this->_alternate_title));
	$our_prefix = mysql_real_escape_string(scrubData($this->_prefix));

	$qUpTitle = "UPDATE title SET title = '" . $our_title . "', alternate_title = '" . $our_alternate_title . "', description = '" . mysql_real_escape_string(scrubData($this->_description, "richtext")) . "', pre = '" . $our_prefix . "' WHERE title_id = " . scrubData($this->_title_id, "integer");

	$rUpTitle = mysql_query($qUpTitle);

	$this->_debug = "<p>1. update title: $qUpTitle</p>";
	if (!$rUpTitle) {
		print "affected rows = " . mysql_affected_rows();
		echo blunDer("We have a problem with the title query: $qUpTitle");
	}

    /////////////////////
    // clear rank
    /////////////////////

	$qClearRank = "DELETE FROM rank WHERE title_id = " . $this->_title_id;

	$rClearRank = mysql_query($qClearRank);

	$this->_debug .= "<p>2. clear rank: $qClearRank</p>";

	if (!$rClearRank) {
		echo blunDer("We have a problem with the clear rank query: $qClearRank");
	}

    /////////////////////
    // insert into rank
    ////////////////////

	self::modifyRank();

    // wipe entry from intervening table, location_title
	$qClearLoc = "DELETE FROM location_title WHERE title_id = " . scrubData($this->_title_id, "integer");
	$rClearLoc = mysql_query($qClearLoc);

	$this->_debug .= "<p>4. wipe location_title: $qClearLoc</p>";
	if (!$rClearLoc) {
		echo blunDer("We have a problem with the clear locations query: $qClearLoc");
	}

    /////////////////////
    // insert/update locations
    ////////////////////

	self::modifyLocation();

    // /////////////////////
    // Alter chchchanges table
    // table, flag, item_id, title, staff_id
    ////////////////////

	if ($notrack != 1) {
		$updateChangeTable = changeMe("record", "update", $this->_title_id, $our_title, $_SESSION['staff_id']);
	}

    // message
	$this->_message = _("Thy Will Be Done.  Record updated.");
}

function modifyRank() {
	for ($i = 0; $i < $this->_subject_count; $i++) {
		$qUpRank = "INSERT INTO rank (rank, subject_id, title_id, source_id, description_override) VALUES (
			'" . scrubData($this->_rank[$i], "integer") . "', ";
		//added dgonzalez to check whether the value must be inserted into database as NULL
		$qUpRank .= scrubData($this->_subject[$i], "integer") != 0 ? "'" . scrubData($this->_subject[$i], "integer") . "'," : "NULL, ";
		$qUpRank .= scrubData($this->_title_id, "integer") != 0 ? "'" . scrubData($this->_title_id, "integer") . "'," : "NULL, ";
		$qUpRank .= scrubData($this->_source[$i], "integer") != 0 ? "'" . scrubData($this->_source[$i], "integer") . "'," : "NULL, ";
		$qUpRank .= "'" . mysql_real_escape_string(scrubData($this->_description_override[$i], "richtext")) . "')";

		$rUpRank = mysql_query($qUpRank);

		$this->_debug .= "<p>3. (update rank loop) : $qUpRank</p>";
		if (!$rUpRank)
		{
			echo blunDer("We have a problem with the rank query: $qUpRank");
		}
	}
}

function modifyLocation() {
	foreach ($this->_location_id as $key => $value) {
      // wipe entry in location_title

		if ($value == "") {

        // Blank location, do an insert
			$qInsertLoc = "INSERT INTO location (format, call_number, location, access_restrictions, eres_display, display_note, ctags, helpguide) VALUES (
				'" . scrubData($this->_format[$key], "integer") . "',
				'" . mysql_real_escape_string(scrubData($this->_call_number[$key])) . "',
				'" . mysql_real_escape_string(scrubData($this->_location[$key])) . "',
				'" . scrubData($this->_access_restrictions[$key], "integer") . "',
				'" . scrubData($this->_eres_display[$key]) . "',
				'" . mysql_real_escape_string(scrubData($this->_display_note[$key], "richtext")) . "',
				'" . mysql_real_escape_string(scrubData($this->_ctags[$key])) . "',
				'" . mysql_real_escape_string(scrubData($this->_helpguide[$key])) . "'
				)";

$rInsertLoc = mysql_query($qInsertLoc);

$this->_debug .= "<p>5a. insert location loop: $qInsertLoc</p>";
if (!$rInsertLoc) {
	echo blunDer("We have a problem with the insert locations query: $qInsertLoc");
}

$current_location_id = mysql_insert_id();
} else {
        // Existing location, do an update
	$qUpLoc = "UPDATE location SET format = '" . scrubData($this->_format[$key], "integer") .
	"', call_number = '" . scrubData($this->_call_number[$key]) .
	"', location = '" . scrubData($this->_location[$key]) .
	"', access_restrictions = '" . scrubData($this->_access_restrictions[$key], "integer") .
	"', eres_display = '" . scrubData($this->_eres_display[$key]) .
	"', display_note = '" . scrubData($this->_display_note[$key], "richtext") .
	"', ctags = '" . mysql_real_escape_string(scrubData($this->_ctags[$key])) .
	"', helpguide = '" . mysql_real_escape_string(scrubData($this->_helpguide[$key])) .
	"' WHERE location_id = " . scrubData($this->_location_id[$key], "integer");

	$rUpLoc = mysql_query($qUpLoc);

	$this->_debug .= "<p>5b. update location loop: $qUpLoc</p>";
	if (!$rUpLoc) {
		echo blunDer("We have a problem with the update locations query: $qUpLoc");
	}

	$current_location_id = scrubData($this->_location_id[$key]);
	$this->_debug .= "<p>current loc id = $current_location_id";
}
      // If/else over, now do an insert to location_title

$qInsertLocTitle = "INSERT INTO location_title (title_id, location_id) VALUES (
	" . scrubData($this->_title_id, "integer") . ",
	$current_location_id
	)";
$this->_debug .= "<p>6. insert into location_title: $qInsertLocTitle</p>";
;
$rInsertLocTitle = mysql_query($qInsertLocTitle);

if (!$rInsertLocTitle) {
	echo blunDer("We have a problem with the insert location_title query: $qInsertLocTitle");
}
}
}

function getMessage() {
	return $this->_message;
}

function getRecordId() {
	return $this->_record_id;
}

function deBug() {
	print $this->_debug;
}

}

?>