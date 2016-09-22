<?php
/**
 *   @file guide_collections.php
 *   @brief CRUD collections of guides for display on public page
 *
 *   @author acarrasco
 *   @date Sept 2016
 *   
 */

use SubjectsPlus\Control\Querier;

    
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Subject Databases";
$feedback = "";

//var_dump($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");


$subs_option_boxes = getSubjectsDropDownItems("guide.php?subject_id=", "", 1);

$all_subjects = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" id=\"subjects\" size=\"1\" >
<option id='place_holder'>" . _("      -- Choose Subject --     ") . "</option>
$subs_option_boxes
</select>
</form>";


$guide_collection_list =  "<div id='guide-collection-list-container'>";
$guide_collection_list .= "<ul id='guide-collection-list'></ul>";
$guide_collection_list .= "</div>";



$guide_search_viewport = "<div id='search-results-container'>";
$guide_search_viewport .= "<label for='add-guide-input'>Search</label>";
$guide_search_viewport .= "<input id='add-guide-input' type='text' name='add-guide-input' />";
$guide_search_viewport .= "<div><h4>Search Results</h4><ul id='guide-search-results'></ul></div>";
$guide_search_viewport .= "</div>";



$guide_collection_viewport =  "<div id='guide-collection-viewport-container'>";

$guide_collection_viewport .= "<div id='collection-metadata' data-collection_id=''>";
$guide_collection_viewport .= "<h3 id='collection-title'></h3>";
$guide_collection_viewport .= "<p id='collection-description'></p>";
$guide_collection_viewport .= "<p id='collection-shortform'></p>";
$guide_collection_viewport .= "<button id='edit-collection-metadata-btn' class='pure-button pure-button-primary'>Edit Collection</button>";
$guide_collection_viewport .= "</div>";


$guide_collection_viewport .= "<div id='collection-metadata-editform' data-collection_id='' style='display:none;'>";
$guide_collection_viewport .= "<input type='text' class='collection-metadata-edit-input' id='collection-title-input' name='collection-title-input'>";
$guide_collection_viewport .= "<input type='text' class='collection-metadata-edit-input' id='collection-description-input' name='collection-description-input'/>";
$guide_collection_viewport .= "<input type='text' class='collection-metadata-edit-input' id='collection-shortform-input' name='collection-shortform-input'/>";
$guide_collection_viewport .= "<button id='update-collection-metadata-btn' class='pure-button pure-button-primary'>Save</button>";
$guide_collection_viewport .= "</div>";

$guide_collection_viewport .= "</div>";

$associated_guides_viewport = "<div id='guide-list-container'>";
$associated_guides_viewport .= "<h4 id='guide-label'>Reorder Guides in this Collection</h4>";
$associated_guides_viewport .= "<ul id='guide-list'></ul>";
$associated_guides_viewport .= "</div>";


?>
<style>
    .error-dialog {
        display:none;
    }
</style>



<div class="pure-g">

    <div class="pure-u-1-3">
        <div class="pluslet">
            <div class="titlebar">
                <div class="titlebar_text"><?php print _("Subject"); ?></div>
                <div class="titlebar_options"></div>
            </div>
            <div class="pluslet_body">
                <div class="all-subjects-dropdown dropdown_list"><?php print $all_subjects; ?></div>
            </div>
        </div>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Add Guides"), $guide_search_viewport, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Collection Details"), $guide_collection_viewport, "no_overflow"); ?>
        <?php echo makePluslet(_("Associated Guides"), $associated_guides_viewport, "no_overflow"); ?>
    </div>

</div>
<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>

<script>
    var gcs = guideCollectionService();
    gcs.init();
    $(document).ready(function() {
        $('#subjects').select2();

    });
</script>
