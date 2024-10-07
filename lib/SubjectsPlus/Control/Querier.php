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
        // This creates the connection by reading the config.php

    	global $hname;
    	global $uname;
    	global $pword;
    	global $dbName_SPlus;
        global $db_port;
        global $db_cert_path;

	    if( isset($db_port) ) {

	    } else {
	      $db_port = "3306";
	    }

	    $dsn = 'mysql:dbname=' . $dbName_SPlus . ';host=' . $hname . ';port=' . $db_port . ';charset=utf8';

	    if( isset($db_cert_path) && $db_cert_path != null ) {
		    $options = array(
			    PDO::ATTR_PERSISTENT => true,
			    PDO::MYSQL_ATTR_SSL_CA => $db_cert_path,
		    );
	    } else {
		    $options = array(
			    PDO::ATTR_PERSISTENT => true,
		    );

	    }

	    try {
		    $this->_connection = new PDO($dsn, $uname, $pword, $options);

            // Disable emulated prepared statements
            // https://www.php.net/manual/en/pdo.setattribute.php
            // Whether enable or disable emulation of prepared statements.
            // Some drivers do not support prepared statements natively or have limited support for them.
            // If set to true PDO will always emulate prepared statements,
            // otherwise PDO will attempt to use native prepared statements.
            // In case the driver cannot successfully prepare the current query,
            // PDO will always fall back to emulating the prepared statement.

            $this->_connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	    } catch (\PDOException $e) {
		    echo "<h1>There was a problem connecting to the database.</h1>";
		    echo "<p>Are you sure that the database connection information is correct?</p>";
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
 	       exit;
          //  echo "<p><h2>Woah! There was a problem with that query.</h2> Maybe this will help: ";
          //  print_r($connection->errorInfo());
          //  echo "</p>";
          //  echo $sql;

            $rows = NULL;
        } else {

           $rows = $result->fetchAll($fetch_style);

        }

        return $rows;
    }

	public function prepareStatement ($sql, $params) {
		$connection = $this->_connection;


		$statement = $connection->prepare($sql);
		foreach ($params as $param=>$value){

			$statement->bindParam($param, $value);
		}
		return $statement;
	}


	public function queryWithPreparedStatement($sql, $fetch_style = NULL, $params) {

		// Default is numbered array
		if ($fetch_style === NULL) {
			$fetch_style = PDO::FETCH_BOTH;
		}

		$sql = $this->prepareStatement($sql, $params);
		$sql->execute();

		if (!$sql) {
			exit;
			//  echo "<p><h2>Woah! There was a problem with that query.</h2> Maybe this will help: ";
			//  print_r($connection->errorInfo());
			//  echo "</p>";
			//  echo $sql;

			$rows = NULL;
		} else {
			$rows = $sql->fetchAll($fetch_style);
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

	public function getConnection() {
		$connection = $this->_connection;
		return $connection;
	}

    public function last_id() {
        $connection = $this->_connection;
        $last_id = $connection->lastInsertId();
        return $last_id;
    }

	public function errorInfo()
	{
		return $this->_connection->errorInfo();
	}

    public function getSingleById($sql, $factory, $id) {
        $statement = $this->_connection->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $result = $statement->fetch();

        return $factory::create($result);

    }
    public function getArrayById($sql, $factory, $id) {
        $objects = array();
        $statement = $this->_connection->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $results = $statement->fetchAll();


        foreach ($results as $result) {
            $objects[] = $factory::create($result);

        }

        return $objects;

    }
}
