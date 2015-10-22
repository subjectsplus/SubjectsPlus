<?php
/**
 *   @file admin/profile.php
 *   @brief Update staff information for individual
 *   @description
 *
 *   @author adarby
 *   @date Jan 2011
 *   @todo
 */
    
use SubjectsPlus\Control\Staff;
use SubjectsPlus\Control\Querier;
  
$page_title = "Admin::Users";

include("../includes/header.php");

// init
$feedback = "";
$ok_record_id = $_SESSION["staff_id"];

//////////////////
// Record View
//////////////////

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
        $record->updateRecord("brief");
    }

    // Show feedback
    $feedback = $record->getMessage();

    // See query?
    //$record->deBug();
} else {

/////////////////////
// Start the record display
////////////////////

$record = new Staff($ok_record_id, '', TRUE);

// show feedback if it isn't already set
if (!$feedback) {
    $feedback = $record->getMessage();
}

echo "<div class=\"feedback\">$feedback</div><br /><br />";
}

$record->outputSelfEditForm();


//$record->deBug();

include("../includes/footer.php");
?>

<script type="text/javascript">

    var headshot_location = "<?php print $record->getHeadshotLoc(); ?>";

    $(function (){

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



    });

</script>
