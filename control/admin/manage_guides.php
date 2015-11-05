<?php

/**
 *   @file manage_guides.php
 *   @brief CRUD for subjects and librarian-subject associations
 *   @description Note that this subject creation has been superseded a bit in version 0.9--now
 *   all staff members (at least with permission to create guides) can create guides
 *
 *   @author adarby
 *   @date Sep 17, 2009
 *   @todo Not sure how this page fits into the new version
 */
use SubjectsPlus\Control\Querier;
    
$subsubcat = "";
$subcat = "admin";
$page_title = "Subject/Librarian Associations";

include("../includes/header.php");

// Connect to database
try { 	} catch (Exception $e) { echo $e; }

///////////////////
// Browse View
///////////////////


$q = "SELECT s.subject_id, subject, fname, lname, st.staff_id, type, shortform, s.active
  FROM subject s
  LEFT JOIN staff_subject ss ON s.subject_id = ss.subject_id
  LEFT JOIN staff st ON ss.staff_id = st.staff_id
  ORDER BY subject";

$querier = new Querier();
$subsArray = $querier->query($q);

	// set up striping
  $row_count = 0; $colour1 = "oddrow"; $colour2 = "evenrow";
  $staff_list = "";

$edit_text = _("Modify");
$view_text = _("View Live");
$linkcheck_text = _("Check Links");

  foreach ($subsArray as $value) {

if ($value[7] != "1") { $active = " <span style=\"font-size:smaller; color: #666;\">inactive</span>";} else { $active = ""; }

$row_colour = ($row_count % 2) ? $colour1 : $colour2;
$staff_list .= "<div class=\"$row_colour striper\" style=\"clear: both; float: left; min-width: 500px;\">
<input type=\"checkbox\" name=\"guide-$value[0]\" value=\"$value[0]\"><a class=\"showmedium-reloader\" href=\"../guides/metadata.php?subject_id=$value[0]&wintype=pop\"><i class=\"fa fa-pencil fa-lg\" title=\"$edit_text\" alt=\"$edit_text\"></i></a> 
&nbsp;&nbsp; <a target=\"_blank\" href=\"../../subjects/guide.php?subject=$value[6]\"><i class=\"fa fa-eye fa-lg\" title=\"$view_text\" alt=\"$view_text\"></i></a> 
&nbsp;&nbsp; <a class=\"showmedium\" href=\"../guides/link_checker.php?subject_id=$value[0]&wintype=pop\"><i class=\"fa fa-globe fa-lg\" title=\"$linkcheck_text\" alt=\"$linkcheck_text\"></i></a> 
&nbsp;&nbsp; <a href=\"../guides/guide.php?subject_id=$value[0]\">$value[1]</a> $active</div>
<div class=\"$row_colour striper\" style=\"float: left; min-width: 100px;\">$value[2] $value[3]</div>
<div class=\"$row_colour striper\" style=\"float: left; min-width: 75px;\">$value[5]</div>";
$row_count++;
  }


// get all the librarians to populate an array like this:
// (Works on the assumption that only librarians/subject specialists have access to SubjectsPlus)

$q2 = "SELECT staff_id, Concat(fname, ' ' , lname) as fullname
FROM staff
WHERE ptags like '%records%'
ORDER BY lname";

$querier2 = new Querier();
$staffArray = $querier2->query($q2);

// Two dropdowns, one for viewing, one for dealing with ticked items
$staff_drop_base = "<select name=\"filter_staff_id\" id=\"filter_by_staff\">\n
<option value=\"All\">" . _("All") . "</option>\n";

$staff_drop_ticks = "<select name=\"filter_staff_id\" id=\"filter_by_staff_ticked\">\n
<option value=\"\">" . _("Select") . "</option>\n";

$staff_drop_vals = "";

foreach($staffArray as $s) {
	$staff_drop_vals .= "<option value=\"$s[0]\">$s[fullname]</option>";
}

$staff_drop_close = "</select>\n";

$staff_dropdown = $staff_drop_base . $staff_drop_vals . $staff_drop_close;
$staff_dropdown_ticks = $staff_drop_ticks . $staff_drop_vals . $staff_drop_close;


// Get all the guide types into a dropdown

$type_drop_base = "<select name=\"filter_type_id\" id=\"filter_by_type\">\n
<option value=\"All\">" . _("All") . "</option>\n";
$type_drop_ticks = "<select name=\"filter_type_id\" id=\"filter_by_type_ticked\">\n";

$type_drop_vals = "
<option value=\"\" class=\"guide-status\">" . _("~~Guide Status~~") . "</option>\n
<option value=\"Public\">" . _("Public") . "</option>\n
<option value=\"Hidden\">" . _("Hidden") . "</option>\n
<option value=\"Suppressed\">" . _("Suppressed") . "</option>\n
<option value=\"\" class=\"guide-status\">" . _("~~Guide Types~~") . "</option>\n";

  foreach ($guide_types as $key=>$value) {
	  $type_drop_vals .= "<option value=\"$key\">$value</option>\n";
  }

