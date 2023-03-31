<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../lib/LTI13/SP_Database.php';

use \IMSGlobal\LTI;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$launch = LTI\LTI_Message_Launch::new(new SP_Database())
                                ->validate();

//var_dump($_REQUEST['id_token']);
//die();

$jwt = $_REQUEST['id_token'];
$key = "e6a14d56-4190-4a3b-b3da-7d728808f163";
$decoded = JWT::decode($jwt, new Key($key, 'RS256'));

print_r($decoded);
die();

try {
    require_once __DIR__ . '/../../control/includes/config.php';

    global $lti_enabled;
    global $PublicPath;
    $guide_path = $PublicPath;

    require_once __DIR__ . '/../../control/includes/functions.php';
    require_once __DIR__ . '/../../control/lti_controller/LTICourseController.php';

    // TODO: require course_id instead of context_label
    if (isset($lti_enabled) && $lti_enabled) {
        $course_label = scrubData($_REQUEST["context_label"]);
        $courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor');
        $courses_code->processCourseCode($course_label, $guide_path);

    } else {
        header("Location: " . $guide_path . "?invalid_lti_call=1"); /* Redirect browser */
    }

} catch (Exception $e) {
    echo 'Exception "\n"', $e->getMessage(), "\n";
    exit();
}
