<?php
/**
 *   @file index.php
 *   @brief handles RUD (Read, Update, Delete) for talkback module.
 *   Note that C (create) is handled only from a talkback submission from
 *   public website
 *
 *   @author adarby
 *   @date march 2011
 */
$subcat = "talkback";
$page_title = "Talk Back Admin";

include("../includes/header.php");

if (isset($_GET["limit"]) && $_GET["limit"] == "all") {
  $limit = "";
} elseif (isset($_GET["limit"])) {
  $limit = "LIMIT 0," . scrubData($_GET["limit"], "int");
} else {
  $limit = "LIMIT 0,10";
}

$row_count1 = 0;
$row_count2 = 0;
$colour1 = "evenrow";
$colour2 = "oddrow";

$tb_yes_intro = "";
$tb_no_intro = "";
$tb_yes_answer = "";
$tb_no_answer = "";

////////////////
// TalkBacks by Tag/Collection
///////////////

if (isset($_GET["tbtag"])) {
  $querierTbtag = new sp_Querier();
  $qTbtag = "SELECT talkback_id, question, q_from, date_submitted, DATE_FORMAT(date_submitted, '%b %D %Y') as date_formatted, answer, a_from, display, last_revised_by, tbtags
    FROM talkback
    WHERE tbtags like '%" . $_GET["tbtag"] . "%'
    ORDER BY date_submitted DESC";

  $tbArrayTag = $querierTbtag->getResult($qTbtag);

  if ($tbArrayTag) {
    $tag_block = genTalkBacks($tbArrayTag, 0);
  } else {
    $tag_block = _("No items match this criteria");
  }
  
} else {
  $tag_block = "";
}

////////////////
// Check for unanswered TalkBacks
///////////////

$querierTBNO = new sp_Querier();
$qTBNO = "SELECT talkback_id, question, q_from, date_submitted, DATE_FORMAT(date_submitted, '%b %D %Y') as date_formatted, answer, a_from, display, last_revised_by, tbtags
    FROM talkback
    WHERE answer = ''
    ORDER BY date_submitted DESC";

$tbArrayNo = $querierTBNO->getResult($qTBNO);

if ($tbArrayNo) {

  /*
    foreach ($tbArrayNo as $value) {
    $row_colour = ($row_count1 % 2) ? $colour1 : $colour2;


    if ($value[2]) {
    $q_from = $value[2];
    } else {
    $q_from = _("Anonymous");
    }

    $short_question = Truncate($value["question"], 200);
    $short_answer = stripslashes(htmlspecialchars_decode(TruncByWord($value["answer"], 15)));

    $tb_no_answer .= "
    <div style=\"clear: both; float: left;  padding: 3px 5px; width: 98%;\" class=\"striper $row_colour\">
    <div style=\"float: left; width: 32px;\"><a class=\"showcustom\" style=\"color: #333;\" href=\"talkback.php?talkback_id=$value[0]&amp;wintype=pop\"><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a></div>
    <div style=\"float: left; width: 85%;\">
    $short_question <span style=\"color: #666; font-size: 10px;\">($q_from, $value[date_formatted])</span>
    </div>
    </div>";

    $row_count1++;
    }


    } */
  $tb_no_answer = genTalkBacks($tbArrayNo, 0);
} else {
  $tb_no_intro = "<p>" . _("Hooray, no unanswered TalkBacks.") . "</p>";
}

////////////////
// Check for answered TalkBacks
///////////////

$querierTBYES = new sp_Querier();
$qTBYES = "SELECT talkback_id, question, q_from, date_submitted, DATE_FORMAT(date_submitted, '%b %D %Y') as date_formatted, answer, a_from, display, last_revised_by, tbtags
    FROM talkback
    WHERE answer != ''
    ORDER BY date_submitted DESC
    $limit";

$tbArrayYes = $querierTBYES->getResult($qTBYES);

if ($tbArrayYes) {


  /*
    foreach ($tbArrayYes as $value) {
    $row_colour = ($row_count2 % 2) ? $colour1 : $colour2;

    if ($value[2]) {
    $q_from = $value[2];
    } else {
    $q_from = _("Anonymous");
    }

    $short_question = Truncate($value["question"], 200);
    $short_answer = stripslashes(htmlspecialchars_decode(TruncByWord($value["answer"], 15)));

    // get last mod date
    $last_mod_tb = lastModded("talkback", $value["talkback_id"], 0, 1);

    if ($last_mod_tb) {
    $mod_line = _("--") . $last_mod_tb;
    } else {
    $mod_line = "";
    }
    $tb_yes_answer .= "
    <div style=\"clear: both; float: left;  padding: 3px 5px; width: 98%;\" class=\"striper $row_colour\">
    <div style=\"float: left; width: 32px; max-width: 5%;\"><a class=\"showcustom\" style=\"color: #333;\" href=\"talkback.php?talkback_id=$value[0]&amp;wintype=pop\"><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a></div>
    <div style=\"float: left; width: 45%;\">
    <strong>Q:</strong> $short_question <span style=\"color: #666; font-size: 10px;\">($q_from, $value[date_formatted])</span>
    </div>
    <div style=\"float: left; width: 45%; margin-left: 4%;\">
    <strong>A:</strong> $short_answer <span style=\"color: #666; font-size: 10px;\">$mod_line</span>
    </div>
    </div>";



    $row_count2++;
    }

   */

  $tb_yes_answer = genTalkBacks($tbArrayYes, 1);
} else {
  $tb_no_intro = "<p>" . _("Hooray, no unanswered TalkBacks.") . "</p>";
  $tb_yes_intro = "<p>" . _("Hooray, no unanswered TalkBacks.") . "</p>";
}

