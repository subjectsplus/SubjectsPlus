<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 6/29/2017
 * Time: 11:01 AM
 */
use SubjectsPlus\Control\Querier;

class LTICourseController
{
    private $course_code_table_name = '';
    private $course_instructor_table_name = '';
    private $db = '';
    private $connection = '';
    private $course_code_file_path = '';
    private $course_instructor_file_path = '';


    function __construct($course_code_table_name='bb_course_code', $course_instructor_table_name='bb_course_instructor', $course_code_file_path="", $course_instructor_file_path="") {
        $this->course_code_table_name = $course_code_table_name;
        $this->course_instructor_table_name = $course_instructor_table_name;
        $this->db = new Querier();
        $this->connection = $this->db->getConnection();
        $this->course_code_file_path = $course_code_file_path;
        $this->course_instructor_file_path = $course_instructor_file_path;
    }

    function importCourseCode()
    {
        if (!$this->tableExists($this->course_code_table_name)) {
            $statement = $this->connection->prepare("CREATE TABLE IF NOT EXISTS " . $this->course_code_table_name . " (id INT NOT NULL AUTO_INCREMENT, 
            course_code LONGTEXT DEFAULT NULL, course_title LONGTEXT DEFAULT NULL, PRIMARY KEY (id))");
            $statement->execute();
        }
        $this->updateBBCoursesCodeTable();
    }

    function importCourseInstructor()
    {
        if (!$this->tableExists($this->course_instructor_table_name)) {
            $statement = $this->connection->prepare("CREATE TABLE IF NOT EXISTS " . $this->course_instructor_table_name . " (id INT NOT NULL AUTO_INCREMENT, 
            course_id LONGTEXT DEFAULT NULL, instructor LONGTEXT DEFAULT NULL, PRIMARY KEY (id))");
            $statement->execute();
        }
        $this->updateBBCourseInstructorTable();
    }

    private function updateBBCoursesCodeTable()
    {
        $file = fopen($this->course_code_file_path, "r");
        fgets($file); // skip first line
        //Output a line of the file until the end is reached
        $line = fgets($file);
        while (!feof($file)) {
            $course_data = explode("UOML", $line);
            $course_temp = explode("	", $course_data[0]);
            $course_code = $course_temp[0];
            $course_name = $course_temp[1];

            $statement = $this->connection->prepare("INSERT INTO " . $this->course_code_table_name . " (course_code, course_title)
            SELECT * FROM (SELECT '" . $course_code . "', '" . $course_name . "') AS tmp
            WHERE NOT EXISTS (SELECT course_code FROM " . $this->course_code_table_name . " WHERE course_code = '" . $course_code . "')");
            $statement->execute();
            $line = fgets($file);
        }
        fclose($file);
        echo 'done';
    }

    private function updateBBCourseInstructorTable()
    {
        $file = fopen($this->course_instructor_file_path, "r");
        fgets($file); // skip first line
        //Output a line of the file until the end is reached
        $line = fgets($file);
        while (!feof($file)) {
            $course_temp = explode("	", $line);
            $course_id = $course_temp[0];
            $course_instructor = $course_temp[1];

            $statement = $this->connection->prepare("INSERT INTO " . $this->course_instructor_table_name . " (course_id, instructor)
            SELECT * FROM (SELECT '" . $course_id . "', '" . $course_instructor . "') AS tmp
            WHERE NOT EXISTS (SELECT course_id FROM " . $this->course_instructor_table_name . " WHERE course_id = '" . $course_id . "')");

            $statement->execute();
            $line = fgets($file);
        }
        fclose($file);
        echo 'done';
    }

    private function tableExists($table_name)
    {
        $statement = $this->connection->prepare("SHOW TABLES LIKE '" . $table_name . "'");
        $statement->execute();
        $result = $statement->fetchAll();
        if (count($result) != 0) {
            return true;
        }
        return false;
    }

    private function getCourseURL($subject_code){
        $temp = explode("-", $subject_code);
        $course_code = $temp[0];
        $instructor_temp = $this->getInstructorByCourseCode($subject_code);
        $instructor_temp = $instructor_temp[0];
        $instructor_temp = $instructor_temp['instructor'];
        $instructor = trim($instructor_temp);

        if (!empty($instructor)){
            $q = "SELECT subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND course_code = '" . $course_code . "' AND instructor LIKE '%" . $instructor . "%' ORDER BY subject";
            $statement = $this->connection->prepare($q);
            $statement->execute();
            $result = $statement->fetchAll();

            if (count($result) != 0){
                return $result;
            }
        }

        $q = "SELECT subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND course_code = '" . $course_code . "' AND instructor = 'None' ORDER BY subject";

        $statement = $this->connection->prepare($q);
        $statement->execute();
        return $statement->fetchAll();
    }

    private function getInstructorByCourseCode ($course_id){
        $q = "SELECT instructor FROM $this->course_instructor_table_name WHERE course_id = '" . $course_id . "'";
        $statement = $this->connection->prepare($q);
        $statement->execute();
        return $statement->fetchAll();
    }

    function processCourseCode($course_code, $guide_path){
        //Find guides by subject code and course number
        $guides = $this->getCourseURL($course_code);
        $guides_count = count($guides);

       if ($guides_count == 0){ //If nothing found, then find guides by subject code only
            $subject_code = substr($course_code, 0, 3);

            $guides = $this->getCourseURL($subject_code);
            $guides_count = count($guides);
        }

        //Redirect according to the results
        if ($guides_count == 0){
            header("Location: " . $guide_path); /* Redirect browser */
        }elseif ($guides_count == 1){
            header("Location: " . $guide_path . $guides[0]['shortform']); /* Redirect browser */
        }else{
            $results = array();
            foreach ($guides as $guide){
                $results[$guide['subject']] = $guide_path . $guide['shortform'];
            }
            include ('lti_view/multiple_guides_view.php');
        }
    }
}