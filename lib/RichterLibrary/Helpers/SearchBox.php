<?php
/*
 * Plugin Name: UM Search Box
* Description: Provides functions to display search box
* Version: 0.1
* Author: Jamie
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