/////////////////
// Show Results
////////////////

print "<br />
<div style=\"float: left;  width: 70%;\"><h2>" . _("Unanswered TalkBacks") . "</h2>
<div class=\"box no_overflow\" id=\"unanswered\">

$tb_no_intro
$tb_no_answer

</div>
<h2>" . _("Answered TalkBacks") . "</h2>
<div class=\"box no_overflow\" id=\"answered\">
    <p><strong>" . _("Most Recent TalkBacks") . "</strong> ";
//if (!$_GET["limit"] == "all") {
if (isset($_GET["limit"]) && $_GET["limit"] = "all") {
  print "(<a href=\"index.php?limit=all\">" . _("See All") . "</a>)";
} else {
  print "(<a href=\"index.php?limit=10\">" . _("See 10 Most Recent") . "</a>)";
}
print "</p><br /><br />
$tb_yes_intro
$tb_yes_answer
</div>

</div>

<div style=\"float: right; width: 28%;margin-left: 10px;\"><h2 class=\"bw_head\">" . _("About TalkBack") . "</h2>
<div class=\"box\">
<p>" . _("TalkBack questions come via the web form on the public TalkBack page. An email should be sent to the admin when a new one arrives, and they may be answered here.") . "</p>
<br />
    <ul>
    <li><a target=\"_blank\" href=\"$TalkBackPath\">" . _("TalkBack Public Page") . "</a></li>
</ul>
</div>

<h2 class=\"bw_head\">" . _("Categories") . "</h2>
<div class=\"box no_overflow\">
<p>" . _("Click on a category to see items flagged that way.") . "</p>
<br />";

// loop through the tbtags as defined in teh config.php file

foreach ($all_tbtags as $value) {
  if (isset($_GET["tbtag"]) && $value == $_GET["tbtag"]) {
    $tag_class = "ctag-on";
  } else {
    $tag_class = "ctag-off";
  }
  print " <span class=\"$tag_class\"><a href=\"index.php?tbtag=$value\">$value</a></span>";
}
print "
<br /><br />
  $tag_block
<br /><br />
</div>
</div>
";


include("../includes/footer.php");

function genTalkBacks($tbArray, $show_response = 1) {
  global $IconPath;

  $row_count1 = 0;
  $row_count2 = 0;
  $colour1 = "evenrow";
  $colour2 = "oddrow";
  $tb_answer = "";

  foreach ($tbArray as $value) {
    $row_colour = ($row_count2 % 2) ? $colour1 : $colour2;

    if ($value[2]) {
      $q_from = $value[2];
    } else {
      $q_from = _("Anonymous");
    }

    if (isset($show_response) && $show_response == 0) {
      $first_div_width = "90%";
      $last_mod_tb = "";
    } else {
      $first_div_width = "45%";
      $short_answer = stripslashes(htmlspecialchars_decode(TruncByWord($value["answer"], 15)));
      $last_mod_tb = lastModded("talkback", $value["talkback_id"], 0, 1);
    }

    $short_question = Truncate($value["question"], 200);


    if ($last_mod_tb) {
      $mod_line = _("--") . $last_mod_tb;
    } else {
      $mod_line = "";
    }
    $tb_answer .= "
            <div style=\"clear: both; float: left;  padding: 3px 5px; width: 98%;\" class=\"striper $row_colour\">
                <div style=\"float: left; width: 32px; max-width: 5%;\"><a class=\"showcustom\" style=\"color: #333;\" href=\"talkback.php?talkback_id=$value[0]&amp;wintype=pop\"><img src=\"$IconPath/pencil.png\" alt=\"edit\" width=\"16\" height=\"16\" /></a></div>
                <div style=\"float: left; width: $first_div_width;\">
                 <strong>Q:</strong> $short_question <span style=\"color: #666; font-size: 10px;\">($q_from, $value[date_formatted])</span>
                </div>";

    if (isset($show_response) && $show_response == 1) {
      $tb_answer .= "<div style=\"float: left; width: 45%; margin-left: 4%;\">
                 <strong>A:</strong> $short_answer <span style=\"color: #666; font-size: 10px;\">$mod_line</span>
                </div>
            ";
    }
    $tb_answer .= "</div>";


    $row_count2++;
  }
  return $tb_answer;
}
?>

<script type="text/javascript">
  $(document).ready(function(){

    /////////////////
    // Load custom modal window
    ////////////////

    $("a[class*=showcustom]").colorbox({
      iframe: true,
      innerWidth:900,
      innerHeight:600,

      onClosed:function() {
        location.reload();
      }
    });

  });
</script>