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


    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();
        $statement = $this->connection->prepare("SELECT subject_id FROM subject");
        $statement->execute();
        $subject_ids = $statement->fetchAll();
        foreach ($subject_ids as $subject_id) {
            $record = new Record($db);
            $record->getRecord($subject_id['subject_id']);

            $this->records[] = $record->toArray();


        }
    }


    public function toArray() {
        return $this->records;
    }


}