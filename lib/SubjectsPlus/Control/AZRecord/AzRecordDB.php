<?php
/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 2/24/16
 * Time: 10:15 AM
 */

namespace SubjectsPlus\Control\AzRecord;


class AzRecordDb
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;

    }

    public function getSingleById($sql, $factory, $id) {
      $statement = $this->connection->prepare($sql);
      $statement->bindParam(':id', $id);
      $statement->execute();
      $result = $statement->fetch();

      return $factory::create($result);

  }
    public function getArrayById($sql, $factory, $id) {
        $objects = [];
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $results = $statement->fetchAll();


        foreach ($results as $result) {
            $objects[] = $factory::create($result);

        }

        return $objects;

    }

}