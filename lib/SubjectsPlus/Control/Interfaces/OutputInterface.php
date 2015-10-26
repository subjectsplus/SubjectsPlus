<?php
/**
 *   @file OutputInterface.php
 *   @brief A simple interface that requires two methods that output an array and json.
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */

namespace SubjectsPlus\Control\Interfaces;

interface OutputInterface {
	
	public function toArray();
	public function toJSON();
	
}