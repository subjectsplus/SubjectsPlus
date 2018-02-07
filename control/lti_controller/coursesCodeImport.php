<?php
include("../includes/autoloader.php"); // need to use this if header not loaded yet
include("../includes/config.php");
require_once('LTICourseController.php');

try {
    $courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor');

    $courses_code->importCourseCode();
    $courses_code->importCourseInstructor();
} catch (Exception $e) {
    echo 'Exception "\n"', $e->getMessage(), "\n";
}