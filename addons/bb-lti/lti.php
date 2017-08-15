<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

include("../../control/includes/autoloader.php"); // need to use this if header not loaded yet
include("../../control/includes/config.php");
require_once '../../lib/ims-blti/blti.php';
require_once('lti_controller/LTICourseController.php');

global $lti_secret;
$lti = new BLTI($lti_secret, false, false);

session_start();
//Where the magic happens!
if ($lti->valid) {

    // let's use our Pretty URLs if mod_rewrite = TRUE or 1
    if ($mod_rewrite == 1) {
        $guide_path = "";
    } else {
        $guide_path = $PublicPath;
    }
    $course_label = $_REQUEST["context_label"];

    $courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor');

    $courses_code->processCourseCode($course_label, $guide_path);
}else{
    echo 'Error';
}