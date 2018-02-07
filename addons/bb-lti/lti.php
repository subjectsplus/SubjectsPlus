<?php
try {
    if (required_indexes_exist()) {
        include("../../control/includes/autoloader.php"); // need to use this if header not loaded yet
        include("../../control/includes/config.php");
        include_once('../../control/includes/functions.php');
        require_once '../../lib/ims-blti/blti.php';
        require_once('../../control/lti_controller/LTICourseController.php');

        global $lti_secret;
        $lti = new BLTI($lti_secret, false, false);
        session_start();

        if ($lti->valid) {

            // let's use our Pretty URLs if mod_rewrite = TRUE or 1
            if ($mod_rewrite == 1) {
                $guide_path = "";
            } else {
                $guide_path = $PublicPath;
            }
            $course_label = scrubData($_REQUEST["context_label"]);

            $courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor');

            $courses_code->processCourseCode($course_label, $guide_path);
        }else{
            die('Invalid LTI call');
            exit();
        }
    } else {
        die('Invalid LTI call');
        exit();
    }
} catch (Exception $e) {
    echo 'Exception "\n"', $e->getMessage(), "\n";
    exit();
}

function required_indexes_exist()
{
    if (isset($_REQUEST["lti_message_type"]) and isset($_REQUEST["lti_version"]) and isset($_REQUEST["resource_link_id"])) return (true);
    return false;
}

