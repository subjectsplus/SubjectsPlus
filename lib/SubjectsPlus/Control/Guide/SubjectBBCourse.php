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

    public function saveChanges($subject_code_id="", $guide_id) {
        $statement = $this->connection->prepare ( "UPDATE subject
                SET course_code = :subject_code_id
                WHERE subject_id = :guide_id"
        );
        $statement->bindParam ( ":subject_code_id", $subject_code_id );
        $statement->bindParam ( ":guide_id", $guide_id );
        $statement->execute();
    }

    function insert ($title_id, $subject_id, $description_override){
        $statement = $this->connection->prepare ("INSERT INTO rank (rank, subject_id, title_id, source_id, description_override, dbbysub_active)
VALUES (0,:subject_id,:title_id, 1, :description_override, 1)");
        $statement->bindParam ( ":description_override", $description_override );
        $statement->bindParam ( ":title_id", $title_id );
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->execute();
    }

    function updateDescriptionOverride ($rank_id, $description_override){
        $statement = $this->connection->prepare ( "UPDATE rank
                SET description_override = :description_override, dbbysub_active = 1
                WHERE rank_id = :rank_id"
        );
        $statement->bindParam ( ":description_override", $description_override );
        $statement->bindParam ( ":rank_id", $rank_id );
        $statement->execute();
    }

    public function saveDescriptionOverride ($subject_id, $title_id, $description_override){
        $rank_id = $this->getRankId($subject_id, $title_id);

        if ($rank_id) {
            $statement = $this->connection->prepare ( "UPDATE rank
                SET description_override = :description_override
                WHERE subject_id = :subject_id
                AND title_id = :title_id"
            );
        }else{
            $statement = $this->connection->prepare ("INSERT INTO rank (rank, subject_id, title_id, source_id, description_override, dbbysub_active)
VALUES (0,:subject_id,:title_id, 1, :description_override, 0)");
        }
        $statement->bindParam ( ":description_override", $description_override );
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->bindParam ( ":title_id", $title_id );
        $statement->execute();
    }

    function getRankId($subject_id, $title_id) {
        $statement = $this->connection->prepare("SELECT rank_id FROM rank
                    WHERE subject_id = :subject_id
                    AND title_id = :title_id"
        );
        $statement->bindParam ( ":subject_id", $subject_id );
        $statement->bindParam ( ":title_id", $title_id );
        $statement->execute();
        $statement = $statement->fetchAll();
        return $statement[0]['rank_id'];
    }

    public function fetchSubjectCodeGuides($subject_code) {
        $statement = $this->connection->prepare("SELECT subject_id, subject, instructor FROM subject WHERE active = 1 AND course_code = :subject_code");
        $statement->bindParam ( ":subject_code", $subject_code );
        $statement->execute();
        $guides = $statement->fetchAll();
        $this->guides = $guides;
    }

    public function hideDatabaseFromGuide($rank_id) {
        $statement = $this->connection->prepare("UPDATE rank SET dbbysub_active = 0  WHERE rank_id = :rank_id");
        $statement->bindParam ( ":rank_id", $rank_id );
        $statement->execute();
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