<?php
class Logger { 

	public function importLog($log_text) {

	 
	$formatted_text = "\n\n" . $log_text . "\n\n";
	 
	file_put_contents("../../../import_log.txt", $formatted_text, FILE_APPEND);

}

}