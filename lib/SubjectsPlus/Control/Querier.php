<?php

namespace SubjectsPlus\Control;

/**
 *   @file Querier.php
 *   @brief A class for database access
 *
 *   @author jlittle
 *   @date April, 2014
 *   @todo
 */
    
// Fetch styles: http://www.php.net/manual/en/pdostatement.fetch.php
// Example:
    
/*
     
     $q = new Querier;
     $rows = $q->query('SELECT * FROM department');
     print_r($rows);
     
     foreach ($rows as $value) {
     
     echo $value['name'];
     
     }
     
*/

use PDO;

class Querier  {

    private $_query;
    private $_connection;

    function __construct() {
        // This creates the connection by reading the credentials from a db.ini file
        $config = parse_ini_file(dirname(dirname(dirname(__DIR__))) . "/control/includes/db.ini", true);

        $dsn = 'mysql:dbname=' . $config['database']['dbname'] . ';host=' . $config['database']['host'] . ';port=' . $config['database']['port'] . ';charset=' . $config['database']['charset'];

        $username = $config['database']['username'];
        $password = $config['database']['password'];

        try {
            $this->_connection = new PDO($dsn, $username, $password, array(PDO::ATTR_PERSISTENT => true));
        } catch (\PDOException $e) {

            
            echo "<h1>There was a problem connecting to the database.</h1>";
            echo "<p>Have you set the database credentials in /control/includes/db.ini?</p>";
            echo "<p>This is the detailed error:</p>";
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function query($sql, $fetch_style = NULL) {
        
        // Default is numbered array
        if ($fetch_style === NULL) {
            $fetch_style = PDO::FETCH_BOTH;
        }

        $connection = $this->_connection;
    
           $result = $connection->query($sql);
        if (!$result) {
            
            echo "<p><h2>Woah! There was a problem with that query.</h2> Maybe this will help: ";
            print_r($connection->errorInfo());
            echo "</p>";
            echo $sql;
            
            $rows = NULL;
        } else {
           $rows = $result->fetchAll($fetch_style);
        }
     

        return $rows;
    }
    
    public function exec($sql) {
        $connection = $this->_connection;
        $result = $connection->exec($sql);
        return $result;
        
    }
    
    public function fetch($sql) {
        $connection = $this->_connection;
        $result = $connection->query($sql);
        $fetch_row = $connection->fetchColumn();
        return $fetch_row;
        
    }
 
    public function num_rows($sql) {
        $connection = $this->_connection;
        $result = $connection->query($sql);
        return count($result);
    }

 
    public function quote($string) {
        $connection = $this->_connection;
        $quoted_string = $connection->quote($string);
        return $quoted_string;
        
    }
    
    public function last_id() {
        $connection = $this->_connection;
        $last_id = $connection->lastInsertId();
        return $last_id;
    }
}
?>