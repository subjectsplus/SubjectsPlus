<?php
/**
 *   @file index.php
 *   @brief handles RUD (Read, Update, Delete) for FAQ module.
 *
 *   @author adarby
 *   @date march 2011
 */
use SubjectsPlus\Control\DBConnector;
use SubjectsPlus\Control\Querier;
    
$subcat = "faq";
$page_title = "FAQ Admin";

include("../includes/header.php");

try {
  $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
  echo $e;
}

if (isset($_GET["limit"])) {
  if ($_GET["limit"] == "all") {
  $limit = "";
  } else {
  $limit = "LIMIT 0," . scrubData($_GET["limit"], "int");
  }

} else {
  $limit = "LIMIT 0,10";
}

$querierFAQ = new  Querier();
$qFAQ = "SELECT faq_id, question, answer, keywords
	FROM faq
	ORDER BY faq_id DESC
	$limit";

$faqArray = $querierFAQ->getResult($qFAQ);

$row_count1 = 0;
$row_count2 = 0;

$colour1 = "evenrow";
$colour2 = "oddrow";

$faq_list = "";

if ($faqArray) {
  foreach ($faqArray as $value) {
    $row_colour1 = ($row_count1 % 2) ? $colour1 : $colour2;

    $short_question = Truncate($value["question"], 200);
    $short_answer = stripslashes(htmlspecialchars_decode(TruncByWord($value["answer"], 15)));
    $last_revised_line = lastModded("faq", $value[0]);
// Answered FAQs
    $faq_list .= "
            <div class=\"striper faq-answered$row_colour1\">
                <div class=\"faq-answered-child\">
                <a href=\"faq.php?faq_id=$value[0]&amp;wintype=pop\" class=\"showmedium-reloader\"><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a>
                &nbsp; &nbsp;<a href=\"" . $FAQPath . "?faq_id=$value[0]\" target=\"_blank\"><img src=\"$IconPath/eye.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a>
                </div>
                <div class=\"faq-short-question-wrap\">
                 $short_question <span class=\"faq-short-question\">($last_revised_line)</span>
                </div>
            </div>";

    $row_count1++;
  }
} else {

  $faq_list = "<p>" . _("No FAQs yet.  Why not dream one up?") . "</p>";
}


print "<br />
<div class=\"faq-visible\">
    <div class=\"box no_overflow\" id=\"answered\">
    <p><strong>$row_count1 " . _("FAQs visible") . "</strong> ";
if (!isset($_GET["limit"]) || $_GET["limit"] != "all") {
  print "(<a href=\"index.php?limit=all\">" . _("See All") . "</a>)";
}
print "</p><br />
    $faq_list
    </div>

</div>

<div class=\"faq-create\">
    <h2 class=\"bw_head\">" . _("Create FAQ") . "</h2>
    <div class=\"box\">
    <p><a href=\"faq.php?faq_id=&amp;wintype=pop\" class=\"showmedium-reloader\">" . _("CREATE FAQ") . "</a></p>
    </div>
    <h2 class=\"bw_head\">" . _("About FAQs") . "</h2>
    <div class=\"box\">
    <p><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /> = " . _("Edit FAQ") . "</p>
    <p><img src=\"$IconPath/eye.png\" alt=\"edit\" width=\"16\" height=\"16\" /> = " . _("View FAQ on Public Site") . "</p>
    </div>
</div>
";


include("../includes/footer.php");
?>


<script type="text/javascript">
  $(document).ready(function(){
    $(".toggle_unanswered").click(function() {
      $("#unanswered .hideme").toggle();
      return false;
    });

    $(".toggle_answered").click(function() {
      $("#answered .hideme").toggle();
      return false;
    });

  });
</script>

