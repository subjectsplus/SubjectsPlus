<?php
include("../../../control/includes/autoloader.php"); // need to use this if header not loaded yet
include("../../../control/includes/config.php");
require_once('LTICourseController.php');

try {
    $courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor');

    echo $courses_code->importCourseCode();
    echo $courses_code->importCourseInstructor();
} catch (Exception $e) {
    echo 'Exception "\n"', $e->getMessage(), "\n";
}

