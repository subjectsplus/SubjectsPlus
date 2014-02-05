<?php 
////// Autoload Classes (Requires PHP >= 5.2 ////////

function includes_autoload($class_name) {
  if ($class_name != "finfo") {
    if (file_exists(__DIR__ . '/classes/' .  $class_name . '.php')) {
      require  $class_name . '.php';
    }
    else {
      
      if (file_exists(__DIR__ . '/pluslets/' .  $class_name .  DIRECTORY_SEPARATOR  . $class_name . '.php')) {
        require  $class_name .  DIRECTORY_SEPARATOR . $class_name . '.php';
      } else {
	
	$className = ltrim($class_name, "\\");
        $fileName  = "";
        $namespace = "";
    if ($lastNsPos = strrpos($className, "\\")) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace("\\", DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    $fileName .= str_replace("_", DIRECTORY_SEPARATOR, $className) . ".php";

			   require $fileName;


			   }
			   }      
			   
			   } // this if is to fix a bug with autoload and the upload.php class
			   }

			   

			   set_include_path(__DIR__ . '/classes/' . PATH_SEPARATOR . __DIR__ . '/pluslets/' . PATH_SEPARATOR .
                                __DIR__ . '/classes/Assetic/Asset/' . PATH_SEPARATOR .  __DIR__ . '/classes/Assetic/Filter/' );
			   //  set_include_path(__DIR__ . '/pluslets/' . $class_name . '/');
			   spl_autoload_register('includes_autoload');

?>