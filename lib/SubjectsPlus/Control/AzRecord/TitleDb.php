<?php
/**
 *   @file TitleDb.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Feb 2016
 *   @todo
 */


namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;
use PDO;

class TitleDb
{
    private $last_insert;

    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();

    }

    public function insertTitle(Title $title) {

        try {
            $title_param =  $title->getTitle();
            $alt_title =    $title->getAlternateTitle();
            $description =  $title->getDescription();
            $pre =          $title->getPre();
            $lastMod =      $title->getLastModifiedBy();

            $this->connection->beginTransaction();
            $statement = $this->connection->prepare("INSERT INTO title (title, alternate_title, description, pre, last_modified_by) VALUES (:title, :alternate_title, :description, :pre, :last_modified_by)");
            $statement->bindParam(':title', $title_param);
            $statement->bindParam(':alternate_title', $alt_title);
            $statement->bindParam(':description', $description);
            $statement->bindParam(':pre', $pre);
            $statement->bindParam(':last_modified_by', $lastMod);
            $statement->execute();
            $this->last_insert = $this->connection->lastInsertId();
            $this->connection->commit();

            $subjects = $title->getSubjects();
            if (isset($subjects)) {
                $this->connection->beginTransaction();
                foreach ($subjects as $subject) {
                    $statement = $this->connection->prepare("INSERT INTO rank (title_id,subject_id,source_id, rank) VALUES (:title_id,:subject_id, 3, 1)");
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

        $title_id = $title->getTitleId();
        $title_param = $title->getTitle();
        $alt_title = $title->getAlternateTitle();
        $description = $title->getDescription();
        $pre = $title->getPre();
        $lastMod = $title->getLastModifiedBy();

        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        $this->connection->beginTransaction();
        $statement = $this->connection->prepare("UPDATE title SET title= :title, alternate_title = :alternate_title, description=:description, pre=:pre,
        last_modified_by=:last_modified_by WHERE title_id = :title_id");
        $statement->bindParam(':title_id', $title_id);
        $statement->bindParam(':title', $title_param);
        $statement->bindParam(':alternate_title', $alt_title);
        $statement->bindParam(':description', $description);
        $statement->bindParam(':pre', $pre);
        $statement->bindParam(':last_modified_by', $lastMod);
        $statement->execute();
        $this->connection->commit();
    }


}
