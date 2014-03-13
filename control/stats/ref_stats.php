<?php

/**
 *   @file ref_stats.php
 *   @brief Transactions statistics 
 *
 *   @author adarby
 *   @date mar 2014
 */

use SubjectsPlus\Control\DBConnector;

$subcat = "stats";
$page_title = "Stats in SP";

include("../includes/config.php");
include("../includes/header.php");


try {
  $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
  echo $e;
}

if (isset($_POST["submit_stat"])) {

  // Submit form

  $record = new RefStat($_POST["refstat_id"], "post");

  //////////////////////////////////

  if ($_POST["faq_id"] == "") {
    $record->insertRecord();
    $ok_record_id = $record->getRecordId();
  } 

  // Show feedback
  $feedback = $record->getMessage();
  // See all the queries?
  //$record->deBug();

echo "<div class=\"feedback\">$feedback</div><br /><br />";  

}




?>

<form class="pure-form">

<div id="titlezone">
    <div class="pure-g-r">
        <div class="pure-u-1">
          <h1>Add Transaction</h1>

        <span>
     <select class="form-control" style="">
      <option>Architecture</option>
      <option>Business</option>
      <option>CHC</option>
      <option>Circulation (Richter)</option>
      <option>Digital Media Lab</option>
      <option selected="selected">Info Desk (Richter)</option>
      <option>Music</option>
      <option>RSMAS</option>
      <option>Special Collections</option>
      <option>Other (add note)</option>
    </select>
    </span>

    <span><input type="text" name="datetime" value="<?php print date("F j, Y, g:i a");  ?>" size="30" /></span>    

    <span><img src="<?php print $IconPath; ?>/pie_chart.png" alt="See Reports" title="See Reports" /></span> 
        </div>
    </div>
</div>


<?php 
date_default_timezone_set('America/New_York');
$ourboxes = '

<div class="checkbox">
  <label>
    <input type="checkbox" value=""> Computer Hardware
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value=""> Computer Software
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value=""> Directional
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value=""> Printers/Copiers
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value=""> Reference
  </label>
</div>
<br />
<textarea class="form-control" rows="2" placeholder="notes"></textarea>
';

$our_refs = array ("In Person", "Phone", "Email", "IM");

$boxes = "<div class=\"pure-g-r bigreadable\"><div class=\"pure-u-1\">  ";

foreach ($our_refs as $key => $value) {
  $boxes .= "

  <div class=\"pluslet inline-block\" id=\"pluslet-$key\" name=\"\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">$value</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">$ourboxes
    <br />
    <p><a class=\"pure-button pure-button-primary\">Add $value</a>&nbsp; x &nbsp;<input name=\"times-$key\" type=\"text\" value=\"1\" size=\"1\" /></p>
    </div>
  </div>

  ";
}

$boxes .= "</div";

print $boxes;
?>



</form>



<?php
include("../includes/footer.php");
?>
