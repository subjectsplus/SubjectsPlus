<?php

/**
 *   @file
 *   @brief
 *
 *   @author rgilmour, adarby
 *   @date
 *   @todo fix getQuery()
 */

class sp_Querier {

	private $_query;

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
			throw new Exception('Query failed: ' . $query . '\n');
		}
	}

	public function getQuery() {
		return $this->_query;
	}

}

?>