<?php
/**
 *   @file AzListTypes.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control\Databases;

use SubjectsPlus\Control\Interfaces\OutputInterface;
class AzListTypes implements OutputInterface {

	public function toArray() {
		$all_types = array();
		
		global $all_ctags;
		sort ( $all_ctags );

		foreach ($all_ctags as $type ) {
			
			$display_type = str_replace("_", " ", $type);

			array_push($all_types,(object) array("type" => $type, "display_type" => $display_type));
			
			
		}
		
		return $all_types;
		
		
	}
	public function toJSON() {
		return json_encode ( self::outputArray(), JSON_FORCE_OBJECT );
		
	}	
	
}