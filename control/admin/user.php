<?php
/**
 *   @file admin/user.php
 *   @brief Update staff information for individual
 *   @description
 *
 *   @author adarby
 *   @date Jan 2011; last rev dec 2014
 *   @todo
 */
    
use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\Querier;
  
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

//Do we want to show inactive users?
    if (isset($_GET["show"]) && $_GET["show"] == "all") {
        $active_inactive = _("Show only active users"); 
        $active_inactive_url = "";
        $wantactive = "";
        $about_that_star = "<br />" . _("* = inactive user");
    } else {
        $active_inactive = _("Show inactive users"); 
        $active_inactive_url = "&show=all";
        $wantactive = "AND active = '1'";
        $about_that_star = "";
    }

    $q = "SELECT user_type_id, user_type FROM user_type ORDER BY user_type_id";

    $querier = new Querier();
    $typeArray = $querier->query($q);

    print "<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">";

    // Loop through user types
    foreach ($typeArray as $value) {
        $staff_list = "";
        $staffArray = "";
        $our_title = $value[1];
        $q2 = "SELECT staff_id, fname, lname, email, ptags, active FROM staff WHERE user_type_id = " . $value[0] . " $wantactive ORDER BY lname, fname";
        $querier2 = new Querier();
        $staffArray = $querier2->query($q2);

        $staff_list .= "";

        // if there are no results
        if (!$staffArray) {
            $staff_list .= "<p>" . _("None registered.  Just as well.  They're going to rise up against us someday.") . "</p>";
        } else {

            $staff_list .= "<p>" . _("Click on a name to update details and privileges.") . " $about_that_star</p>";

            // set up striping
            $row_count = 0;
            $colour1 = "oddrow";
            $colour2 = "evenrow";

            foreach ($staffArray as $staff) {
                
                $bonus_style = "";
                // unpack the ptags
                $these_tags = "";
                $current_ptags = explode("|", $staff[4]);

                foreach ($all_ptags as $value2) {
                    if (in_array($value2, $current_ptags)) {
                        $these_tags .= "<span class=\"ctag-on\" style=\"\">$value2</span> ";
                    } else {
                        $these_tags .= "<span class=\"ctag-off\" style=\"\">$value2</span> ";
                    }
                }
                $row_colour = ($row_count % 2) ? $colour1 : $colour2;
   
                $button = "<button id=\"save_changes-$staff[0]\" rel=\"\" style=\"display: none;\">" . _("Update Permissions") . "</button>"   ;         

                // put star if inactive
                $inactive_clue = "";
                if ($staff[5] != 1) { $inactive_clue = " * ";}
                
                $staff_list .= "<div class=\"staff_container\"><div class=\"$row_colour striper\" style=\"clear: both; float: left; min-width: 200px;\">
                $inactive_clue<a href=\"user.php?staff_id=$staff[0]\">";
                    // if there's no last name, we display email 
                    if($staff[2] != "") { $staff_list .= "$staff[2], $staff[1]"; } else { $staff_list .= "$staff[3]"; }

                $staff_list .= "</a></div>
                <div id=\"user-$staff[0]\" class=\"$row_colour striper\">$these_tags $button<span></span>
                </div></div>";
                
                
                $row_count++;
            }
        }

        $staff_list .= "";
        makePluslet($value[1], $staff_list, "no_overflow");
    }

    
    //print $staff_list;
    print "</div>
    <div class=\"pure-u-1-3\">";

    // config
    $config_box2 = "<div class=\"onoffswitch\">
    <input id=\"active_inactive\" class=\"onoffswitch-checkbox2\" type=\"checkbox\" checked>
    <label class=\"onoffswitch-label\" for=\"active_inactive\">
    <span class=\"onoffswitch-inner\"></span>
    <span class=\"onoffswitch-switch\"></span>
    </label>
    <span class=\"settings-label-text\" style=\"color: #333; font-weight: bold;\">Hide Inactive Users</span>
    </div>";

    
    $config_box = "<p><a href=\"user.php?browse$active_inactive_url\">$active_inactive</a></p>";

    makePluslet(_("Options"), $config_box, "no_overflow");

    // time to give some help
    $privs_blurb = _("Select which parts of SubjectsPlus this user may access.
                <p><strong>records</strong> allows access to both the Record and Guide tabs.
                <p><strong>eresource_mgr</strong> allows the user to see all the information about a Record (and delete it), and quickly see all guides.
                <p><strong>admin</strong> allows access to the overall admin of the site.
                <p><strong>librarian</strong> means user shows up in lists of librarians.
                <p><strong>supervisor</strong> means user shows up in list of supervisors
                <p><strong>view_map</strong> lets user see the map of where everyone lives.  Probably only for muckymucks.  Might not be implemented on your site; check wiki for help.
                ");

    makePluslet(_("On Privilege"), $privs_blurb, "no_overflow");

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

                // Show inactive users
        $(document).on('click', '.onoffswitch-checkbox2', function() {
            alert("boo"); //staff_container
            ('.staff_container').css('display: block;');
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
        $record = new Staff($ok_record_id, "delete", TRUE);
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
    $record = new Staff($_POST["staff_id"], "post", TRUE);

    
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
    // $record->deBug();
}

/////////////////////
// Start the record display
////////////////////
print feedBack($feedback);
    
$record = (!isset($record)) ? new Staff($ok_record_id, '', TRUE) : $record;

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

        $("*[class*=required_field]").prevUntil('div', 'span').append('<span style="color: red;">*</span>');

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
