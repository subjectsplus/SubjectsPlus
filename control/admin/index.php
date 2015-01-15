<?php
/**
 *   @file index.php
 *   @brief Splash interface to the SubjectsPlus admin folder.
 *
 *   @author agdarby
 *   @date Dec 2012
 *   @todo
 */
$subcat = "admin";
$page_title = "Admin SubjectsPlus";

include("../includes/header.php");

$recent_activity = seeRecentChanges("", 20);

$users_box = "<ul>
            <li><a href=\"user.php?type=add\">" .  _("Add New User") . "</a></li>
            <li><a href=\"user.php?browse\">" . _("Browse Users") . "</a></li>
        </ul>";

print "
<div class=\"pure-g\">
  <div class=\"pure-u-1-3\">  
  ";

makePluslet(_("Users"), $users_box, "no_overflow");

$map_box = "<p>" . _("Note:  This is potentially confidential stuff.") . "</p>
        <ul>
            <li><a href=\"staff_map.php\">" . _("View Map of Staff Addresses") . "</a></li>
        </ul>";

makepluslet(_("Staff Map"), $map_box, "no_overflow");

$guides_box = "<ul>
            <li><a href=\"manage_guides.php\">" . _("Manage Guides") . "</a></li>";

            if ($use_disciplines == TRUE) {
                $guides_box .= '<li><a href="disciplines.php">' .  _("Manage Disciplines") . '</a></li>';
            }

        $guides_box .=  "</ul>";

makepluslet( _("Guides"), $guides_box, "no_overflow");

$departments_box = "
<ul>
    <li><a href=\"departments.php\">" . _("Browse/Add New Department") . "</a></li>
</ul>";
    
makepluslet(_("Departments"), $departments_box, "no_overflow");

 $sources_box = "<ul>
            <li><a href=\"sources.php\">" . _("Add New Source Type") . "</a></li>
            <li><a href=\"../guides/link_checker.php?type=records\">" . _("Check All Records' Links") . "</a></li>
        </ul>"; 


makepluslet(_("Record Source Types"), $sources_box, "no_overflow");

$faq_box = "<ul>
            <li><a href=\"faq_collections.php\">" . _("Browse/Add FAQ Collections") . "</a></li>
        </ul>";

makepluslet( _("FAQ Collections"), $faq_box, "no_overflow");
        

print "</div>"; // close pure-u-1-3
print "<div class=\"pure-u-2-3\">";

makePluslet(_("Recent Activity"), $recent_activity, "no_overflow");

print "</div>"; // close pure-u-2-3
print "</div>"; // close pure

?>


<?php
        include("../includes/footer.php");
?>