<?php
namespace SubjectsPlus\Control;

/**
 *   @file sp_BuildNav
 *   @brief
 *
 *   @author adarby
 *   @date
 *   @todo don't show guides autosearch if user lacks credentials
 */
class BuildNav {

  public function displayNav() {
    global $AssetPath;
    global $CpanelPath;
    global $subcat;
    global $is_um; 

    $headshot = getHeadshot($_SESSION["email"], "smaller", "");
    $headshot_lg = getHeadshot($_SESSION["email"], "", "");
    $staff_page = $CpanelPath . "admin/profile.php";
    
    
    if ($_SESSION["fname"]) {
      $name = $_SESSION["fname"];
      
    } else {
      $name = $_SESSION["email"];
    }
    
    print "
      <ul id=\"topnav\">
      <li id=\"logospot\"><a href=\"$CpanelPath" . "index.php\"><img src=\"$AssetPath" . "images/admin/logo_v3_full.png\" /></a>
      <div>$headshot_lg
      <p> Hi $name </p>
      <br style=\"clear: both\" />
      <ul>
      <li><a href=\"$staff_page\">" . _("Edit Your Profile") . "</a></li>
      <li><a href=\"$CpanelPath" . "logout.php\">" . _("Log Out") . "</a></li>
      </ul>
      </div>
      </li>
      ";
    ///////////////
    // RECORDS
    // records and guides use same authentication credential
    ///////////////
    if (isset($_SESSION["records"]) && $_SESSION["records"] == 1) {

      print "
	     <li";
      if ($subcat == "records") {
        print " class=\"selected\"";
      }
      print "><a href=\"$CpanelPath" . "records/\">" . _("Records") . "</a>
        <div>
         <ul>
            <li><a href=\"$CpanelPath" . "records/record.php\">" . _("New Record") . "</a></li>
            <li><a href=\"$CpanelPath" . "records/index.php\">" . _("Browse Items") . "</a></li>
         </ul>
		  </div></li>";

      ///////////////
      // GUIDES
      //////////////
      print "<li";
      if ($subcat == "guides") {
        print " class=\"selected\"";
      }

      print "><a href=\"$CpanelPath" . "guides/\">" . _("Guides") . "</a>
      <div>
       <ul>
          <li><a href=\"$CpanelPath" . "guides/metadata.php\">" . _("New Guide") . "</a></li>
          <li><a href=\"$CpanelPath" . "guides/\">" . _("Browse Guides") . "</a></li>
          <li><a href=\"$CpanelPath" . "guides/manage.php\">" . _("Manage Files") . "</a></li>
          <!--<li><a href=\"$CpanelPath" . "guides/delish_url.php\">" . _("Delicious Builder") . "</a></li>-->
          <li><a href=\"$CpanelPath" . "guides/link_checker.php\">" . _("Link Checker") . "</a></li>
       </ul>
      </div></li>";
    }

    //////////
    // FAQs
    //////////
    if (isset($_SESSION["faq"]) && $_SESSION["faq"] == 1) {
      print "
        <li";
      if ($subcat == "faq") {
        print " class=\"selected\"";
      }
      print"><a href=\"$CpanelPath" . "faq/\">" . _("FAQs") . "</a>
      <div>
        <ul>
          <li><a href=\"$CpanelPath" . "faq/faq.php\">" . _("New FAQ") . "</a></li>
          <li><a href=\"$CpanelPath" . "faq/browse_faq.php?type=subject\">" . _("Browse by Subject") . "</a></li>
          <li><a href=\"$CpanelPath" . "faq/browse_faq.php?type=holding\">" . _("Browse by Collection") . "</a></li>
        </ul>
      </div>
      </li>";
    }

    // TalkBack tab
    if (isset($_SESSION["talkback"]) && $_SESSION["talkback"] == 1) {
      print "
	<li";
      if ($subcat == "talkback") {
        print " class=\"selected\"";
      }

      print "><a href=\"$CpanelPath" . "talkback/\">" . _("TalkBack") . "</a></li>";
    }

    // Videos tab
    if (isset($_SESSION["videos"]) && $_SESSION["videos"] == 1) {
      print "
            <li><a href=\"$CpanelPath" . "videos/\"";
      if ($subcat == "videos") {
        print " class=\"selected\"";
      }
      print ">" . _("Videos") . "</a>
      <div>
        <ul>
          <li><a href=\"$CpanelPath" . "videos/\">" . _("List Current") . "</a></li>
          <li><a href=\"$CpanelPath" . "videos/ingest.php\">" . _("Find/Ingest") . "</a></li>
          <li><a href=\"$CpanelPath" . "videos/video.php\">" . _("Manually Enter") . "</a></li>
        </ul>
       </div></li>";
    }

    // Stats tab
if ($is_um == TRUE) {
    if (isset($_SESSION["records"]) && $_SESSION["records"] == 1) {
      print "
            <li><a href=\"$CpanelPath" . "stats/\"";
      if ($subcat == "stats") {
        print " class=\"selected\"";
      }
      print ">" . _("Stats") . "</a>
      <div>
        <ul>
          <li><a href=\"$CpanelPath" . "stats/\">" . _("Overview") . "</a></li>
          <li><a href=\"$CpanelPath" . "stats/ref_stats.php\">" . _("Add Transaction") . "</a></li>
        </ul>
       </div></li>";
    }
}

    // Admin tab
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) {
      print "
	<li";
      if ($subcat == "admin") {
        print " class=\"selected\"";
      }
      print "><a href=\"$CpanelPath" . "admin/\">" . _("Admin") . "</a>
      <div>
    		<ul>
    			<li><a href=\"$CpanelPath" . "admin/user.php\">" . _("Add New User") . "</a></li>
          <li><a href=\"$CpanelPath" . "admin/user.php?browse\">" . _("Manage Users") . "</a></li>
    			<li><a href=\"$CpanelPath" . "admin/manage_guides.php\">" . _("Manage Guides") . "</a></li>
          <li><a href=\"$CpanelPath" . "admin/guide_collections.php\">" . _("Guide Collections") . "</a></li>
    			<li><a href=\"$CpanelPath" . "admin/departments.php\">" . _("Departments") . "</a></li>
    			<li><a href=\"$CpanelPath" . "admin/sources.php\">" . _("Sources") . "</a></li>
          <li><a href=\"$CpanelPath" . "admin/faq_collections.php\">" . _("FAQ Collections") . "</a></li>
    			<li><a href=\"$CpanelPath" . "edit-config.php\">" . _("Config Site") . "</a></li>
    		</ul>
      </div>
	   </li>";
    }

    // determine our default search/search box text
    switch ($subcat) {
      case "records";
        $input_text = _("Search records");
        $target_url = "record.php?record_id=";
        break;
      case "guides";
        $input_text = _("Search guides");
        $target_url = "../guides/guide.php?subject_id=";
        break;
      case "faq";
        $input_text = _("Search faqs");
        $target_url = "faq.php?faq_id=";
        break;
      case "home":
	$input_text = _("Search all");
        $target_url = "../control/guides/guide.php?subject_id=";
	break;
      case "talkback";
        $input_text = _("Search talkback");
        $target_url = "talkback.php?talkback_id=";
        break;
      case "admin";
        $input_text = _("Search users");
        $target_url = "user.php?staff_id=";
        break;
      default:
        $input_text = _("Search all");
        $target_url = "guide.php?subject_id=";
        break;
    }

    print "
    <li class=\"nohover\">";
    $input_box = new CompleteMe("sp_search", $CpanelPath . "search.php", $target_url, $input_text, $subcat, "", "private");
    $input_box->displayBox();
    print "
    </li>";


  }

}
