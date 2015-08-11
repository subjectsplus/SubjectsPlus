<?php
/**
 *   @file manage.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control\Databases;

use SubjectsPlus\Control\Querier;

class AzListNew extends AzListBySub implements AzListInterface {
	
	public function toArray() {
		
	$new_databses = self::outputArrayBySub("76");

	return $new_databses;
	
	}
	
}