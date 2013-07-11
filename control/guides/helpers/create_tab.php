<?php

/**
 *   @file create_tab.php
 *   @brief Create new tab
 *   @description
 *
 *   @author agdarby, dgonzalez
 *   @date updated june 2013
 */

$subsubcat = "";
$subcat = "guides";
$page_title = "Create Tabs include";
$header = "noshow";

include("../../includes/header.php");

print dropBoxes(0, 'left', "");
print dropBoxes(1, 'center', "");
print dropBoxes(2, 'sidebar', "");
print '<div id="clearblock" style="clear:both;"></div> <!-- this just seems to allow the space to grow to fit dropbox areas -->';

function dropBoxes($i, $itext, $content) {
	global $AssetPath;

	$col = '<div id="container-' . $i . '" style="position: relative; float: left; width: 30%;">
	<div class="dropspotty unsortable" id="dropspot-' . $itext . '-1">
	<img src="' . $AssetPath . 'images/icons/package-x-generic.png" alt="' . _("Drop Content Here") . '" />
    <span class="dropspot-text">' . _("Drop Here") . '</span>
    </div>
    <div class="portal-column sort-column portal-column-' . $i . '" id="portal-column-' . $i . '" style="float: left;">' .
	$content . "<div><br /></div>"
	. '</div></div>';

	return $col;
}

?>