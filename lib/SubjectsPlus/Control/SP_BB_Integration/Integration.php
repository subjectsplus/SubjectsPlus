<?php
namespace SubjectsPlus\Control\SP_BB_Integration;

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

    public function getAllCustomCodesList(){
        $statement = $this->db_connection->prepare("SELECT custom_code from sp_bb_courses_relation");
        $statement->execute();
        return $statement->fetchAll();
    }

}
