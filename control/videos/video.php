<?php
/**
 *   @file video.php
 *   @brief Create the metadata for a video stored elsewhere; also update and delete
 *
 *   @author adarby
 *   @date feb 2012
 * 	  @todo 
 */
$subcat = "video";
$page_title = "Manage Video";

// Suppress header if it is to be shown in colorbox or popup window
if (isset($_REQUEST["wintype"]) && $_REQUEST["wintype"] == "pop") {
    $no_header = "yes";
} else {
  $_REQUEST["wintype"] = NULL;
}

// init
$feedback = "";
$no_form = "";

include("../includes/header.php");

// Connect to database
try {
    $dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}


// Test our record_id, if it exists; must be integer
if (isset($_GET["video_id"])) {
  $check_record_id = is_numeric($_GET["video_id"]);
  if ($check_record_id == 1) {
    $ok_record_id = $_GET["video_id"];
  }
} else {
  $ok_record_id = "";
}


if (isset($_POST["delete_record"]) || isset($_GET["delete_record"])) {
    // make sure there's a record_id

    if ($ok_record_id != "") {
        // do the delete
        $record = new sp_Video($ok_record_id, "delete");
        $record->deleteRecord();
        //$record->deBug();
        // Show feedback
        $feedback = $record->getMessage();
        // don't display the form again
        $no_form = TRUE;
    } else {
        $feedback = "There is no record by that ID.";
    }
}

if (isset($_POST["submit_record"])) {

    // Submit form

    $record = new sp_Video($_POST["video_id"], "post");

    //////////////////////////////////
    // Is this an Insert or an update?
    //////////////////////////////////

    if ($_POST["video_id"] == "") {
        $record->insertRecord();
        $ok_record_id = $record->getRecordId();
    } else {
        $record->updateRecord();
    }

    // Show feedback
    $feedback = $record->getMessage();
    // See all the queries?
    //$record->deBug();
}

$record = new sp_Video($ok_record_id);

// show feedback if it isn't already set
if (!$feedback) {
    $feedback = $record->getMessage();
}

if ($_REQUEST["wintype"] == "pop") {
    print "<div id=\"maincontent\">";
}

echo "<div class=\"feedback\">$feedback</div><br /><br />";

/////////////////////////
// SHOW FORM
// If the form isn't suppressed, output it.  It will be blank if there's no id
/////////////////////////

if (!$no_form) {
    $record->outputForm($_REQUEST["wintype"]);
}

//$record->deBug();

include("../includes/footer.php");
?>

<script type="text/javascript">

    $(function (){
        ///////////////
        /* vtags     */
        ///////////////

        $("span[class*=ctag-]").livequery('click', function() {
	
            var all_tags = "";
	
            // change to other class
            if ($(this).attr("class") == "ctag-off") {
                $(this).attr("class", "ctag-on");
            } else {
                $(this).attr("class", "ctag-off");
            }

            // determine the new selected items
            $(this).parent().find(".ctag-on").each(function(i) {
                var this_ctag = $(this).text();
                all_tags = all_tags + this_ctag + "|";
		
            });
            // strip off final pipe (|)
            all_tags = all_tags.replace( /[|]$/, "" );
            // set new value to hidden form field
            $(this).parent().find("input[name*=vtags]").val(all_tags);
	

        });
        
        ////////////////
        // Check Submit
        // When the form has been submitted, check required fields
        ////////////////

        $("#new_record").submit( function () {

            // If a required field is empty, set zonk to 1, and change the bg colour
            // of the offending field
            var alerter = 0;

            $("*[class*=required_field]").each(function() {
                // get contents of string, trim off whitespace
                var our_contents = $(this).val();
                var our_contents  = jQuery.trim(our_contents );

                if (our_contents  == '') {
                    $(this).attr("style", "background-color:#FFDFDF");
                    alerter = 1;
                } else {
                    $(this).attr("style", "background-color:none");
                }

                return alerter;

            });



            if (alerter == 1) {
                alert("<?php print _("You must complete all required form fields."); ?>");
                return false;
            } 

        });

        //////////////////
        // Make sure that delete was intentional
        //////////////////
        $('.delete_button').livequery('click', function(event) {

            $(this).after('<div class="rec_delete_confirm"><?php print $rusure; ?>  <a id="confirm-yes-<?php print $ok_record_id; ?>"><?php print $textyes; ?></a> | <a id="confirm-no"><?php print $textno; ?></a></div>');

            return false;
        });


        $('a[id*=confirm-yes]').livequery('click', function(event) {
            var this_record_id = $(this).attr("id").split("-");
            // see if we're in a popup window, and tweak string accordingly
            var pop_status = "<?php print $_REQUEST["wintype"]; ?>";

            var delete_url = "video.php?video_id=" + this_record_id[2] + "&delete_record=true&wintype=" + pop_status;

            window.location.replace(delete_url);

            return false;

        });

        $('a[id*=confirm-no]').livequery('click', function(event) {

            $(this).parent().remove();

            return false;
        });


    });


</script>
