<?php
   namespace SubjectsPlus\Control;
/**
 *   @file
 *   @brief make connection to selected database
 *
 *   @author rgilmour, adarby
 *   @date
 *   @todo
 */

class DBConnector {

  private $_hname;
  private $_uname;
  private $_pword;
  private $_dbName;
  private $_connection;

  public function __construct($uname, $pword, $dbName, $hname) {
    $this->_uname = $uname;
    $this->_pword = $pword;
    $this->_dbName = $dbName;
    $this->_hname = $hname;

    if ($this->_connection = mysql_connect($hname, $uname, $pword)) {
      if (!mysql_select_db($dbName)) {
        throw new \Exception('Could not choose database.');
      }

    // Make sure things are unicode-friendly
    $setup_q = "SET NAMES 'utf8'";
    $db->query($setup_q);

      return $this->_connection;
    } else {
      throw new \Exception('Could not connect to database server.');
    }



  }


	public function getConnection()
	{
		return $this->_connection;
	}
}
?>