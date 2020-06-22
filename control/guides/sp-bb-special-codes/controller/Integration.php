<?php

use SubjectsPlus\Control\Querier;

class Integration
{
    private $db;
    private $db_connection;

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
is_edited BOOLEAN DEFAULT FALSE,
last_edited_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
last_edited_by_id INT NULL,
FOREIGN KEY (last_edited_by_id) REFERENCES staff(staff_id) ON DELETE SET NULL
)");
        $statement->execute();

    }

    public function getAllCustomCodesList()
    {
        $statement = $this->db_connection->prepare("SELECT * from sp_bb_courses_relation");
        $statement->execute();
        $special_codes = $statement->fetchAll();

        return $special_codes;
    }

    public function getAddCourseCodeTemplateForm()
    {
        $template = "sp-bb-special-codes/views/partials/add-course-code-form.php";
        $labels = [
            'uniqueCodeLabel' => _("Unique Special Code"),
            'description' => _("Description"),
            'addSpecialCourseCode' => _("Add new Special Code")
        ];
        return $this->renderTemplate($template, $labels);
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

    private function renderTemplate($template, $arguments){
        ob_start();
        foreach ($arguments as $key => $value) {
            ${$key} = $value;
        }
        include $template;
        return ob_get_clean();
    }

}
