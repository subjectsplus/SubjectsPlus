<?php
/**
 *   @file guide_collections.php
 *   @brief CRUD collections of guides for display on public page
 *
 *   @author adarby
 *   @date Aug 2015
 *   
 */

use SubjectsPlus\Control\Querier;

    
$subsubcat = "";
$subcat = "admin";
$page_title = "Admin Guide Collections";
$feedback = "";

//var_dump($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");


$add_collection_box = "
<form id=\"guide-collection-form\" class=\"pure-form pure-form-stacked\">
<label for=\"title\">" . _("Collection Name") . "</label>
<input type=\"text\" id='title' name=\"title\" class=\"required_field\" required>
<label for=\"description\">" . _("Description") . "</label>
<textarea name=\"description\" id=\"description\"></textarea>
<label for=\"shortform\">" . _("Shortform (must be unique)") . "</label>
<input type=\"text\" id='shortform' name=\"shortform\" class=\"required_field\" required>
<button class=\"button pure-button pure-button-primary\" id=\"add_collection\" name=\"add_collection\" >" . _("Add New Collection") . "</button>
</form>
";


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
$guide_collection_viewport .= "<dl><dt>" . _("Description") . "</dt><dd id='collection-description'></dd>";
$guide_collection_viewport .= "<dt>" . _("Shortform") . "</dt><dd id='collection-shortform'></dd></dl>";
$guide_collection_viewport .= "<p id='collection-shortform'></p>";
$guide_collection_viewport .= "<button id='edit-collection-metadata-btn' class='pure-button pure-button-primary'>Edit Collection</button>";
$guide_collection_viewport .= "</div>";


$guide_collection_viewport .= "<div id='collection-metadata-editform' data-collection_id='' style='display:none;'>";
$guide_collection_viewport .= "<label for='collection-title-input'>" . _("Collection Name") . "</label>";
$guide_collection_viewport .= "<input type='text' class='collection-metadata-edit-input' id='collection-title-input' name='collection-title-input'>";
$guide_collection_viewport .= "<label for='collection-description-input'>" . _("Description") . "</label>";
$guide_collection_viewport .= "<input type='text' class='collection-metadata-edit-input' id='collection-description-input' name='collection-description-input'/>";
$guide_collection_viewport .= "<label for='collection-shortform-input'>" . _("Shortform") . "</label>";
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

    <div class="pure-u-1">
        <div class="flash-msg-container"><p id="flash-msg"></p></div>
        <div class="error-dialog" id="error-dialog-title">Title cannot be empty</div>
        <div class="error-dialog" id="error-dialog-shortform">Shortform cannot be empty</div>
        <div class="error-dialog" id="error-dialog-shortform-dup">Shortform must be unique. Please choose another</div>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Add New Collection"), $add_collection_box , "no_overflow"); ?>
        <?php echo makePluslet(_("Guide Collections List"), $guide_collection_list, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Add Guides"), $guide_search_viewport, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Collection Details"), $guide_collection_viewport, "no_overflow"); ?>
        <?php echo makePluslet(_("Associated Guides"), $associated_guides_viewport, "no_overflow"); ?>
    </div>

</div>

<script src="<?php echo getControlURL(); ?>includes/js_custom.php" type="text/javascript"></script>

<script>
    var gcs = guideCollectionService();
    gcs.init();
</script>
