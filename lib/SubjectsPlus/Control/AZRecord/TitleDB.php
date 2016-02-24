<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/24/16
 * Time: 12:08 PM
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;

class TitleDB
{
    private $last_insert;

    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();

    }

    public function insertTitle(Title $title) {

        try {
            $this->connection->beginTransaction();
            $statement = $this->connection->prepare("INSERT INTO title (title, alternate_title, description, pre, last_modified_by) VALUES (:title, :alternate_title, :description, :pre, :last_modified_by)");
            $statement->bindParam(':title', $title->title);
            $statement->bindParam(':alternate_title', $title->alternate_title);
            $statement->bindParam(':description', $title->description);
            $statement->bindParam(':pre', $title->pre);
            $statement->bindParam(':last_modified_by', $title->last_modified_by);
            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            if (isset($title->subjects)) {
                $this->connection->beginTransaction();
                foreach ($title->subjects as $subject) {
                    $statement = $this->connection->prepare("INSERT INTO rank (title_id,subject_id) VALUES (:title_id,:subject_id)");
                    $statement->bindParam(':subject_id', $subject['subject_id']);
                    $statement->bindParam(':title_id', $this->last_insert);
                    $statement->execute();
                    $this->connection->commit();
                }

            }

        } catch(PDOExecption $e) {
            $this->connection->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }

        return $this->last_insert;
    }
}