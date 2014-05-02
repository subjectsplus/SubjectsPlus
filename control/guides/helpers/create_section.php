<?php

/**
 *   @file create_section.php
 *   @brief Create new section HTML
 *   @description
 *
 *   @author agdarby, dgonzalez
 *   @date updated April 2014
 */

use SubjectsPlus\Control\Guide;

$subsubcat = "";
$subcat = "guides";
$page_title = "Create Tabs include";
$header = "noshow";

include("../../includes/header.php");

$lobjGuide = new Guide();

//print section and slider div
$new_id = rand(1, 100000);

print "<div id=\"section_new_$new_id\" class=\"sp_section\" data-layout=\"4-4-4\">";
print "<div class=\"sp_section_controls\">
			<img src=\"$IconPath/hand_cursor-26.png\" class=\"section_sort\"/>
			<div id=\"slider_section_new_$new_id\" class=\"sp_section_slider\"></div>
	   </div>";

print $lobjGuide->dropBoxes(0, 'left', "");
print $lobjGuide->dropBoxes(1, 'center', "");
print $lobjGuide->dropBoxes(2, 'sidebar', "");
print '<div id="clearblock" style="clear:both;"></div> <!-- this just seems to allow the space to grow to fit dropbox areas -->';
print '</div>';
?>
<script type="text/javascript">
	makeSectionSlider('div[id="<?php echo "slider_section_new_$new_id"; ?>"]');
</script>