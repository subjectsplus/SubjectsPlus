<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/24/16
 * Time: 12:08 PM
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;
use PDO;

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
            $statement->bindParam(':title', $title->getTitle());
            $statement->bindParam(':alternate_title', $title->getAlternateTitle());
            $statement->bindParam(':description', $title->getDescription());
            $statement->bindParam(':pre', $title->getPre());
            $statement->bindParam(':last_modified_by', $title->getLastModifiedBy());
            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            $subjects = $title->getSubjects();
            if (isset($subjects)) {
                $this->connection->beginTransaction();
                foreach ($subjects as $subject) {
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

    public function updateTitle(Title $title) {
        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        $this->connection->beginTransaction();
        $statement = $this->connection->prepare("UPDATE title SET title= :title, alternate_title = :alternate_title, description=:description, pre=:pre,
        last_modified_by=:last_modified_by WHERE title_id = :title_id");
        $statement->bindParam(':title_id', $title->getTitleId());
        $statement->bindParam(':title', $title->getTitle());
        $statement->bindParam(':alternate_title', $title->getAlternateTitle());
        $statement->bindParam(':description', $title->getDescription());
        $statement->bindParam(':pre', $title->getPre());
        $statement->bindParam(':last_modified_by', $title->getLastModifiedBy());
        $statement->execute();
        $this->connection->commit();
    }


}