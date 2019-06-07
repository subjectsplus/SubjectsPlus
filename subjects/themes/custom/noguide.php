<?php
/**
 *   @file noguide.php
 *   @brief Page for when a guide is suppressed or unavailable page
 *
 *   @author AGD
 *   @date Jan 2019
 */


$page_title = "Search Results";

$use_jquery = array("ui"); 




include("includes/header_custom.php");

?>

  <div class="pure-g">
    <div class="pure-u-1-2">

  <div class="pluslet no_overflow">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Guide Unavailable"); ?></div>
      <div class="titlebar_options"></div>
    </div>
    <div class="pluslet_body">
    <?php print _("This guide is currently unavailable. It may be under maintenance, or just resting.") ?>
    <br />
    <a href="index.php"><?php print _("Find another guide."); ?></a>
    </div>
    </div>

    </div>
    <div class="pure-u-1-2">

    </div>
  </div>


<?php 


include("includes/footer_custom.php");

?>