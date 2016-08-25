<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 7/15/16
 * Time: 11:07 AM
 */

namespace SubjectsPlus\Control\OAI;
use SubjectsPlus\Control\Querier;


class RecordList
{

    private $connection;
    private $records;

    private $active = 1;
    private $type = 'Subject';


    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();


        $statement = $this->connection->prepare("SELECT subject_id FROM subject WHERE active = :active AND `type` = :type");
        $statement->bindParam(':active', $this->active);
        $statement->bindParam(':type', $this->type);
        $statement->execute();
        $subject_ids = $statement->fetchAll();
        foreach ($subject_ids as $subject_id) {
            $record = new Record($db);
            $record->getRecord($subject_id['subject_id']);

            $this->records[] = $record;


        }
    }


    public function getRecords() {
        return $this->records;
    }


}