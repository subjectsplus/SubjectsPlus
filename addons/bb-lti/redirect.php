<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../lib/LTI13/SP_Database.php';

use \IMSGlobal\LTI;

$launch = LTI\LTI_Message_Launch::new(new SP_Database())
                                ->validate();

$jwt = $_REQUEST['id_token'];

$course_label = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $jwt)[1]))), true)['https://purl.imsglobal.org/spec/lti/claim/custom']['label'];

try {
    require_once __DIR__ . '/../../control/includes/config.php';

    global $lti_enabled;
    global $PublicPath;
    $guide_path = $PublicPath;

    require_once __DIR__ . '/../../control/includes/functions.php';
    require_once __DIR__ . '/../../control/lti_controller/LTICourseController.php';

    // TODO: require course_id instead of context_label
    if (isset($lti_enabled) && $lti_enabled) {
        $course_label = scrubData($course_label);
        $courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor');
        $courses_code->processCourseCode($course_label, $guide_path);

    } else {
        header("Location: " . $guide_path . "?invalid_lti_call=1"); /* Redirect browser */
    }

} catch (Exception $e) {
    echo 'Exception "\n"', $e->getMessage(), "\n";
    exit();
}
