<?php
/**
 *   @file AzListSubjects.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control\Databases;

class AzListSubjects extends AzList {

	public function toArray() {
		
		
		$connection = $this->connection;
		
		$statement = $connection->prepare ( "SELECT subject, subject_id FROM subject WHERE active = '1' ORDER BY subject" );
		
		$statement->bindParam ( ":qualifer", $letter );
		$statement->execute ();
		$r = $statement->fetchAll ( $this->default_fetch );
	
		return $r;
	}
	
	
	public function toJSON() {
		return json_encode ( self::outputArray() );
		
	}	
	
}