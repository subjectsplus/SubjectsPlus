<?php
/**
 *   @file LibGuidesImport.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date June 2014
 */
namespace SubjectsPlus\Control;

class Logger {
	public function importLog($log_text = "") {
	
		$formatted_text = "<p>" . json_encode($log_text) . "</p>";
		
		$log_directory = getControlPath () . "logs/";
		$log_file = $log_directory . "import_log.html";
		
		$f = file_put_contents ( $log_file, $formatted_text, FILE_APPEND );
		
		if ($f) {
		}
	}
}