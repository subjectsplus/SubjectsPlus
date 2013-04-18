<?php
/**
 *   @file admin/user.php
 *   @brief Update staff information for individual
 *   @description
 *
 *   @author adarby
 *   @date Jan 2011
 *   @todo
 */
$subcat = "admin";
$page_title = "Admin::Users";

// init
$feedback = "";

include("../includes/header.php");

// Test our record_id, if it exists; must be integer
if (isset($_REQUEST["staff_id"])) {
  $check_id = is_numeric($_REQUEST["staff_id"]);
} else {
  $check_id = "";
}


if ($check_id == TRUE) {
    $ok_record_id = $_REQUEST["staff_id"];
} else {
    $ok_record_id = "";
}

///////////////////
// Browse View
///////////////////
if (isset($_GET["browse"])) {

    $q = "SELECT user_type_id, user_type FROM user_type ORDER BY user_type_id";

    $querier = new sp_Querier();
    $typeArray = $querier->getResult($q);

    $staff_list = "";
    // Loop through user types
    foreach ($typeArray as $value) {

        $staff_list .= "<h2>" . $value[1] . "</h2>";

        $q2 = "SELECT staff_id, fname, lname, ptags FROM staff WHERE user_type_id = " . $value[0] . " ORDER BY lname, fname";
        $querier2 = new sp_Querier();
        $staffArray = $querier2->getResult($q2);

        $staff_list .= "<div class=\"box no_overflow\">";

        // if there are no results
        if (!$staffArray) {
            $staff_list .= "<p>" . _("None registered.  Just as well.  They're going to rise up against us someday.") . "</p>";
        } else {

            $staff_list .= "<p>" . _("Click on a name to update details and privileges") . "</p>";

            // set up striping
            $row_count = 0;
            $colour1 = "oddrow";
            $colour2 = "evenrow";

            foreach ($staffArray as $value2) {

                // unpack the ptags
                $these_tags = "";
                $current_ptags = explode("|", $value2[3]);
                foreach ($all_ptags as $value) {
                    if (in_array($value, $current_ptags)) {
                        $these_tags .= "<span class=\"ctag-on\" style=\"font-size: 11px;\">$value</span> ";
                    } else {
                        $these_tags .= "<span class=\"ctag-off\" style=\"font-size: 11px;\">$value</span> ";
                    }
                }
                $row_colour = ($row_count % 2) ? $colour1 : $colour2;
                $staff_list .= "<div class=\"$row_colour striper\" style=\"clear: both; float: left; min-width: 200px;\"><a href=\"user.php?staff_id=$value2[0]\">$value2[2], $value2[1]</a></div> <div id=\"user-$value2[0]\" class=\"$row_colour striper\" style=\"float: left;\">$these_tags <button id=\"save_changes-$value2[0]\" rel=\"\" style=\"display: none;\">" . _("Update Permissions") . "</button><span></span>
</div>";
                $row_count++;
            }
        }

        $staff_list .= "</div>";
    }

    print "<br /><div style=\"float: left; min-width: 500px;\">";
    print $staff_list;
    print "</div>";
    include("../includes/footer.php");
    ?>

<script type="text/javascript">
  $(document).ready(function(){
        ///////////////
        /* ptags     */
        ///////////////

        $("span[class*=ctag-]").livequery('click', function() {

            var all_tags = "";

            // change to other class
            if ($(this).attr("class") == "ctag-off") {
                $(this).attr("class", "ctag-on");
            } else {
                $(this).attr("class", "ctag-off");
            }

            var row_id = $(this).parent().find("button").attr("id").split("-");
            var row_id = row_id[1];

            $("#save_changes-" + row_id).next().empty();
            $("#save_changes-" + row_id).fadeIn();
            // determine the new selected items
            $(this).parent().find(".ctag-on").each(function(i) {
                var this_ctag = $(this).text();
                all_tags = all_tags + this_ctag + "|";

            });
            // strip off final pipe (|)
            all_tags = all_tags.replace( /[|]$/, "" );
            // set new value to hidden form field
            $(this).parent().find("button").val(all_tags);

            $(".save_changes").fadeIn();

        });

        $("button[id*=save_changes-]").livequery('click', function() {
            var row_id = $(this).attr("id").split("-");
            var new_vals = $(this).parent().find("button").attr("value");
            // load file to modify jquery
            $(this).hide();
            $(this).next().load("admin_bits.php", {action: 'update_permissions', ptags: new_vals, update_id: row_id[1]});
            //alert(new_vals);
        });
  });
</script>

<?php
    return;
}

