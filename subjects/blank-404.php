<?php
/**
 *   @file blank-404.php
 *   @brief 404 page
 *
 *   @author AGD
 *   @date Jan 2019
 */
$page_title = "404 -- Page Not Found";
include_once(__DIR__ . "/../control/includes/config.php");
include_once(__DIR__ . "/../control/includes/functions.php");

$this_fname = __DIR__ . "/blank-404.php";
$that_fname = theme_file($this_fname, $subjects_theme);
if ( $this_fname != $that_fname ) { include_once($that_fname); exit; }

include_once(theme_file(__DIR__ . "/includes/header.php", $subjects_theme));
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


<?php 
//print "<pre>";
//print_r($results);
include_once(theme_file(__DIR__ . "/includes/footer.php", $subjects_theme));
?>
