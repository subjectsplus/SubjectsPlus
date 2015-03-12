<?php


//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');
include_once('../../../../control/includes/autoloader.php');

use SubjectsPlus\Control\Querier;

//print out custom style for oddrow class
print "<style type=\"text/css\">
.oddrow {
	background-color: #f6e3e7;
	}
</style>";

//initialize local variables
$row_count = 0;
$colour1 = "oddrow";
$colour2 = "evenrow";

//function to print out faq checkboxes according to sql results
function innerLoop($id, $results_array, $show_edit)
{
global $colour1;
global $colour2;
global $BaseURL;
$row_count = 0;

	// add a prefix of coll if it's a collection
	if ($show_edit == 0) {$prefix = "coll"; } else {$prefix = "";}

	//go through all results and print out checkbox
	foreach($results_array as $myrow2) {
	$row_colour = ($row_count % 2) ? $colour1 : $colour2;
		print "<div style=\"clear: both; padding: 3px 5px;\" class=\"$row_colour\">
		<input name=\"but\" type=\"checkbox\" value=\"$prefix$myrow2[0]\">&nbsp;&nbsp;" . stripslashes(htmlspecialchars_decode($myrow2[1]));
			if ($show_edit == 1) {
				print " <a target=\"blank\" href=\"{$BaseURL}control/faq/faq.php?faq_id=$myrow2[0]\">edit</a>";
			}

		print "</div>";
	$row_count++;
	}

	return $row_count;
}

// Connect to database
try {
	} catch (Exception $e) {
	echo $e;
}

//querier initialize
$db = new Querier();

//if browsing by subject
if (isset($_GET["browse"]) && $_GET["browse"] == "subject")
{
	print "<br /><h2>" . _("<strong>Tick</strong> the boxes of any FAQs you want to include in your pluslet and hit <strong>OK</strong>.") . "</h2>";

	//sql for all subjects
	$q = "SELECT * FROM faq f, faq_subject fs, subject s WHERE f.faq_id = fs.faq_id AND s.subject_id = fs.subject_id GROUP BY subject";
	$r = $db->query($q);

	//go through all subjects and get the related faqs
	foreach ($r as $myrow)
	{

		$sub_id = $myrow["subject_id"];
		$subject = $myrow["subject"];

		print "<br /><p><strong style=\"font-size: large;\">$subject</strong></p><br />";

		//sql for faqs
		$q2 = "SELECT f.faq_id, f.question FROM faq_subject fs, faq f WHERE  f.faq_id = fs.faq_id AND fs.subject_id = '$sub_id' ORDER BY f.question";
		$r2 = $db->query($q2);

		//go through all results to print out checkboxes
		$rc = innerLoop($sub_id, $r2, 1);

		if ($rc == 0) {
			print "<p>" . _("There are no results.  You'll need to try something different.") . "</p>";
		}

	}

} elseif (isset($_GET["browse"]) && $_GET["browse"] == "collection") { //if browsing by collection

	print "<br /><h2>" . _("<strong>Tick</strong> the boxes of any FAQs you want to include in your pluslet and hit <strong>OK</strong>.") . "</h2>";

	//sql for all collections
	$q = "SELECT fp.faqpage_id, fp.name FROM faq f, faq_faqpage ff, faqpage fp WHERE f.faq_id = ff.faq_id AND fp.faqpage_id = ff.faqpage_id GROUP BY fp.name";

	$r = $db->query($q);

	//go through all collections and get the related faqs
	foreach ($r as $myrow) {

		$coll_id = $myrow["0"];
		$collection = $myrow["1"];

		print "<br /><p><strong style=\"font-size: large;\">$collection</strong></p><br />";

		//sql for faqs
		$q2 = "SELECT f.faq_id, f.question FROM faq_faqpage ff, faq f WHERE  f.faq_id = ff.faq_id AND ff.faqpage_id = '$coll_id' ORDER BY f.question";
		$r2 = $db->query($q2);

		//go through all results to print out checkboxes
		$rc = innerLoop($coll_id, $r2, 1);

		if ($rc == 0) {
			print "<p>" . _("There are no results.  You'll need to try something different.") . "</p>";
		}

	}

} elseif (isset($_GET["browse"]) && $_GET["browse"] == "all") { //if browsing by all

	print "<br /><h2>" . _("<strong>Tick</strong> the boxes of any FAQs you want to include in your pluslet and hit <strong>OK</strong>.") . "</h2>";

	//sql for all FAQs
	$q = "SELECT faq_id, question FROM faq";

	$r = $db->query($q);

		//go through all results to print out checkboxes
		$rc = innerLoop(1, $r, 1);

		if ($rc == 0) {
			print "<p>" . _("There are no results.  You'll need to try something different.") . "</p>";
		}



} elseif(isset($_COOKIE["our_guide"]) && isset($_COOKIE["our_guide_id"])){ //get for current guide based on cookie

	print "<br /><p>" . _("<strong>Tick</strong> the boxes of any FAQs you want to include in your pluslet and hit <strong>OK</strong>.") . "</p>";

	print "<br /><strong style=\"font-size: large;\">" . $_COOKIE["our_guide"] . "</strong><br /><br />\n";

	//select faqs for current guide
	$q = "SELECT f.faq_id, f.question FROM faq_subject fs, faq f WHERE  f.faq_id = fs.faq_id AND fs.subject_id = '" . $_COOKIE["our_guide_id"] . "'";
	$r = $db->query($q);

	//go thtough all faqs and print out checkboxes
	$rc = innerLoop($_COOKIE["our_guide_id"], $r, 1);

	if ($rc == 0) {
		print "<p>" . _("You don't have any FAQs associated with this subject yet.  Maybe click Browse by Subject or Browse by Collection, above, to see what else is out there.") . "</p>";
	}
}

?>