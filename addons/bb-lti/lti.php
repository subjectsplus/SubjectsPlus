<?php
try {
    include("../../control/includes/config.php");
    global $lti_enabled;
    if ($mod_rewrite == 1) {
        $guide_path = "";
    } else {
        $guide_path = $PublicPath;
    }
    if (isset($lti_enabled)){
        if ($lti_enabled){
            if (required_indexes_exist()) {
                include("../../control/includes/autoloader.php"); // need to use this if header not loaded yet
                include_once('../../control/includes/functions.php');
                require_once '../../lib/ims-blti/blti.php';
                require_once('../../control/lti_controller/LTICourseController.php');

                global $lti_secret;
                $lti = new BLTI($lti_secret, false, false);
                session_start();

                if ($lti->valid) {

                    // let's use our Pretty URLs if mod_rewrite = TRUE or 1

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
        }else{
            header("Location: " . $guide_path . "?no_lti_enabled=1"); /* Redirect browser */
        }
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

