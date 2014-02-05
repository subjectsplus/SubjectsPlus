<?php

/**
 *   @file browse_faq.php
 *   @brief Browseable view of FAQs . . .
 *
 *   @author adarby
 *   @date Sep 17, 2009
 *   @todo Remove this JavaScript in favour of using JQModal
 */
$subsubcat = "";
$subcat = "faq";
$page_title = "FAQ Admin";

$postvar_type = '';

if(isset($_GET['type']))
{
	$postvar_type = $_GET['type'];
}

include("../includes/header.php");

print "<br /><br /><div style=\"float: left;  width: 70%;\">";

if ($postvar_type == "holding") {

  print "<div class=\"box no_overflow\">" . "<h2>" . _("Browse FAQs by Collection") . "</h2>
<p>
. _("If you wish to edit a FAQ, click the link.") . "</p>";

  $q = "SELECT fp.faqpage_id, fp.name FROM faq f, faq_faqpage ff, faqpage fp WHERE f.faq_id = ff.faq_id AND fp.faqpage_id = ff.faqpage_id GROUP BY fp.name";

//print $q;

  $r = MYSQL_QUERY($q);

  $colour1 = "evenrow";
  $colour2 = "oddrow";
  $row_count = 1;

  while ($myrow = mysql_fetch_array($r)) {
    $fp_id = $myrow["0"];
    $name = $myrow["1"];

    $row_colour = ($row_count % 2) ? $colour1 : $colour2;

    print "<h3>$name</h3>";

    $q2 = "SELECT * FROM faq_faqpage ff, faq f WHERE  f.faq_id = ff.faq_id AND ff.faqpage_id = '$fp_id'";
    //print $q2;
    $r2 = MYSQL_QUERY($q2);

    print "<ul>";
    while ($myrow2 = mysql_fetch_array($r2)) {
      print "<li><a class=\"showmedium\" href=\"faq.php?faq_id=" . $myrow2["faq_id"] . "&wintype=pop\">" . stripslashes(htmlspecialchars_decode($myrow2["question"])) . "</a></li>";
    }
    print "</ul>";

    $row_count++;
  }
} else {

    print "<div class=\"box no_overflow\">". "<h2>" . _("Browse FAQs by Subject Area") . "</h2>

<p>" . _("If you wish to edit a FAQ, click the link.") . "</p>
    ";

  $q = "SELECT faq_id, question, answer, keywords, last_revised, last_revised_by
FROM faq
ORDER BY faq_id DESC";

  $q = "SELECT * FROM faq f, faq_subject fs, subject s WHERE f.faq_id = fs.faq_id AND s.subject_id = fs.subject_id GROUP BY subject";

  $r = MYSQL_QUERY($q);

  $colour1 = "evenrow";
  $colour2 = "oddrow";
  $row_count = 1;

  while ($myrow = mysql_fetch_array($r)) {
    $sub_id = $myrow["subject_id"];
    $subject = $myrow["subject"];

    $row_colour = ($row_count % 2) ? $colour1 : $colour2;

    print "<h3>$subject</h3>";

    $q2 = "SELECT * FROM faq_subject fs, faq f WHERE  f.faq_id = fs.faq_id AND fs.subject_id = '$sub_id'";
    $r2 = MYSQL_QUERY($q2);

    print "<ul>";
    while ($myrow2 = mysql_fetch_array($r2)) {
      print "<li><a class=\"showmedium\" href=\"faq.php?faq_id=" . $myrow2["faq_id"] . "&wintype=pop\">" . stripslashes(htmlspecialchars_decode($myrow2["question"])) . "</a></li>";
    }
    print "</ul>";

    $row_count++;
  }
}
print "</div>
    </div>";
include("../includes/footer.php");
?>