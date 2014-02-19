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
      
    $headshot = getHeadshot($_SESSION["email"], "smaller");
      
    print "
	<div class=\"float-left\" id=\"logo\"><a href=\"$CpanelPath" . "index.php\"><img src=\"$AssetPath" . "images/admin/logo_small.png\"  border=\"0\" class=\"logo\" alt=\"SubjectsPlus\" /></a></div>
   <div id=\"navcats\">
      <ul class=\"topnav\">";

    // Records tab, Guides Tab
    if (isset($_SESSION["records"]) && $_SESSION["records"] == 1) {

      // Actually, someone with the NOFUN permission can't view records
      if (!isset($_SESSION["NOFUN"])) {
        print "
	<li class=\"zoom\"><a href=\"$CpanelPath" . "records/\"";
        if ($subcat == "records") {
          print " class=\"selected\"";
        }
        print ">" . _("Records") . "</a>
         <ul class=\"subnav\">
            <li><a href=\"$CpanelPath" . "records/record.php\">" . _("New Record") . "</a></li>
            <li><a href=\"$CpanelPath" . "records/index.php\">" . _("Browse Items") . "</a></li>
         </ul>
		</li>";
      }

      ///////////////
      // GUIDES
      //////////////
      print "<li class=\"zoom\"><a href=\"$CpanelPath" . "guides/\"";
      if ($subcat == "guides") {
        print " class=\"selected\"";
      }
      print ">" . _("Guides") . "</a>
   <ul class=\"subnav\">
      <li><a href=\"$CpanelPath" . "guides/metadata.php\">" . _("New Guide") . "</a></li>
      <li><a href=\"$CpanelPath" . "guides/\">" . _("Browse Guides") . "</a></li>
      <li><a href=\"$CpanelPath" . "guides/manage.php\">" . _("Manage Files") . "</a></li>
      <li><a href=\"$CpanelPath" . "guides/delish_url.php\">" . _("Delicious Builder") . "</a></li>
      <li><a href=\"$CpanelPath" . "guides/link_checker.php\">" . _("Link Checker") . "</a></li>
   </ul>
</li>";
    }

    // FAQ tab
    if (isset($_SESSION["faq"]) && $_SESSION["faq"] == 1) {
      print "
            <li class=\"zoom\"><a href=\"$CpanelPath" . "faq/\"";
      if ($subcat == "faq") {
        print " class=\"selected\"";
      }
      print ">FAQs</a>
                    <ul class=\"subnav\">
                            <li><a href=\"$CpanelPath" . "faq/faq.php\">" . _("New FAQ") . "</a></li>
                            <li><a href=\"$CpanelPath" . "faq/browse_faq.php?type=subject\">" . _("Browse by Subject") . "</a></li>
                            <li><a href=\"$CpanelPath" . "faq/browse_faq.php?type=holding\">" . _("Browse by Collection") . "</a></li>
                    </ul>

            </li>";
    }

    // TalkBack tab
    if (isset($_SESSION["talkback"]) && $_SESSION["talkback"] == 1) {
      print "
	<li class=\"zoom\"><a href=\"$CpanelPath" . "talkback/\"";
      if ($subcat == "talkback") {
        print " class=\"selected\"";
      }
      print ">TalkBack</a></li>";
    }

    // Videos tab
    if (isset($_SESSION["videos"]) && $_SESSION["videos"] == 1) {
      print "
            <li class=\"zoom\"><a href=\"$CpanelPath" . "videos/\"";
      if ($subcat == "videos") {
        print " class=\"selected\"";
      }
      print ">Videos</a>
                    <ul class=\"subnav\">
                      <li><a href=\"$CpanelPath" . "videos/\">" . _("List Current") . "</a></li>
                      <li><a href=\"$CpanelPath" . "videos/ingest.php\">" . _("Find/Ingest") . "</a></li>
                      <li><a href=\"$CpanelPath" . "videos/video.php\">" . _("Manually Enter") . "</a></li>
                    </ul>
            </li>";
    }
    // Admin tab
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) {
      print "
	<li class=\"zoom\"><a href=\"$CpanelPath" . "admin/\"";
      if ($subcat == "admin") {
        print " class=\"selected\"";
      }
      print ">" . _("Admin") . "</a>
		<ul class=\"subnav\">
			<li><a href=\"$CpanelPath" . "admin/user.php\">" . _("Add New User") . "</a></li>
                    <li><a href=\"$CpanelPath" . "admin/user.php?browse\">" . _("Manage Users") . "</a></li>
			<li><a href=\"$CpanelPath" . "admin/manage_guides.php\">" . _("Manage Guides") . "</a></li>
			<li><a href=\"$CpanelPath" . "admin/departments.php\">" . _("Departments") . "</a></li>
			<li><a href=\"$CpanelPath" . "admin/sources.php\">" . _("Sources") . "</a></li>
                        <li><a href=\"$CpanelPath" . "admin/faq_collections.php\">" . _("FAQ Collections") . "</a></li>
			<li><a href=\"$CpanelPath" . "edit-config.php\">" . _("Config Site") . "</a></li>
		</ul>

	</li>";
    }


    print "</ul>
		</div>
      <div class=\"right-nav-container\">
      <div id=\"supersearch\">";

    // determine our default search/search box text
    switch ($subcat) {
      case "records";
        $input_text = _("Search records");
        $target_url = "record.php?record_id=";
        break;
      case "guides";
        $input_text = _("Search guides");
        $target_url = "guide.php?subject_id=";
        break;
      case "faq";
        $input_text = _("Search faqs");
        $target_url = "faq.php?faq_id=";
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
    // don't show to the NOFUN crowd
    if (!isset($_SESSION["NOFUN"])) {
      $input_box = new CompleteMe("quick_search", $CpanelPath . "search.php", $target_url, $input_text, $subcat, "", "private");
      $input_box->displayBox();
    }
    print "</div>
      <div id=\"user\">
      <ul class=\"topnav\">
      <li class=\"zoom\"><a>";

      print $headshot;
    print _("Hi") . ", " . $_SESSION["fname"];

    print "</a>
         <ul class=\"subnav\">
            <li><a href=\"#\">" . _("View My Profile") . "</a></li>
            <li><a href=\"$CpanelPath" . "logout.php\">" . _("Logout") . "</a></li>
         </ul>
      </li>
      </ul>
       </div>
      </div>
";
  }

}

?>