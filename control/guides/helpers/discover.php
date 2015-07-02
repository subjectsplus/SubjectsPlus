<?php

/**
 *   @file
 *   @brief Search/Browse results returned via jquery, find_results.php
 *
 *   @author adarby
 *   @date
 *   @todo
 */

$subcat = "guides";
$page_title = "Find Stuff";

$no_header = "yes";

include("../../includes/header.php");

$all_subs = "";
$our_subject_id = "";

if (isset($_GET["subject_id"])) {
  $our_subject_id = scrubData($_GET["subject_id"], "int");
}

// get list of all subjects with pluslets, to use later
$q3 = "SELECT s.shortform, s.subject FROM subject s
INNER JOIN tab t
ON s.subject_id = t.subject_id
INNER JOIN section sec
ON t.tab_id = sec.tab_id
INNER JOIN pluslet_section ps
ON sec.section_id = ps.section_id
GROUP BY s.subject";

//print $q2;

$r3 = $db->query($q3);

foreach($r3 as $myrow) {
	$sub_title = Truncate($myrow[1], 50, '');
	$all_subs .= "<option value=\"$myrow[0]\">$sub_title</option>";
}


print "
<div id=\"maincontent\">
<br /><br />
<form action=\"discover.php\" method=\"post\" id=\"target\">

<div style=\"float: left; width: 60%;\">
    <div class=\"box\">
    <h2>Browse</h2>

	<select name=\"all_subs\" id=\"all_subs\">
	<option value=\"\" style=\"font-size: 9pt;\">" . _("- Browse Boxes -") . "</option>

	$all_subs
</select>
</div>

</div>
<div style=\"float: left;  width: 35%; margin-left: 3%;\">
	<div class=\"box\">
    <h2>Search</h2>

	 <input type=\"text\" id=\"search_terms\" name=\"search\" />
	 <input type=\"submit\" value=\"" . _("Go!") . "\" name=\"searcher\" id=\"searcho\" />
	 </div>
</div>
</form>
<div class=\"box no_overflow\" class=\"clear-both\">
<div id=\"results\"></div>
</div>
";



?>

<script type="text/javascript" language="javascript">
$(document).ready(function(){

	var thisguide = '<?php print $our_subject_id; ?>';
	$("#all_subs").change(function() {
		var desired_guide = $("select option:selected").val();
		$("#results").fadeIn(3000).load("find_results.php", {shortform: desired_guide, guide_id: thisguide});
	});

	$('form').submit(function() {
		var terms = $("#search_terms").val();
		$("#results").fadeIn(3000).load("find_results.php", {search_terms: terms, guide_id: thisguide });
	  	return false;
	});

// this might be obsolete, in place of copy and clone
	$("button[name*=add-]").livequery('click', function(event) {
		var item_id = $(this).attr("name").split("-");

		// make these vars available to the parent file, guide.php
		parent.addItem = item_id[1];
		parent.addItemType = item_id[2];
		parent.addCloneType = item_id[3];
		if (item_id[3] == 'clone') {
			alert("Holy crap it's a clone");
		} else {
			alert("Holy crap it's a copy");
		}
		parent.jQuery.colorbox.close();
		return false;
	});

// Copy and clone content.  This adds some "onclose" commands to colourbox, which gets picked up in the setupAllColorboxes()
// function of guide.js
	$("button[name*=copy-]").livequery('click', function(event) {
		var item_id = $(this).attr("name").split("-");
		// make these vars available to the parent file, guide.php
		parent.addItem = item_id[1];
		parent.addItemType = item_id[2];
		parent.jQuery.colorbox.close();
		return false;
	});

		$("button[name*=clone-]").livequery('click', function(event) {
		var item_id = $(this).attr("name").split("-");
		// make these vars available to the parent file, guide.php
		parent.addItem = item_id[1];
		parent.addItemType = item_id[2];
		parent.jQuery.colorbox.close();
		return false;
	});

});
</script>
