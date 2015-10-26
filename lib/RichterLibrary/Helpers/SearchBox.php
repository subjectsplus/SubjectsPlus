<?php
/**
 *   @file SearchBox.php
 *   @brief	Outputs a UM Library search box 
 *
 *   @author little9 (Jamie Little)
 *   @date June 2015
 *   
 */

namespace RichterLibrary\Helpers;


class SearchBox {
	function __construct() {
	}

	public function requireToVar($file){
		ob_start();
		require($file);

		return ob_get_clean();
	}

	public function outputBox() {
		$markup = $this->requireToVar('views/um-searchbox.html');
		return $markup;
	}
}