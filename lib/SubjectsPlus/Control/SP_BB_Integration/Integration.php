<?php
namespace SubjectsPlus\Control\SP_BB_Integration;

use SubjectsPlus\Control\Querier;

class Integration
{
    private $db;

    /**
     * SP_BB_Integration constructor.
     * @param $db
     */
    public function __construct(Querier $db)
    {
        $this->db = $db;
        $this->createTableIfNotExist();
    }

    private function createTableIfNotExist()
    {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CREATE TABLE IF NOT EXISTS sp_bb_courses_relation (
id INT AUTO_INCREMENT PRIMARY KEY,
custom_code MEDIUMTEXT  NOT NULL,
associated_course_codes MEDIUMTEXT NOT NULL,
is_edited BOOLEAN DEFAULT FALSE,
last_edited_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
last_edited_by_id INT NOT NULL,
FOREIGN KEY (last_edited_by_id) REFERENCES staff(staff_id)
)");
        $statement->execute();

    }

}
