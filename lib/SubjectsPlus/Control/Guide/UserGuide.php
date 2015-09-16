<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 9/15/15
 * Time: 5:05 PM
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;

use SubjectsPlus\Control\Interfaces\OutputInterface;

class UserGuide implements OutputInterface
{


    private $db;
    private $guides = array();

    public function __construct(Querier $db) {

        $this->db = new Querier;
    }

    public function loadUserGuides($staff_id) {
        $guides = array();

        // Get all guides associated with the staffId
        $this->staff_id = $staff_id;
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT subject.subject_id, subject, subject.active
                                            FROM `subject`, staff_subject, staff
                                            WHERE staff.staff_id = staff_subject.staff_id
                                            AND staff_subject.subject_id = subject.subject_id
                                            AND staff.staff_id = :staff_id
                                            ORDER BY subject");
        $statement->bindParam(":staff_id", $this->staff_id);
        $statement->execute();
        $guides = $statement->fetchAll();

        $this->guides = $guides;
    }


    public function toArray() {
        return get_object_vars ( $this );
    }
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }

}