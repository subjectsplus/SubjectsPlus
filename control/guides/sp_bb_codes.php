<?php

$subcat = "guides";
$page_title = "SP-Blackboard Course Codes Integrations";

include("../includes/header.php");

global $lti_enabled;
if (!$lti_enabled){
    print "
    <div class=\"master-feedback\" style=\"display:block;\">The LTI integration is disabled. Sorry for the inconvenience</div>
    ";
    die();
}

use SubjectsPlus\Control\Querier;
require_once( __DIR__ .'/sp-bb-special-codes/controller/Integration.php');

$db = new Querier;
$integration = new Integration($db);

$currentCodesList = $integration->getSpecialCourseCodesList();
$addNewSpecialCodeTemplate = $integration->getAddCourseCodeTemplateForm();
$editSpecialCodeTemplate = $integration->getEditCourseCodeTemplateForm();

include( __DIR__ . "/sp-bb-special-codes/views/sp-bb-codes-main.php");

include("../includes/footer.php");
