<?php
/**
 *   @file AzListTrials.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control\Databases;

use SubjectsPlus\Control\Querier;

class AzListTrials extends AzList {
	private $_listing = array ();
	public function outputArrayByType($selected_type) {
		$azlist = self::outputArray ();
	
		foreach ( $azlist as $title ) {
			$types = explode ( "|", $title ['ctags'] );
				
			if (in_array ( $selected_type, $types ))
	
				array_push ( $this->_listing, $title );
		}
	
		return $this->_listing;
	}
	
	
	
	public function toArray() {
		
	$trials = $this->outputArrayByType("Database_Trial");

	return $trials;
	
	}
	
}