//////////////////
// Record View
//////////////////

if (isset($_POST["delete_record"]) || isset($_GET["delete_record"])) {
    // make sure there's a record_id

    if ($ok_record_id != "") {
        // do the delete
        $record = new sp_Staff($ok_record_id, "delete", TRUE);
        $record->deleteRecord();
        //$record->deBug();
        // Show feedback
        $feedback = $record->getMessage();

        // Make form empty
    } else {
        $feedback = _("There is no record by that ID.");
    }
}

if (isset($_POST["submit_record"])) {

    // Submit form
    $record = new sp_Staff($_POST["staff_id"], "post", TRUE);

    //////////////////////////////////
    // Is this an insert or an update?
    //////////////////////////////////

    if ($_POST["staff_id"] == "") {
        $record->insertRecord();
        $ok_record_id = $record->getRecordId();
    } else {
        $record->updateRecord();
    }

    // Show feedback
    $feedback = $record->getMessage();

    // See query?
    //$record->deBug();
}

/////////////////////
// Start the record display
////////////////////

$record = new sp_Staff($ok_record_id, '', TRUE);

// show feedback if it isn't already set
if (!$feedback) {
    $feedback = $record->getMessage();
}

// Deal with situation with popup window
if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
    print "<div id=\"maincontent\">";
    $wintype = "pop";
} else {
  $wintype = "";
}

echo "<div class=\"feedback\">$feedback</div><br /><br />";

// Don't show a form
if (isset($_POST["delete_record"]) || isset($_GET["delete_record"])) {
    print "<div class=\"box\"><p>" . _("We're really going to miss that staff person.  Search above to delete someone else!") . "</p></div>";
} else {
    $record->outputForm($wintype);
}

//$record->deBug();

include("../includes/footer.php");
?>

<script type="text/javascript">

    var headshot_location = "<?php print $record->getHeadshotLoc(); ?>";

    $(function (){

        ///////////////////////
        // Reset Password & Load New Headshot
        // (this is in shared.js now)
        ///////////////////////


        ///////////////
        /* ctags     */
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
            $(this).parent().find("input[name*=ptags]").val(all_tags);


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

            // Popup warning if required fields not complete
            if (alerter == 1) {
                alert("<?php print _("You must complete all required form fields."); ?>");
                return false;
            }

        });

    	//////////////////
    	// Add red star to labels of required inputs
    	//////////////////

    	$("*[class*=required_field]").prev().prev().append('<span style="color: red;">*</span>');
    	//The telephone field needs an additional prev
    	$("#tel[class*=required_field]").prev().prev().append('<span style="color: red;">*</span>');

        //////////////////
        // Make sure that delete was intentional
        //////////////////
        $('.delete_button').livequery('click', function(event) {

            $(this).after('<div class="rec_delete_confirm"><?php print $rusure; ?>  <a id="confirm-yes-<?php print $ok_record_id; ?>"><?php print $textyes; ?></a> | <a id="confirm-no"><?php print $textno; ?></a></div>');

            return false;
        });


        $('a[id*=confirm-yes]').livequery('click', function(event) {
            var this_record_id = $(this).attr("id").split("-");
            var delete_url = "user.php?staff_id=" + this_record_id[2] + "&delete_record=true";
            window.location.replace(delete_url);
            return false;

        });

        $('a[id*=confirm-no]')
        .livequery('click', function(event) {

            $(this).parent().remove();

            return false;
        });

         ///////////////
        /* Coord Lookup */
        ///////////////

        $(".lookup_button").livequery('click', function() {
            // find our message div, clear out any message from before
            var feedback_div = $(this).parent().find(".url_feedback")
            feedback_div.empty();


            var address_location = $("#street_address").attr("value") + " " + $("#city").attr("value") + " " + $("#state").attr("value") + " " + $("#zip").attr("value");
            //alert(address_location);
            address_location = $.trim(address_location);
            if (!address_location) {
              // let's see if we can generate an address
              var our_address = "";
              alert ("Please enter an address, first");
              return;
            }
            // load a file which queries the api, returns values

            //alert(address_location);
            //$("#latitude").attr("value").load("admin_bits.php", {action: 'address_lookup', address: address_location}).fadeIn(1600);
            $.get('admin_bits.php', {action: 'address_lookup', address: address_location}, function(result) {
    $('#lat_long').val(result);
});

            return;


        });

    });

</script>