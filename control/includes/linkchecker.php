<?php

/**
 *   @file linkchecker.php
 *   @brief check links in a given guide
 *
 *   @author rgilmour, adarby
 *   @date mar 2011
 *   @todo
 */

use SubjectsPlus\Control\LinkChecker;

$subsubcat = "";
$subcat = "";
$page_title = "Link Checker";

$no_header = "yes";

include("../includes/header.php");

$shortie = scrubData($_REQUEST["shortform"]);

// Connect to database


if (!isset($_POST["runcheck"])) {
    $linkReport = "<p>" . _("The link report can take a few minutes to run, depending on how many links you have on the page.  So be patient.") . "</p><br />
        <form action=\"linkchecker.php\" method=\"post\">
        <input type=\"hidden\" name=\"shortform\" value=\"$shortie\" />
        <input type=\"submit\" name=\"runcheck\" value=\"" . _("Run Linkchecker Now") . "\" />
        </form>
";
} else {

    $linkReport = "<br /><h3>" . _("Results") . "</h3>";
// 1. Get public url of guide to be tested

    $full_url = $PublicPath . "guide.php?subject=" . $shortie;

// Load page into $page variable via CURL
    //  echo $sub_url . "\n";
    $c = curl_init($full_url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    $page = curl_exec($c);
    $page = explode("<body", $page);
    $page = $page[1];
    $info = curl_getinfo($c);
    curl_close($c);

    $base_url = $PublicPath . "";


    try {
        $lc = new LinkChecker("linkcheck_blacklist.txt", $base_url);
    } catch (Exception $e) {
        echo $e;
    }

    try {
        $linkReport .= $lc->checkLinks($page);
    } catch (Exception $e) {
        echo "Couldn't check links: $e";
    }

    if ($linkReport == "") {
        $linkReport .= _("Wow, it appears that all your links are okay.  Good work!");
    } else {
        // Give mail option
        $mailReport = "<p>" ._("Would you like to mail this report to someone?") . "</p><br />
            <input type=\"submit\" name=\"send_report2owner\" value=\"" . _("Send report to: ")
        . $_SESSION["email"] . "\"> &nbsp; <input type=\"submit\" name=\"send_report2all\" value=\"" . _("Send report to All Guide Owners")
        . "\"><br />
        <div id=\"email_results\"></div>
        ";
    }
}



print "<div id=\"maincontent\">
<div class=\"box no_overflow\">
    <h2 class=\"bw_head\">" . _("Link Report for ") . $shortie . "</h2>


$mailReport
$linkReport
    </div>
</div>";

include("../includes/footer.php");
?>
<script type="text/javascript">

    $(function (){

        $("input[name*=send_report2]").livequery('click', function(event) {

            var shortform = "<?php print $shortie; ?>";
            var our_contents = $(this).attr("name");
            var our_linkresults = $("#link_result_set").html();

            $("#email_results").load("../guides/helpers/guide_bits.php",
            {type: 'email_link_report', sendto: our_contents, linkresults: our_linkresults, shortform: shortform}).fadeIn(1600);
            return false;
        });


    });


</script>



?>