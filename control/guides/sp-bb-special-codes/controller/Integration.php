<?php

use SubjectsPlus\Control\Querier;

class Integration
{
    private $db;
    private $db_connection;
    private $last_execution_result;

    /**
     * SP_BB_Integration constructor.
     * @param $db
     */
    public function __construct(Querier $db)
    {
        $this->db = $db;
        $this->db_connection = $this->db->getConnection();
        $this->createTableIfNotExist();
    }

    private function createTableIfNotExist()
    {
        $statement = $this->db_connection->prepare("CREATE TABLE IF NOT EXISTS sp_bb_courses_relation (
id INT AUTO_INCREMENT PRIMARY KEY,
custom_code MEDIUMTEXT  NOT NULL,
associated_course_codes MEDIUMTEXT NOT NULL,
description MEDIUMTEXT NULL,
UNIQUE KEY unique_custom_code (custom_code(255))

)");
        $statement->execute();

    }

    public function getAllCustomCodesList()
    {
        $statement = $this->db_connection->prepare("SELECT * from sp_bb_courses_relation");
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getAddCourseCodeTemplateForm()
    {
        $template = "sp-bb-special-codes/views/partials/add-course-code-form.php";
        $labels = [
            'uniqueCodeLabel' => _("Unique Custom Course Code (*)"),
            'description' => _("Short description"),
            'associatedCourseCodesLabel' => _("Asssociated Course Codes (*)"),
            'addSpecialCourseCode' => _("Add new Special Code")
        ];
        return $this->renderTemplate($template, $labels);
    }

    public function lastExecutionResultToJson()
    {
        return json_encode($this->last_execution_result);
    }

    public function insertCustomCourseCode($data)
    {
        $custom_code = scrubData($data["special-course-code"]);
        $associated_course_codes = scrubData($data["associated-course-codes"]);
        $description = scrubData($data["description"]);

        $statement = $this->db_connection->prepare(
            "INSERT INTO sp_bb_courses_relation (custom_code, associated_course_codes, description) 
VALUES (:custom_code, :associated_course_codes, :description)");

        $statement->bindParam(':custom_code', $custom_code);
        $statement->bindParam(':associated_course_codes', $associated_course_codes);
        $statement->bindParam(':description', $description);

        $execution_result = $statement->execute();

        if ($execution_result) {
            $data['id'] = $this->db->last_id();
            $result['data'] = $data;
        }else{
            $result['errorCode'] = $statement->errorCode();
            $result['nonUniqueCourseCode'] = $custom_code;
        }

        $result['result'] = $execution_result;
        $this->last_execution_result = $result;
    }

    public function editCustomCourseCode($data)
    {
        $custom_code_id = scrubData($data["editing-course-code-id"]);
        $custom_code = scrubData($data["special-course-code"]);
        $associated_course_codes = scrubData($data["associated-course-codes"]);
        $description = scrubData($data["description"]);

        $statement = $this->db_connection->prepare(
            "UPDATE sp_bb_courses_relation SET custom_code = :custom_code, associated_course_codes = :associated_course_codes, description = :description
WHERE id = :custom_code_id");

        $statement->bindParam(':custom_code', $custom_code);
        $statement->bindParam(':associated_course_codes', $associated_course_codes);
        $statement->bindParam(':description', $description);
        $statement->bindParam(':custom_code_id', $custom_code_id);

        $execution_result = $statement->execute();

        if ($execution_result) {
            $data['id'] = $custom_code_id;
            $result['data'] = $data;
        }else{
            $result['errorCode'] = $statement->errorCode();
            $result['nonUniqueCourseCode'] = $custom_code;
        }

        $result['result'] = $execution_result;
        $this->last_execution_result = $result;
    }

    public function removeCustomCourseCode($data)
    {
        $custom_code_id = scrubData($data["removing-course-code-id"]);

        $statement = $this->db_connection->prepare(
            "DELETE FROM sp_bb_courses_relation WHERE id = :custom_code_id");

        $statement->bindParam(':custom_code_id', $custom_code_id);

        $execution_result = $statement->execute();

        if ($execution_result) {
            $result['data'] = $custom_code_id;
        }

        $result['result'] = $execution_result;
        $this->last_execution_result = $result;
    }

    public function getEditCourseCodeTemplateForm()
    {
        $template = "sp-bb-special-codes/views/partials/edit-course-code-form.php";
        $labels = [
            'formClassName' => "edit-special-course-code-form",
            'style' => "display: none;",
            'saveSpecialCourseCode' => _("Save Special Code Changes"),
            'uniqueCodeLabel' => _("Unique Custom Course Code (*)"),
            'description' => _("Short description"),
            'associatedCourseCodesLabel' => _("Asssociated Course Codes (*)"),
        ];
        return $this->renderTemplate($template, $labels);
    }

    public function getSpecialCourseCodesList()
    {
        $template = "sp-bb-special-codes/views/partials/course-code-list.php";
        $special_courses = $this->getAllCustomCodesList();

        $args = [
            'special_courses' => $special_courses
        ];
        return $this->renderTemplate($template, $args);
    }

    private function renderTemplate($template, $arguments)
    {
        ob_start();
        foreach ($arguments as $key => $value) {
            ${$key} = $value;
        }
        include $template;
        return ob_get_clean();
    }

}