$type_drop_close = "</select>\n";

$type_dropdown = $type_drop_base . $type_drop_vals . $type_drop_close;
$type_dropdown_ticks = $type_drop_ticks . $type_drop_vals . $type_drop_close;

$mg_box = "

<div class=\"tick-wrap\"  style=\"padding: 1em 10px;\">
	<span class=\"filter\" id=\"ticked_label\">" . _("Ticked Guides") . "</span>
	<span class=\"filter\">" . _("Show Guides By") . " $staff_dropdown</span>
	<span class=\"filter\">" . _("Show") . " $type_dropdown</span>
</div>


<div id=\"tickzone\" style=\"display: none;padding: 1em 10px;\"><span class=\"filter_ticks\" >" . _("Assign to ") . " $staff_dropdown_ticks</span><span class=\"filter_ticks\" > Change To $type_dropdown_ticks</span><span class=\"filter_ticks\" id=\"tick_forget\">Never Mind</span></div>
</div>
<div id=\"listing_space\" style=\" \">
$staff_list
</div>
</div>
";

print "
<div class=\"pure-g\">
  <div class=\"pure-u-2-3\">";

makePluslet(_("Manage Guides"), $mg_box, "no_overflow");    

print "</div></div>";

include("../includes/footer.php");

?>

<script type="text/javascript">

$(function (){

$('#filter_by_type').livequery('change', function() {
	var type_text = $('#filter_by_type :selected').text();
	$("#listing_space").load("admin_bits.php", {action: 'guide_type', filter_key: type_text});
});

$('#filter_by_staff').livequery('change', function() {
	var staff_id = $('#filter_by_staff :selected').val();
	$("#listing_space").load("admin_bits.php", {action: 'staffer', filter_key: staff_id});
});

// Dealing with multiple tick boxes checked

$('#listing_space input:checkbox').livequery('click', function() {
	// Change to active colour for Ticked Guides box
	$('#ticked_label').removeClass('filter').addClass('filter_ticks');
	// hide the regular filters
	$(".filter").hide();
	//expose the filter options
	$("#tickzone").show();
	// only need to do this once
	$("#listing_space input:checkbox").unbind("click");
});

$('#ticked_label').livequery('click', function() {
	$("#tickzone").show();
		// hide the regular filters
	$(".filter").hide();
	var allVals = [];
   $('#listing_space :checked').each(function() {
       allVals.push($(this).val());
    });
	  $('#t').val(allVals)

	//$("#listing_space").load("admin_bits.php", {action: 'ticks', filter_key: allVals});
});


$("#tick_forget").livequery('click', function() {
	$("#tickzone").hide();
	// Show the regular filters again
	$(".filter").show();
});

// Action! (these two could be combined) //

$('select[id*=_ticked]').livequery('change', function() {
	var our_type = $(this).attr("id").split("_");

	var our_id = $('#filter_by_' + our_type[2] + '_ticked :selected').val();
	var our_text = $('#filter_by_' + our_type[2] + '_ticked :selected').text();

	// Empty ID = placeholder text in selects like "select a librarian"
	if (our_id != "") {
		var allTicks = [];

		// grab all items that have been ticked into an array
		$('#listing_space :checked').each(function() {
			 allTicks.push($(this).val());
		 });

		// Make sure array isn't empty, then load our page
		if (allTicks.length > 0) {
			// route as appropriate based on our_type, callback makes normal controls reappear
				if (our_type[2] == "staff") {
					$("#listing_space").load("admin_bits.php", {action: 'staff_mod', filter_key: our_id, selected: allTicks}, function() {
							$("#tickzone").hide();
							$(".filter").show();
					});
				} else {
					$("#listing_space").load("admin_bits.php", {action: 'type_mod', filter_key: our_text, selected: allTicks}, function() {
							$("#tickzone").fadeOut();
							$(".filter").fadeIn();
					});
				}

		}

	}


});

/* $('#filter_by_staff_ticked').livequery('change', function() {
	var staff_id = $('#filter_by_staff_ticked :selected').val();
	if (staff_id != "") {
		var allTicks = [];
		$('#listing_space :checked').each(function() {
			 allTicks.push($(this).val());
		 });

		$("#listing_space").load("admin_bits.php", {action: 'staff_mod', filter_key: staff_id, selected: allTicks});
	}
});

$('#filter_by_type_ticked').livequery('change', function() {
	var type_id = $('#filter_by_type_ticked :selected').val();
	var type_text = $('#filter_by_type_ticked :selected').text();
	if (type_id != "") {
		var allTicks = [];
		$('#listing_space :checked').each(function() {
			 allTicks.push($(this).val());
		 });
		// make sure the allTicks isn't empty
		if (allTicks.length > 0) {
			$("#listing_space").load("admin_bits.php", {action: 'type_mod', filter_key: type_text, selected: allTicks});
		}
	}
});  */

});

</script>

