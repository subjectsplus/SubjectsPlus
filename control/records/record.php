<?php
/**
 *   @file record.php
 *   @brief Adding a new record.  Completely revised v. 0.10.  Re-revised 1.2
 *
 *   @author adarby
 *   @date Oct 2012
 * 	  @todo add last modified, info/help buttons for each field,
 *   check_url needs to be class rather than id, or have a unique id; title check for dupe in .js
  have an update button next to default_source type, to change all ?
 */


use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\Dropdown;
use SubjectsPlus\Control\Record;
use SubjectsPlus\Control\LinkChecker;
use SubjectsPlus\Control\Querier;

$subcat = "records";
$page_title = "Edit Record";
$wintype = "";

// Suppress header if it is to be shown in colorbox or popup window
if (isset($_REQUEST["wintype"]) && $_REQUEST["wintype"] == "pop") {
    $no_header = "yes";
}

$tertiary_nav = "yes";
include("../includes/header.php");

// The following is just for testing purposes, to turn on/off the delete functionality
//$_SESSION["eresource_mgr"] = 0;

// Connect to database



// Test our record_id, if it exists; must be integer
if (isset($_GET["record_id"])) {
  $check_id = is_numeric($_GET["record_id"]);
}else {
  $check_id = "";
}

if ($check_id == TRUE) {
    $ok_record_id = $_GET["record_id"];
} else {
    $ok_record_id = "";
}

if (isset($_POST["delete_record"]) || isset($_GET["delete_record"])) {
    // make sure there's a record_id

    if ($ok_record_id != "") {
        // do the delete
        $record = new Record($ok_record_id, "delete");
        $record->deleteRecord();
        //$record->deBug();
        // Show feedbck
        $feedback = $record->getMessage();
    } else {
        $feedback = "There is no record by that ID.";
    }
}

