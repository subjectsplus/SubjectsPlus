<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};
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


    function __construct($course_code_table_name = 'bb_course_code', $course_instructor_table_name = 'bb_course_instructor')
    {
        $this->course_code_table_name = $course_code_table_name;
        $this->course_instructor_table_name = $course_instructor_table_name;
        $this->db = new Querier();
        $this->connection = $this->db->getConnection();
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
        global $lti_courses_dir_path;
        $file_path = $this->getLatestFileFromServer($lti_courses_dir_path);
        sleep(10);
        $file = fopen($file_path, "r");
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
        $this->deleteTempFile($file_path);
        echo 'done';
    }

    private function deleteTempFile($file){
        unlink($file);
    }

    public function getLatestFileFromServer($file_path='')
    {
        global $lti_service_account_username;
        global $lti_service_account_password;
        global $lti_sftp_server_url;

        // Make our connection
        $sftp_connection = ssh2_connect($lti_sftp_server_url);

        // Authenticate
        if (!ssh2_auth_password($sftp_connection, $lti_service_account_username, $lti_service_account_password)) throw new Exception('Unable to connect.');

        // Create our SFTP resource
        if (!$sftp = ssh2_sftp($sftp_connection)) throw new Exception('Unable to create SFTP connection.');

        //Set ignored elements array
        $ignored = array('.', '..', '.svn', '.htaccess', 'instructors');

        // Get and sort the files
        $files = array();
        foreach (scandir('ssh2.sftp://' . $sftp . $file_path) as $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime('ssh2.sftp://' . $sftp . $file_path . '/' . $file);
        }

        arsort($files);
        $files = array_keys($files);

        if (!empty($files)) {
            $last_file = $files[0];
            $this->downloadFileFromServer($sftp, $file_path, $last_file);
        }

        ssh2_exec($sftp_connection, 'exit');
        unset($sftp_connection);

        return "./temp_files/$last_file";
    }

    private function downloadFileFromServer($sftp, $file_path, $last_file){
        // Remote stream
        if (!$remoteStream = @fopen("ssh2.sftp://$sftp/$file_path/$last_file", 'r')) {
            echo "Unable to open remote file: $last_file";
        }

        // Local stream
        if (!$localStream = @fopen("temp_files/$last_file", 'w')) {
            echo "Unable to open local file for writing: temp_files/$last_file";
        }

        // Write from our remote stream to our local stream
        $read = 0;
        $fileSize = filesize("ssh2.sftp://$sftp/$file_path/$last_file");
        while ($read < $fileSize && ($buffer = fread($remoteStream, $fileSize - $read))) {
            // Increase our bytes read
            $read += strlen($buffer);

            // Write to our local file
            if (fwrite($localStream, $buffer) === FALSE) {
                echo "Unable to write to local file: temp_files/$last_file";
            }
        }

        // Close our streams
        fclose($localStream);
        fclose($remoteStream);
    }

    private function updateBBCourseInstructorTable()
    {
        global $lti_instructors_dir_path;
        $file_path = $this->getLatestFileFromServer($lti_instructors_dir_path);
        sleep(10);
        $file = fopen($file_path, "r");
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

    private function getCourseURL($subject_code)
    {
        $temp = explode("-", $subject_code);
        $course_code = $temp[0];
        $instructor = $this->getInstructorByCourseCode($subject_code);

        if (!empty($instructor)) {
            $q = "SELECT subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND course_code = '" . $course_code . "' AND instructor LIKE '%" . $instructor . "%' ORDER BY subject";
            $statement = $this->connection->prepare($q);
            $statement->execute();
            $result = $statement->fetchAll();

            if (count($result) != 0) {
                return $result;
            }
        }

        $q = "SELECT subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND course_code = '" . $course_code . "' AND (instructor = 'None' OR instructor IS NULL OR instructor = '' )";

        $statement = $this->connection->prepare($q);
        $statement->execute();
        return $statement->fetchAll();
    }

    private function getInstructorByCourseCode($course_id)
    {
        $q = "SELECT instructor FROM $this->course_instructor_table_name WHERE course_id = '" . $course_id . "'";
        $statement = $this->connection->prepare($q);
        $statement->execute();
        $instructor_temp = $statement->fetchAll();
        if (!empty($instructor_temp)){
            $instructor_temp = $instructor_temp[0];
            $instructor_temp = $instructor_temp['instructor'];
            $instructor = trim($instructor_temp);
            return $instructor;
        }else{
            return "";
        }
    }

    private function getGuidesByInstructor($instructor)
    {
        $q = "SELECT subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND instructor LIKE '%" . $instructor . "%' ORDER BY subject";
        $statement = $this->connection->prepare($q);
        $statement->execute();
        return $statement->fetchAll();
    }

    function processCourseCode($course_code, $guide_path)
    {
        $instructor = $this->getInstructorByCourseCode($course_code);

        if (!empty($instructor)) {
            $intructor_courses = $this->getGuidesByInstructor($instructor);
            $instructor_courses_count = count($intructor_courses);

            if ($instructor_courses_count == 1) {
                header("Location: " . $guide_path . $intructor_courses[0]['shortform']); /* Redirect browser */
            } elseif ($instructor_courses_count > 1) {
                $results = array();
                foreach ($intructor_courses as $guide) {
                    $results[$guide['subject']] = $guide_path . $guide['shortform'];
                }
                include('lti_view/multiple_guides_view.php');
            }else{
                $this->findGuideBySubjectCodeAndCourseNumber($course_code, $guide_path);
            }
        }
        else {
            $this->findGuideBySubjectCodeAndCourseNumber($course_code, $guide_path);
        }
    }

    function findGuideBySubjectCodeAndCourseNumber($course_code, $guide_path){
        //Find guides by subject code and course number
        $guides = $this->getCourseURL($course_code);
        $guides_count = count($guides);

        if ($guides_count == 0) { //If nothing found, then find guides by subject code only
            $subject_code = substr($course_code, 0, 3);

            $guides = $this->getCourseURL($subject_code);
            $guides_count = count($guides);
        }

        //Redirect according to the results
        if ($guides_count == 0) {
            header("Location: " . $guide_path . "?no_bb_guide=1"); /* Redirect browser */
        } elseif ($guides_count == 1) {
            header("Location: " . $guide_path . "guide.php?subject=" . $guides[0]['shortform']); /* Redirect browser */
        } else {
            $results = array();
            foreach ($guides as $guide) {
                $results[$guide['subject']] = $guide_path . "guide.php?subject=" . $guide['shortform'];
            }
            include('lti_view/multiple_guides_view.php');
        }
    }
}