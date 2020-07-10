<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\SubjectBBCourse;
    
$subsubcat = "";
$subcat = "guide";
$page_title = "Manage Guides and Blackboard Courses";
$feedback = "";

//var_dump($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");
require_once("../includes/config.php");
require_once("../includes/functions.php");

$db = new Querier;
$objSubjectCodes = new SubjectBBCourse($db);

$subs_option_boxes = $objSubjectCodes->getSubjectCodesDropDownItems();
$current_associations = $objSubjectCodes->getCurrentAssociations();

$all_subject_codes = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" id=\"subject_codes\">
<option id='place_holder'>" . _("      -- Choose Subject Code --     ") . "</option>
$subs_option_boxes
</select>
</form>";


$guide_collection_list =  "<div id='guide-collection-list-container'>";
$guide_collection_list .= "<ul id='guide-collection-list'></ul>";
$guide_collection_list .= "</div>";



$subject_code_search_viewport = "<div id='search-results-container'>";
$subject_code_search_viewport .= "<label for='add-guide-input'>Search</label> ";
$subject_code_search_viewport .= "<input id='add-guide-input' type='text' name='add-guide-input' />";
$subject_code_search_viewport .= "<div><h4>Search Results</h4><ul id='guide-search-results'></ul></div>";
$subject_code_search_viewport .= "</div>";

$associated_guides_viewport = "<div id='guide-list-container'>";
$associated_guides_viewport .= "<h4 id='guide-label'></h4>";
$associated_guides_viewport .= "<ul id='guide-list'></ul>";
$associated_guides_viewport .= "<button id='update-guides-btn' class='pure-button pure-button-primary' style=\"display: none;\">Save Changes</button>";
$associated_guides_viewport .= "</div>";



?>
<style>
    .error-dialog {
        display:none;
    }
</style>



<div class="pure-g">

    <div class="pure-u-1-2">
        <?php echo makePluslet(_("Current Associations"), $current_associations, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-2">
        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php print _("Select Subject Code"); ?></div>
                <div class="titlebar_options"></div>
            </div>
            <div class="pluslet_body">
                <div class="all-subjects-dropdown dropdown_list"><?php print $all_subject_codes; ?></div>
            </div>
        </div>
        <div class="pure-u-1-2">
            <?php echo makePluslet(_("Search Guides"), $subject_code_search_viewport, "no_overflow"); ?>
        </div>
    </div>



</div>
<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/guides/SubjectGuideService.js"></script>

<script>
    var sgs = subjectGuideService();
    sgs.init();
    $(document).ready(function() {
        $('#subject_codes').select2();

    });
</script>
