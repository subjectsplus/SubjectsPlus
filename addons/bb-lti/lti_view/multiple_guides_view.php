<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};
global $AssetPath;
$subsubcat = "";
$page_title = "Select your guide";
$feedback = "";

$use_jquery = array("ui");
include("../../control/includes/autoloader.php");
require_once("../../control/includes/config.php");
require_once("../../control/includes/functions.php");
include("../../subjects/includes/header_um.php");
?>
    <div class="panel-container panel-adj">
    <div class="wrapper">
        <div>
            <div class="breather index-search-area">
                <div class="guide_list">
                    <div class="guide_list_header">
                        <h3>There are several guides matching your selection:</h3>
                        <ul class="guide-listing">
                            <?php foreach ($results as $guide_title => $guide_link): ?>
                                <li>
                                    <a target="_blank" href="<?php echo $guide_link; ?>"><?php echo $guide_title; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                </div>
            </div><!--end breather-->
        </div>
    </div>


<?php include("../../subjects/includes/footer_um.php"); ?>