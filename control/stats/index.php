<?php

/**
 *   @file index.php
 *   @brief Splash page for stats
 *
 *   @author adarby
 *   @date mar 2014
 */



$subcat = "stats";
$page_title = "Stats in SP";

include("../includes/config.php");
include("../includes/header.php");


try {
  } catch (Exception $e) {
  echo $e;
}

?>
<div class="pure-g">
  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Stats stats stats"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="topimage"></div>
      <div class="pluslet_body">
        <p></p>
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("All Guides"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="topimage"></div>
      <div class="pluslet_body">
        <p></p>
        
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Add Transaction"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="topimage"></div>
      <div class="pluslet_body">
        <ol>
          <li><a href="metadata.php"><?php print _("Create new guide"); ?></a></li>
        </ol>
      </div>
    </div>

  </div>
</div>

<?php
include("../includes/footer.php");
?>
