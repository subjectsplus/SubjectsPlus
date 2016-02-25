<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/25/16
 * Time: 12:11 PM
 */

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Interfaces\OutputInterface;
use SubjectsPlus\Control\Querier;

class AzRecordList implements OutputInterface
{
    private $connection;
    private $records;


    public function __construct(Querier $db)
    {
        $this->connection = $db->getConnection();
        $statement = $this->connection->prepare("SELECT title_id FROM title");
        $statement->execute();
        $title_ids = $statement->fetchAll();
        foreach ($title_ids as $title_id) {
            $azrecord = new AzRecord($db);
            $azrecord->getRecord($title_id['title_id']);

            $this->records[] = $azrecord->toArray();


        }
    }

    public function toJSON() {
        return json_encode($this->records);
    }

    public function toArray() {
        return $this->records;
    }


}