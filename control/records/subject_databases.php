<?php

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\SubjectDatabase;
    
$subsubcat = "";
$subcat = "records";
$page_title = "Admin Databases by Subject";
$feedback = "";

//var_dump($_POST);

include("../includes/header.php");
include("../includes/autoloader.php");
require_once("../includes/config.php");
require_once("../includes/functions.php");

$db = new Querier;
$objDatabases = new SubjectDatabase($db);

$subs_option_boxes = $objDatabases->getSubjectsDropDownItems();

$all_subjects = "
<form method=\"post\" action=\"index.php\" name=\"form\">
<select name=\"item\" id=\"subjects\">
<option id='place_holder'>" . _("      -- Choose Subject --     ") . "</option>
$subs_option_boxes
</select>
</form>";


$guide_collection_list =  "<div id='guide-collection-list-container'>";
$guide_collection_list .= "<ul id='guide-collection-list'></ul>";
$guide_collection_list .= "</div>";



$database_search_viewport = "<div id='search-results-container'>";
$database_search_viewport .= "<label for='add-database-input'>Search</label> ";
$database_search_viewport .= "<input id='add-database-input' type='text' name='add-database-input' />";
$database_search_viewport .= "<div><h4>Search Results</h4><ul id='database-search-results'></ul></div>";
$database_search_viewport .= "</div>";

$associated_databases_viewport = "<div id='database-list-container'>";
$associated_databases_viewport .= "<h4 id='database-label'></h4>";
$associated_databases_viewport .= "<ul id='database-list'></ul>";
$associated_databases_viewport .= "<button id='update-databases-btn' class='pure-button pure-button-primary' style=\"display: none;\">Save Changes</button>";
$associated_databases_viewport .= "</div>";

$about_tb_body = "<p>" . _("You may use this page to set the databases which should be associated with each subject.  This will appear on the public subjects/databases.php page when you click the Databases by Subject dropdown (if your site has one).") . "</p>
    <p><a href=\"" . $PublicPath . "/databases.php?letter=bysub\">" . _("Databases by Subject") . "</a></p>
    <h3>" . _("Usage") . "</h3>
    <ul>
    <li>" . _("Select your subject") . "</li>
    <li>" . _("Search for a record stored in the A-Z list of records") . "</li>
    <li>" . _("Add that record to your list") . "</li>
    <li>" . _("Save and enjoy") . "</li>
    </ul>
    <br />"

;

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
                <div class="titlebar_text"><?php print _("Select Subject"); ?></div>
                <div class="titlebar_options"></div>
            </div>
            <div class="pluslet_body">
                <div class="all-subjects-dropdown dropdown_list"><?php print $all_subjects; ?></div>
            </div>
        </div>
        <?php echo makePluslet(_("Databases Associated with Subject"), $associated_databases_viewport, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">        
        <?php echo makePluslet(_("Databases (Limited to A-Z DB List)"), $database_search_viewport, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("About Databases by Subject"), $about_tb_body, "no_overflow"); ?>
    </div>

</div>
<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>

<script>
    var sds = subjectDatabaseService();
    sds.init();
    $(document).ready(function() {
        $('#subjects').select2();

    });
</script>
