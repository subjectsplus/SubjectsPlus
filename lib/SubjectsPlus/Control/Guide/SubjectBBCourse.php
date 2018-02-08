<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 7/05/16
 * Time: 4:11 PM
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Interfaces\OutputInterface;


/**
 * Class SubjectBBCourse
 * @package SubjectsPlus\Control\Guide
 */
class SubjectBBCourse implements OutputInterface
{

    protected $db;
    protected $connection;

    public $response;
    public $guides;

    public function __construct(Querier $db) {
        $this->db = $db;
        $this->connection = $this->db->getConnection();
    }

    function getSubjectCodesDropDownItems() {

        $subs_option_boxes = "";

        $statement = $this->connection->prepare ("SELECT DISTINCT LEFT (UPPER(course_code), 3) FROM bb_course_code ORDER BY course_code");
        $statement->execute();
        $statement = $statement->fetchAll();
        $subs_result = $statement;
        $num_subs = count($subs_result);
        if ($num_subs > 0) {
            $subs_option_boxes = "";

            foreach ($subs_result as $myrow) {
                $subs_name = $myrow[0];

                $subs_option_boxes .= "<option subject-code-id=$subs_name>$subs_name</option>";
            }
        }

        return $subs_option_boxes;
    }

    function getCurrentAssociations() {

        $temp = "";

        $statement = $this->connection->prepare ("SELECT DISTINCT course_code FROM subject where course_code IS NOT NULL AND LENGTH(course_code) = 3");
        $statement->execute();
        $statement = $statement->fetchAll();
        $course_codes = $statement;

        foreach ($course_codes as $course_code){
            $code = $course_code[0];
            $statement = $this->connection->prepare ("SELECT subject_id, subject, shortform FROM subject where course_code IS NOT NULL AND course_code = :code");
            $statement->bindParam ( ":code", $code );
            $statement->execute();
            $statement = $statement->fetchAll();
            $guides = $statement;
            $temp[$code]=$guides;
        }

        $associations = "";

        if (count($temp) > 0){
            $associations .= "<div style='display: none' id='code-editing-list'>
                                <h3>Selected for editing</h3>
                                <dl id='current-selection-editing'></dl>
<hr>
</div>";
            $associations .= "<dl id='current-associations-list'>";
            foreach ($temp as $key => $value){
                $associations .= "<dt data-subject-code='$key' class='subject-code-title'>$key</dt>";
                foreach ($value as $guide_info){
                    $guide_id = $guide_info[0];
                    $guide_title = $guide_info[1];
                    $associations .= "<dd data-subject-code='$key' class='subject-code-guide' data-guide-id='$guide_id'>$guide_title <a class=\"remove-guide-btn\" title=\"Remove Guide from Subject Code\"><i class=\"fa fa-trash\"></i></a></dd>";
                }
            }

            $associations .= "</dl>";
        }
        return $associations;
    }

    function getInstructorsDropDownItems($selected_instructor) {

        $subs_option_boxes = "";
        $statement = $this->connection->prepare ("SELECT DISTINCT instructor FROM bb_course_instructor ORDER BY instructor");
        $statement->execute();
        $statement = $statement->fetchAll();
        $subs_result = $statement;
        $num_subs = count($subs_result);
        if ($num_subs > 0) {
            $subs_option_boxes = "";

            foreach ($subs_result as $myrow) {
                $instructor_name = $myrow[0];
                $selected = "";

                if (!empty($selected_instructor)){
                    if (trim($selected_instructor) == trim($instructor_name)){
                        $selected = "selected=\"selected\"";
                    }
                }

                $subs_option_boxes .= "<option " . $selected . ">" . $instructor_name . "</option>";
            }
        }
        return $subs_option_boxes;
    }

    public function saveChanges($subject_code_id="", $guide_id) {
        $statement = $this->connection->prepare ( "UPDATE subject
                SET course_code = :subject_code_id
                WHERE subject_id = :guide_id"
        );
        $statement->bindParam ( ":subject_code_id", $subject_code_id );
        $statement->bindParam ( ":guide_id", $guide_id );
        $statement->execute();
    }

    public function fetchSubjectCodeGuides($subject_code) {
        $statement = $this->connection->prepare("SELECT subject_id, subject, instructor FROM subject WHERE active = 1 AND course_code = :subject_code");
        $statement->bindParam ( ":subject_code", $subject_code );
        $statement->execute();
        $guides = $statement->fetchAll();
        $this->guides = $guides;
    }


    /**
     * @return array
     */
    public function toArray() {
        return get_object_vars ( $this );
    }

    /**
     * @return string
     */
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }
}