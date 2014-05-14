<?php
   namespace SubjectsPlus\Control;
/**
 *   @file RefStat
 *   @brief manage refstat submissions (called by stats/ref_stats.php)
 *
 *   @author agdarby
 *   @date Jan 2014
 *   @todo better blunDer interaction, better message, maybe hide the blunder errors until the end
 */
class RefStat {

  private $_refstat_id;
  private $_location_id;
  private $_type_id; 
  private $_mode_id; // this is created by js
  private $_date;
  private $_note;
  private $_submit_times_x; // how many times this submit should be looped

  public function __construct($refstat_id="", $flag="") {

    if ($flag == "" && $refstat_id == "") {
      $flag = "empty";
    }

    switch ($flag) {

      case "post":
        // prepare record for insertion 
        // based on mode id, only select certain values
        $this->_mode_id = $_POST["mode_id"];
        $this->_note = $_POST["notes" . "-" . $this->_mode_id];
        $this->_submit_times_x = $_POST["times" . "-" . $this->_mode_id];

        $this->_location_id = $_POST["location_id"];
        $this->_type_id = $_POST["type_id"];

        // example:  March 19, 2014, 1:20 pm
        $format = 'F d, Y, g:i a';
        $ourdate =$_POST["datetime"];
        $date = \DateTime::createFromFormat($format, $ourdate);
        $this->_date = $date->format('Y-m-d H:i:s');


        break;
      case "delete":
        // No delete for this

        break;

      case "empty":
      default:

        $this->_refstat_id = $refstat_id;

        /////////////
        // Qyery uml_refstat_mode table for list of modes (e.g., email, in person, etc.)
        /////////////

        $querier1 = new Querier();
        $q1 = "SELECT mode_id, label
                FROM uml_refstats_mode
                ORDER BY label";

        $this->_modes = $querier1->query($q1);

        $this->_debug .= "<p>Modes query: $q1";

        ///////////////////
        // Query location table
        // used to get our set of subjects
        // ////////////////

        $querier2 = new Querier();
        $q2 = "SELECT location_id, label
                FROM uml_refstats_location
                ORDER BY label";

        $this->_locations = $querier2->query($q2);

        $this->_debug .= "<p>Locations query: $q2";

        ///////////////////
        // Query type table
        // used to get our list of types of ref requests (e.g., printing)
        // ////////////////

        $querier3 = new Querier();
        $q3 = "SELECT type_id, label
                FROM uml_refstats_type
                ORDER BY label";

        $this->_types = $querier3->query($q3);


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
  echo "<option value=\"$value[0]\">$value[1]</option>";
  }

echo "
</select>

<span><input type=\"text\" name=\"datetime\" value=\"" . date("F j, Y, g:i a") . "\" size=\"30\" /></span>

<br style=\"clear: both;\" />";

// loop through our modes, to create one box for each

foreach ($this->_modes as $value) {
  $box_content = "";
  // create the radio buttons

  // loop through the types
  foreach ($this->_types as $value2) {
    $box_content .= "
        <label for=\"option-$value[1]-$value2[0]\" class=\"pure-radio\">
        <input id=\"option-$value[1]-$value2[0]\" type=\"radio\" name=\"type_id\" value=\"$value2[0]\">
        $value2[1]
    </label>";
  }

  $box_content .= "<textarea class=\"form-control\" rows=\"2\" placeholder=\"notes\" name=\"notes-$value[0]\"></textarea>
  <br />
  <p><input class=\"pure-button pure-button-primary\" type=\"submit\" value=\"Add $value[1]\" name=\"submit_record-$value[0]\">
  &nbsp; x &nbsp;<input name=\"times-$value[0]\" type=\"text\" value=\"1\" size=\"1\" /></p>
  ";

  // create one pluslet per mode
  makePluslet ($value[1], $box_content, "inline-block");

}

echo "<input type=\"hidden\" name=\"mode_id\" value=\"\"/>";
echo "</form>";

  }



  public function insertRecord() {

    /////////////////////
    // update refstats table
    /////////////////////

    $db = new Querier;

    $qInsert = "INSERT INTO uml_refstats (type_id, location_id, mode_id, date, note) VALUES (
	  " . $db->quote(scrubData($this->_type_id, "integer")) . ",
	  " . $db->quote(scrubData($this->_location_id, "integer")) . ",
    " . $db->quote(scrubData($this->_mode_id, "integer")) . ",
    " . $db->quote(scrubData($this->_date, "text")) . ",
    " . $db->quote(scrubData($this->_note, "text")) . "
    )";

    //print $qInsert;

    // if we're doing multiple identicals. we loop
      $x = 0;
      while ($x < $this->_submit_times_x ) {
        $rInsert = $db->query($qInsert);
        $x++;
      }
      
    $this->_refstat_id = $db->last_id();

    $this->_debug = "<p>1. insert: $qInsert</p>";
    if (!$rInsert) {
      echo blunDer("We have a problem with the tb query: $qInsert");
    }

    // message
    $this->_message = _("Thy Will Be Done.");
  }


  function getMessage() {
    return $this->_message;
  }

  function getRecordId() {
    return $this->_refstat_id;
  }

  function deBug() {
    print $this->_debug;
  }

}

?>