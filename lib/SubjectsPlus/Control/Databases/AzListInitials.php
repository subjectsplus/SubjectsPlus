<?php

namespace SubjectsPlus\Control\Databases;


class AzListInitials extends AzList {
	public function toArray() {
		$connection = $this->db->getConnection ();
		$statement = $connection->prepare ( "SELECT DISTINCT LEFT(title,1) as initial FROM title WHERE LEFT(title,1)  !=  ' '");
		
		$statement->execute ();
		$r = $statement->fetchAll ( $this->default_fetch );
		
		return $r;
	}
	public function toJSON() {
		return json_encode ( self::outputArray () );
	}
}