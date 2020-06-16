<?php

$subcat = "guides";
$page_title = "SubjectsPlus-Blackboard Course Codes Integrations";

include("../includes/header.php");

global $lti_enabled;
if (!$lti_enabled){
    print "
    <div class=\"master-feedback\" style=\"display:block;\">The LTI integration is disabled. Sorry for the inconvenience</div>
    ";
    die();
}

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\SP_BB_Integration\Integration;

$db = new Querier;
$integration = new Integration($db);

?>

<?php 
include("../includes/footer.php");
?>
