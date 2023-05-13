<?php
//Create a config.php file in the same directory where coursesCodeImport.php file is
// The config.php file needs to contain the following code

//$server_environment = getcwd();
//
//switch($server_environment) {
//
//    case '/path/to/lti_controller/directory':
//        define('SP_PATH', '/path/to/local/sp4/directory');
//        break;
//}
//
// $med_course_codes = []; //an array of course codes
//

include('config.php');
include(getcwd() . "/../../control/includes/autoloader.php"); // need to use this if header not loaded yet
include(getcwd() . "/../../control/includes/config.php");
require_once('LTICourseController.php');

try {
    $courses_code = new LTICourseController('bb_course_code2', 'bb_course_instructor2');

    $courses_code->importCourseCode();
    $courses_code->importCourseInstructor();
} catch (Exception $e) {
    echo 'Exception "\n"', $e->getMessage(), "\n";
}
