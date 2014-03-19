<?php

namespace SubjectsPlus\Control;
/**
 *   @file
 *   @brief
 *
 *   @author rgilmour, adarby, jlittle
 *   @date
 *   @todo fix getQuery()
 */

use PDO;
    
class Querier {
    
	private $_query;
    private $_connection;
    
        function __construct() {
        // This creates the connection by reading the credentials from a db.ini file
        $config = parse_ini_file(dirname(dirname(dirname(__DIR__))) . "/control/includes/db.ini", true);
        
        $dsn = 'mysql:dbname='. $config['database']['dbname'] . ';host=' . $config['database']['host'] . ';port=' . $config['database']['port'] . ';charset=' .$config['database']['charset'];
            
        $username = $config['database']['username'];
        $password = $config['database']['password'];
         
        try {
        $this->_connection = new PDO($dsn, $username, $password, array(
                       PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
           
         
            echo 'Connection failed: ' . $e->getMessage();
            
        }
        
    }
    
    
    public function query($sql, $fetch_style=NULL) {
        // Fetch styles: http://www.php.net/manual/en/pdostatement.fetch.php
        // Example:

        /*
         $q = new Querier;
         $rows = $q->query('SELECT * FROM department');
         print_r($rows);
         */
        
        // Default is numbered array
        if ($fetch_style === NULL ){
            $fetch_style = PDO::FETCH_NUM;
        }
        
        $connection = $this->_connection;
        $result = $connection->query($sql);
        $rows = $result->fetchAll($fetch_style);
        
        return $rows;
        
    }
    
    public function num_rows($sql) {
        $connection = $this->_connection;
        $result = $connection->query($sql);
        return count($result);
        
    }
    
    
    public function insert() {
        
    }
    
    public function update() {
        
    }
    
    public function delete() {
        
    }
    
    public function
    
    
    
    // Ye olde Querier
    ///////////////////////////////////////////////////////
	
    public function getResult($query, $boolAssoc = false) {
		$this->_query = $query;
		$resultArray = array();
		$result  = mysql_query($query);
		if ($result) {

			if($boolAssoc)
			{
				while ($row = mysql_fetch_assoc($result)) {
					$resultArray[] = $row;
				}
			}else
			{
				while ($row = mysql_fetch_array($result)) {
					$resultArray[] = $row;
				}
			}

			if (mysql_num_rows($result) > 0) {
				return $resultArray;
			} else {
				return FALSE;
			}
		} else {
			throw new \Exception('Query failed: ' . mysql_error() . '\n');
		}
    }
    
    
    public function insertQuery($query) {
        $this->_query = $query;
		$resultArray = array();
		$result  = mysql_query($query);
        if ($result) {
            
		} else {
			throw new \Exception('Query failed: ' . mysql_error() . '\n');
		}
	
    }

	public function getQuery() {
		return $this->_query;
	}

}

?>