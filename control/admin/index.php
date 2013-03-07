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
?>

<br />
<div style="float: left;  width: 30%;">
    <h2 class="bw_head"><?php print _("Users"); ?></h2>
    <div class="box">
        <ul>
            <li><a href="user.php?type=add"><?php print _("Add New User"); ?></a></li>
            <li><a href="user.php?browse"><?php print _("Browse Users"); ?></a></li>
        </ul>
    </div>

        <h2 class="bw_head">Staff Map</h2>
    <div class="box">
      <p><?php print _("Note:  This is potentially confidential stuff."); ?></p>
        <ul>
            <!--<li><a href="export_contacts.php?type=all"><?php print _("Download All Contact Info"); ?></a></li>-->
            <li><a href="staff_map.php"><?php print _("View Map of Staff Addresses"); ?></a></li>
        </ul>
    </div>
        
    <h2 class="bw_head"><?php print _("Guides"); ?></h2>
    <div class="box">
        <ul>
            <li><a href="manage_guides.php"><?php print _("Manage Guides"); ?></a></li>
            <?php 
            if ($use_disciplines == TRUE) {
                print '<li><a href="disciplines.php">' .  _("Manage Disciplines") . '</a></li>';
            }
            ?>
        </ul>
    </div>

    <h2 class="bw_head"><?php print _("Departments"); ?></h2>
    <div class="box">
        <ul>
            <li><a href="departments.php"><?php print _("Browse/Add New Department"); ?></a></li>
        </ul>
    </div>

    <h2 class="bw_head"><?php print _("Record Source Types"); ?></h2>
    <div class="box">
        <ul>
            <li><a href="sources.php"><?php print _("Add New Source Type"); ?></a></li>
        </ul>
    </div>

    <h2 class="bw_head"><?php print _("FAQ Collections"); ?></h2>
    <div class="box">
        <ul>
            <li><a href="faq_collections.php"><?php print _("Browse/Add FAQ Collections"); ?></a></li>
        </ul>
    </div>
</div>

<div style="float: left; width: 50%;margin-left: 10%; ">
    <h2 class="bw_head"><?php print _("Recent Activity"); ?></h2>
    <div class="box">
        <?php print $recent_activity; ?>
    </div>
</div>


<?php
        include("../includes/footer.php");
?>
