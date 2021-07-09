<?php
/**
 *   @file blank-404.php
 *   @brief 404 page
 *
 *   @author AGD
 *   @date Jan 2019
 */
$page_title = "404 -- Page Not Found";


include_once(__DIR__ . "/includes/header_custom.php");
?>

  <div class="pure-g">
    <div class="pure-u-1-2">

  <div class="pluslet no_overflow">
    <div class="titlebar">
      <div class="titlebar_text"><?php print _("Oh No 404"); ?></div>
      <div class="titlebar_options"></div>
    </div>
    <div class="pluslet_body">
    <?php print _("This page does not seem to exist.") ?>
    <br />
    <a href="index.php"><?php print _("See a list of all research guides."); ?></a>
    </div>
    </div>

    </div>
    <div class="pure-u-1-2">

    </div>
  </div>


<?php include_once(__DIR__ . "/includes/footer_custom.php"); ?>
