<?php

use SubjectsPlus\Control\Querier;
global $AssetPath;
$subsubcat = "";
$page_title = "Lorem ipsum title";
$feedback = "";

//var_dump($_POST);
$use_jquery = array("ui");
include("../../control/includes/autoloader.php");
require_once("../../control/includes/config.php");
require_once("../../control/includes/functions.php");
include("../../subjects/includes/header_um.php");
?>
<div class="wrapper">
    <div>
        <div class="breather">
            <p>Lorem ipsum</p>
            <ul class="guide-listing">
                <?php foreach($results as $guide_title => $guide_link): ?>
                    <li>
                        <a target="_blank" href="<?php echo $guide_link;?>"><?php echo $guide_title;?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>


<?php include("../../subjects/includes/footer_um.php"); ?>