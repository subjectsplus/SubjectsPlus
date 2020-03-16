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

include('config.php');
include_once(getcwd() . "/../../lib/SubjectsPlus/Control/Querier.php");
include_once(getcwd() . "/../../lib/SubjectsPlus/Control/Stats/Stats.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Stats\Stats;

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
        global $subjects_theme;
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

            if ($subjects_theme == "med") {
                global $med_course_codes;
                if (!in_array(preg_replace('/[^a-zA-Z]/', '', $course_code), $med_course_codes)) {
                    $line = fgets($file);
                    continue;
                }
            }

            $statement = $this->connection->prepare("INSERT INTO " . $this->course_code_table_name . " (course_code, course_title)
            SELECT * FROM (SELECT '" . $course_code . "', '" . $course_name . "') AS tmp
            WHERE NOT EXISTS (SELECT course_code FROM " . $this->course_code_table_name . " WHERE course_code = '" . $course_code . "')");
            $statement->execute();
            $line = fgets($file);
        }
        fclose($file);
        $this->deleteTempFile($file_path);
    }

    private function deleteTempFile($file)
    {
        unlink($file);
    }

    public function getLatestFileFromServer($file_path = '')
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
        $ignored = array('.', '..', '.svn', '.htaccess', 'instructors', 'old');

        // Get and sort the files
        $files = array();


        foreach (scandir('ssh2.sftp://' . intval($sftp) . $file_path) as $file) {
            if (in_array($file, $ignored) || in_array(pathinfo($file)['extension'], $ignored)) continue;
            array_push($files, 'ssh2.sftp://' . intval($sftp) . $file_path . '/' . $file);
        }

        if (!empty($files)) {
            $last_file = end($files);
            $this->downloadFileFromServer($last_file);
        }

        ssh2_exec($sftp_connection, 'exit');
        unset($sftp_connection);

        return "./temp_files/" . basename($last_file);
    }

    private function downloadFileFromServer($file)
    {

        $file_basename = basename($file);
        $stream = fopen($file, 'r');
        $contents = stream_get_contents($stream);
        file_put_contents("temp_files/$file_basename", $contents);

        // Close our streams
        fclose($stream);

        $log = fopen("log.txt", "a");
        $date = date("Y-m-d H:i:s");
        fwrite($log, $date . " - Updated from " . $file_basename . PHP_EOL);
        fclose($log);
    }

    private function updateBBCourseInstructorTable()
    {
        global $lti_instructors_dir_path;
        global $subjects_theme;
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

            if ($subjects_theme == "med") {
                global $med_course_codes;
                if (!in_array(preg_replace('/[^a-zA-Z]/', '', $course_id), $med_course_codes)) {
                    $line = fgets($file);
                    continue;
                }
            }

            $statement = $this->connection->prepare("INSERT INTO " . $this->course_instructor_table_name . " (course_id, instructor)
            SELECT * FROM (SELECT '" . $course_id . "', '" . $course_instructor . "') AS tmp
            WHERE NOT EXISTS (SELECT course_id FROM " . $this->course_instructor_table_name . " WHERE course_id = '" . $course_id . "')");

            $statement->execute();
            $line = fgets($file);
        }
        fclose($file);
        $this->deleteTempFile($file_path);
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
            $q = "SELECT subject_id, subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND course_code = '" . $course_code . "' AND instructor LIKE '%" . $instructor . "%' ORDER BY subject";
            $statement = $this->connection->prepare($q);
            $statement->execute();
            $result = $statement->fetchAll();

            if (count($result) != 0) {
                return $result;
            }
        }

        $specific_guide_code = $this->checkSpecificCase($subject_code);

        if (!empty($specific_guide_code)) {
            $course_code = $specific_guide_code;
        }

        $q = "SELECT subject_id, subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND course_code = '" . $course_code . "' AND (instructor = 'None' OR instructor IS NULL OR instructor = '' )";

        $statement = $this->connection->prepare($q);
        $statement->execute();
        return $statement->fetchAll();
    }

    private function checkSpecificCase($subject_code)
    {
        global $specific_course_codes;
        if ($specific_course_codes) {
            foreach ($specific_course_codes as $guide_code => $course_codes) {
                if (array_search($subject_code, $course_codes)) {
                    return $guide_code;
                }
            }
            return "";
        }
    }

    private function getInstructorByCourseCode($course_id)
    {
        $q = "SELECT instructor FROM $this->course_instructor_table_name WHERE course_id = '" . $course_id . "'";
        $statement = $this->connection->prepare($q);
        $statement->execute();
        $instructor_temp = $statement->fetchAll();
        if (!empty($instructor_temp)) {
            $instructor_temp = $instructor_temp[0];
            $instructor_temp = $instructor_temp['instructor'];
            $instructor = trim($instructor_temp);
            return $instructor;
        } else {
            return "";
        }
    }

    private function getGuidesByInstructor($instructor)
    {
        $q = "SELECT subject_id, subject, shortform FROM subject WHERE active = '1' AND type != 'Placeholder' AND instructor LIKE '%" . $instructor . "%' ORDER BY subject";
        $statement = $this->connection->prepare($q);
        $statement->execute();
        return $statement->fetchAll();
    }

    function isMedCourseCode($course_code){
        global $med_course_codes;

        if ($med_course_codes){
            return in_array(substr($course_code, 0, 3), $med_course_codes);
        }
    }

    function processCourseCode($course_code, $guide_path)
    {
        //temporary fix to deal with med course guides
        if ($this->isMedCourseCode($course_code)){
            header("Location: https://spmed.library.miami.edu/subjects/index.php"); /* Redirect browser */
            return;
        }

        $instructor = $this->getInstructorByCourseCode($course_code);

        if (!empty($instructor)) {
            $intructor_courses = $this->getGuidesByInstructor($instructor);
            $instructor_courses_count = count($intructor_courses);

            if ($instructor_courses_count == 1) {
                $this->saveStats($course_code, $instructor_courses_count, $intructor_courses[0]['subject_id']);
                header("Location: " . $guide_path . $intructor_courses[0]['shortform']); /* Redirect browser */
            } elseif ($instructor_courses_count > 1) {
                $results = array();
                $research_guide_ids = array();
                foreach ($intructor_courses as $guide) {
                    $results[$guide['subject']] = $guide_path . $guide['shortform'];
                    array_push($research_guide_ids, $guide['subject_id']);
                }
                $this->saveStats($course_code, $instructor_courses_count, implode(",", $research_guide_ids));
                include('lti_view/multiple_guides_view.php');
            } else {
                $this->findGuideBySubjectCodeAndCourseNumber($course_code, $guide_path);
            }
        } else {
            $this->findGuideBySubjectCodeAndCourseNumber($course_code, $guide_path);
        }
    }

    function findGuideBySubjectCodeAndCourseNumber($course_code, $guide_path)
    {
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
            $this->saveStats($course_code, $guides_count, "");
            header("Location: " . $guide_path . "?no_bb_guide=1"); /* Redirect browser */
        } elseif ($guides_count == 1) {
            $this->saveStats($course_code, 1, $guides[0]['subject_id']);
            header("Location: " . $guide_path . "guide.php?subject=" . $guides[0]['shortform']); /* Redirect browser */
        } else {
            $results = array();
            $research_guide_ids = array();
            foreach ($guides as $guide) {
                array_push($research_guide_ids, $guide['subject_id']);
                $results[$guide['subject']] = $guide_path . "guide.php?subject=" . $guide['shortform'];
            }
            $this->saveStats($course_code, $guides_count, implode(",", $research_guide_ids));
            include('lti_view/multiple_guides_view.php');
        }
    }

    function saveStats($course_code, $associations_count, $research_guides_ids)
    {
        $stats = new Stats($this->db);
        $stats->setEventType('lti_hit');

        //For the eventType "lti_hit", the course_code is being stored in the tab_name column
        $stats->setTabName($course_code);

        //For the eventType "lti_hit", the count of associated research guides with the course_code  is being stored in the link_url column
        $stats->setLinkUrl($associations_count);

        //For the eventType "lti_hit", the associated research guides ids with the course_code is being stored in the link_title column
        $stats->setLinkTitle($research_guides_ids);

        if (isset($_SERVER['HTTP_REFERER'])) {
            $stats->setHttpReferer($_SERVER['HTTP_REFERER']);
        } else {
            $stats->setHttpReferer("Referer Unavailable");
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $stats->setRemoteAddress($_SERVER['REMOTE_ADDR']);
        }

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $stats->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        }

        $stats->saveStats();
    }
}
