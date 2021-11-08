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

include_once(__DIR__ . "/../control/includes/config.php");
include_once(__DIR__ . "/../control/includes/functions.php");
include_once(__DIR__ . "/../control/includes/autoloader.php");

$this_fname = __DIR__ . "/noguide.php";
$that_fname = theme_file($this_fname, $subjects_theme);
if ( $this_fname != $that_fname ) { include($that_fname); exit; }

include_once(theme_file(__DIR__ . "/includes/header.php", $subjects_theme));

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

//print "<pre>";
//print_r($results);

include_once(theme_file(__DIR__ . "/includes/footer.php", $subjects_theme));

?>
