<?php

use SubjectsPlus\Control\Querier;

class Integration
{
    private $db;
    private $db_connection;
    private $last_function_result;

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
description MEDIUMTEXT NULL
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
            'uniqueCodeLabel' => _("Unique Custom Course Code"),
            'description' => _("Description"),
            'associatedCourseCodesLabel' => _("Asssociated Course Codes"),
            'addSpecialCourseCode' => _("Add new Special Code")
        ];
        return $this->renderTemplate($template, $labels);
    }

    public function lastActionResultToJson()
    {
        return json_encode($this->last_function_result);
    }

    public function insertCustomCourseCode($data)
    {
        $custom_code = scrubData($data["special-course-code"]);
        $associated_course_codes = scrubData($data["associated-course-codes"]);
        $description = scrubData($data["description"]);

        $this->db;
        $statement = $this->db_connection->prepare(
            "INSERT INTO sp_bb_courses_relation (custom_code, associated_course_codes, description) 
VALUES (:custom_code, :associated_course_codes, :description)");

        $statement->bindParam(':custom_code', $custom_code);
        $statement->bindParam(':associated_course_codes', $associated_course_codes);
        $statement->bindParam(':description', $description);
        $this->last_function_result = $statement->execute();;
    }

    public function getEditCourseCodeTemplateForm()
    {
        $template = "sp-bb-special-codes/views/partials/edit-course-code-form.php";
        $labels = [
            'formClassName' => "edit-special-course-code-form",
            'style' => "display: none;",
            'uniqueCodeLabel' => _("Unique Special Code"),
            'description' => _("Description"),
            'saveSpecialCourseCode' => _("Save Special Code Changes")
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
