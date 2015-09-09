<?php
class Logger { 

	public function importLog($log_text) {

	 
	$formatted_text = "\n\n" . $this->grab_dump($log_text) . "\n\n";
	 
	file_put_contents("../../../import_log.txt", $formatted_text, FILE_APPEND);

}


public function grab_dump($var)
{
    ob_start();
    var_dump($var);
    return ob_get_clean();
}

}