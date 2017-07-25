<?php
include("../../../control/includes/autoloader.php"); // need to use this if header not loaded yet
include("../../../control/includes/config.php");
require_once('LTICourseController.php');

$courses_code = new LTICourseController('bb_course_code', 'bb_course_instructor',getcwd() . 'temp_files/20170629-0800_courses.txt', 'temp_files/20170721-1528_course_instructors.txt');

echo $courses_code->importCourseCode();
echo $courses_code->importCourseInstructor();