if (isset($_POST["submit_record"])) {

    // 1.  Make sure we have minimum non-dupe data
    // 1a. Make sure there is a title, location, and subject
    if ($_POST["title"] == "" || $_POST["location"][0] == "" || $_POST["subject"][0] == "") {

        echo "<div class=\"feedback\">" . _("You must have a title, location, and subject.  Please go back and fix these omissions.  And turn on JavaScript, for goodness sakes!") . "</div><br /><br />";

        exit;
    }

    // 1b. IF THIS IS AN INSERT make sure the title isn't an exact dupe
    if ($_POST["title_id"] == "") {

        $db = new Querier();
        $qDupe = "SELECT title_id, title FROM title WHERE title LIKE " . $db->quote($_POST["title"]) ;
        $dupetitleArray = $db->query($qDupe);
     
        if ($dupetitleArray) {
            echo _("There is already a record with this title: ") . "<a href=\"record.php?record_id=" . $dupetitleArray[0] . "\">" . $dupetitleArray[1] . "</a>.  " . _("Maybe do a search and make sure it doesn't already exist?");
            return FALSE;
        }
    }

    // Submit form

    $record = new Record($_POST["title_id"], "post");

    //////////////////////////////////
    // Is this an Insert or an update?
    //////////////////////////////////

    if ($_POST["title_id"] == "") {
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

$record = new Record($ok_record_id);

// show feedback if it isn't already set
if (!isset($feedback)) {
    $feedback = $record->getMessage();
}

print feedBack($feedback);

if (isset($_GET["wintype"]) && $_GET["wintype"] == "pop") {
    print "<div id=\"maincontent\">";
    $wintype = "pop";
}

//echo "<div class=\"feedback\">$feedback</div><br /><br />";



// output our record, which will be blank if there's no id

?> 

<div id="maincontent">

<?php

$record->outputForm($wintype);

//$record->deBug();

print "</div>"; // close #maincontent
include("../includes/footer.php");
?>

<script type="text/javascript">

    $(function (){

        /*
// Pre-load new record with subject, if called from within guide
var win_type = '<?php print $_GET["wintype"]; ?>';

if (win_type == "pop") {

        var our_sub_id = '<?php print ($_GET["caller_id"]); ?>';
        var our_sub_text = 'boo';
        var our_source_text = $('select[name=default_source_id] :selected').text();
        var our_source_id = $('select[name=default_source_id] :selected').val();

        $('#subject_list').prepend('<div class="new_subject-'+our_sub_id+'"></div>');
        $('.new_subject-'+our_sub_id+'').hide().load("record_bits.php", {type: 'add_subject', our_sub_id: our_sub_id, our_sub_text: our_sub_text, our_source_text: our_source_text, our_source_id:our_source_id}).fadeIn(1600);

} */

        // add in some default text
        //$("#record_title").defaultText("Enter Record Title");
        //$("#record_location").defaultText("Enter Record Location");

        ///////////////////////
        // Add another location
        ///////////////////////

        $('.add_location').livequery('click', function() {
            // create space
            $(this).before('<div class="new_location"></div>');
            $(".new_location").load("record_bits.php", {type: 'location'});

        	return false;
        });

        ///////////////////
        // delete a location
        ///////////////////

        $(".delete_location").livequery('click', function() {
            // make sure this isn't the only location first
            var numloc = $(".location_box").length;
            if (numloc >1) {
                $(this).parents('.new_location').remove();
                $(this).parents('.location_box').remove();
            } else {
                alert("Thwarted!  You must have at least one location for a record.");
            }
        });

        ///////////////////////
        // Format Change = Change Form
        ///////////////////////

        $('select[name*=format]').livequery('change', function() {

            // from format table 1 = web, 2 = print, 3 = print w/url
            var format_type_id = $(this).val()

            if (format_type_id == 1) {
                //alert("changed to web");

                // Enlarge Location Box (if necessary) and show life preserver (if necessary)
                $(this).parent().parent().children(".record_location").attr("size", 60);
                $(this).parent().parent().find(".checkurl_img").show();

                // hide call_num box
                $(".call_num_box").hide("slow");

            } else if (format_type_id == 2){
                // change to Print type

                // hide life preserver
                $(this).parent().parent().find(".checkurl_img").hide();
               
                $(".call_num_box").show("slow");

                // Shrink Location Box
                $(this).parent().parent().children(".record_location").attr("size", 30);


            } else if (format_type_id == 3){
                // change to Print with URL type:  Show secondary box
                // Enlarge Location Box (if necessary) and show life preserver (if necessary)
                $(this).parent().parent().children(".record_location").attr("size", 60);
                $(this).parent().parent().find(".checkurl_img").show();
                // show call_num box
                $(".call_num_box").show("slow");

            }

            var new_record_label = "";

            //$(this).parent().parent().children(".record_label").replaceWith(new_record_label);
            $(this).parent().parent().children(".record_label:first").load("record_bits.php", {type: 'new_record_label', format_type_id: format_type_id});
        });

        ///////////////////////////////////
        // add subject to list on dropdown
        ///////////////////////////////////

        $('select[name*=subject_id]').livequery ('change', function() {
            var our_sub_id = $(this).val();

            if (!our_sub_id) { return false;} // Only add subjects with values

            var our_sub_text = $('select[name*=subject_id] :selected').text();
            var our_source_text = $('select[name=default_source_id] :selected').text();
            var our_source_id = $('select[name=default_source_id] :selected').val();

            $('#subject_list').prepend('<div class="new_subject-'+our_sub_id+'"></div>');
            $('.new_subject-'+our_sub_id+'').hide().load("record_bits.php", {type: 'add_subject', our_sub_id: our_sub_id, our_sub_text: our_sub_text, our_source_text: our_source_text, our_source_id:our_source_id}).fadeIn(1600);

        });

        ///////////////////
        // delete a subject from dropdown
        ///////////////////

        $(".delete_sub").livequery('click', function() {

            // make sure this isn't the only location first
            var numsub = $(".selected_item_wrapper").length;
            if (numsub >1) {
                $(this).parent().parent().remove();
            } else {
                alert("Thwarted!  You must have at least one subject for a record.");
            }


        });

        ////////////////////
        // source override
        ///////////////////

        $(".source_override").livequery('click', function() {

            // extract caller's id; this_source_id[1] = subject id, this_source_id[2] = source type id
            var this_source_id = $(this).attr("id").split("-");

            // dim out the link, change class name so it can't be clicked
            $(this).attr("class", "unclickable");

            var our_item = $(this).parent().parent().find(".small_extra");

            // add our dropdown list

            new_sources = $.ajax({
                url: "record_bits.php",
                type: "POST",
                data: ({type: 'source_override', our_source_id: this_source_id[2], our_subject_id: this_source_id[1]}),
                dataType: "html",
                success: function(html) {
                    our_item.append(html);
                }
            });


        });

        // cancel the source override by clicking the X icon

        $(".cancel_add_source").livequery('click', function() {

            // extract caller's id
            var this_source_id = $(this).attr("id").split("-");

            // create link back to the clickable button
            var calling_link = '#source_override-' +this_source_id[1]+'-'+this_source_id[2];

            // remove the dropdown from dom
            $(this).parent().remove();

            // reset the source_override link/img to clickable
            $(calling_link).attr("class", "source_override");
        });

        // Clicking the + icon changes the hidden source value, changes the small text next to the subject,
        // makes the dropdown disappear

        $(".add_source").livequery('click', function() {

            // extract caller's id
            var this_source_id = $(this).attr("id").split("-");

            // find the text of the new source type & update the parenthetical text
            var new_source_text = $(this).parent().find("option:selected").text();
            new_source_text = '<span class="small_extra">' + new_source_text + '</span>';
            $(this).parent().parent().replaceWith(new_source_text);

            // update the hidden value
            var new_source_id = $(this).prev().val();
            var hidden_source = '#hidden_source-' +this_source_id[1]+'-'+this_source_id[2];
            $(hidden_source).attr("value", new_source_id);

            // remove the dropdown from dom
            $(this).parent().remove();

            // create link back to the clickable button
            var calling_link = '#source_override-' +this_source_id[1]+'-'+this_source_id[2];

            // reset the source_override link/img to clickable
            $(calling_link).attr("class", "source_override");
        });

    	////////////////////
    	/* On change of select of source override, click add_soruce*/
    	///////////////////

    	$( 'select[name="source_override[]"]' ).livequery( 'change', function()
    	{
    		$(this).siblings('.add_source')[0].click();
    	});

        ////////////////////
        /* Note Override */
        ///////////////////

        $(".note_override").livequery('click', function() {
            // display text editor or textarea
            $(this).parent().parent().find(".desc_override").toggle();

            // on add remove dropdown from dom, add hidden field with appropriate id, put small text after subject, make colour icon
        });

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
            $(this).parent().find("input[name*=ctags]").val(all_tags);


        });

        //////////////////
        /* A-Z List tag */
        //////////////////

        $("span[class*=aztag-]").livequery('click', function() {

            // change to other class, update hidden input field
            if ($(this).attr("class") == "aztag-off") {
                $(this).attr("class", "aztag-on");
                $(this).parent().find("input[name*=eres_display]").val("Y");
            } else {
                $(this).attr("class", "aztag-off");
                $(this).parent().find("input[name*=eres_display]").val("N");
            }

        });

        ///////////////
        /* URL Check */
        ///////////////

        $(".checkurl_img_wrapper").livequery('click', function() {
            // find our message div, clear out any message from before
            var feedback_div = $(this).parent().find(".url_feedback")
            feedback_div.empty();

            // find our our url, trim it, then find out if this is a restricted resource
            var url_location = $(this).parent().find("input.check_url");
            var checkurl = jQuery.trim(url_location.val());
            var restrictions = $(this).parent().find("select[name*=access_restrictions]").val();

            //alert("now checking: " + checkurl);
            // make sure it's not null
            if (checkurl.length > 0) {

            	var useProxy = '';

                // 0. see if it's restricted (2); no point testing
                if (restrictions == 2) {
                    useProxy = 'TRUE';
                }
                // 1. see if there's a proxy string
                var testproxy = "";
                testproxy = isProxy(checkurl);

                if (testproxy != null) {
                    // there is a proxy string, no point checking the url, just strip it off
                    var checkurl = stripProxy(checkurl);
                    var feedback = "Proxy string stripped.  URL not checked.";
                    feedback_div.append(feedback);
                    // insert the modified url into the text box
                    url_location.val(checkurl);
                    return;
                } else {
                    // make sure there's an http:// at the beginning
                    var checkurl = checkHTTP(checkurl);
                }

                // insert the modified url into the text box
                url_location.val(checkurl);

                // check the URL
                $(this).hide().load("record_bits.php", {type: 'check_url', checkurl: checkurl, useProxy: useProxy}).fadeIn(1600);

            } else {
                alert("You must enter a location first!");
            }

        });

        function isUrl(s) {
            var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
            return regexp.test(s);
        }

        function checkHTTP(s) {

            var pattern = new RegExp('^http[s]*:\/\/');
            var result = s.match(pattern);
            if (result == null) {
                result = "http://" + s;
            } else {
                result = s;
            }
            return result;

        }
        //http://ezproxy.ithaca.edu:2048/login?url=http://search.epnet.com/login.aspx?authtype=ip,uid&profile=ehost&defaultdb=mth

        function isProxy(s) {
		<?php
		if($proxyURL == '')
		{
			?>
			return null;
			<?php
		} ?>
            var proxyURL = RegExp.escape('<?php print $proxyURL; ?>');
            var safeURL = new RegExp(proxyURL);
            var result = s.match(safeURL, '');
            return result;
        }

        function stripProxy(s) {

            var proxyURL = RegExp.escape('<?php print $proxyURL; ?>');
            var safeURL = new RegExp(proxyURL);
            var result = s.replace(safeURL, '');
            return result;

        }



        RegExp.escape = function(str)
        {
            var specials = new RegExp("[.*+?|()\\[\\]{}\\\\]", "g"); // .*+?|()[]{}\
            return str.replace(specials, "\\$&");
        }

        ////////////////
        // Check Submit
        // When the form has been submitted, check required fields
        ////////////////

        $("#new_record").submit( function () {

            // Check that there is at least one subject
            var numsub = $(".selected_item_wrapper").length;

            if (numsub < 1) {
                alert("<?php print _("You must have at least one subject."); ?>");
                return false;
            };

            // check required fields
            // make sure the record isn't a dupe--check for title and location

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
            } else {
                // now check that the title is ok, not a dupe--if it's a new record
                var title_string = $("#record_title").val();

                //alert(title_string);
                return true;
            }

        });

        //////////////////
        // Recommend delete
        //////////////////
        $('.recommend_delete').livequery('click', function(event) {
            $(this).next().load("record_bits.php", {type: 'recommend_delete', our_id: '<?php print $ok_record_id; ?>'}).fadeIn(1600);

            return false;
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
            var delete_url = "record.php?record_id=" + this_record_id[2] + "&delete_record=true";
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
