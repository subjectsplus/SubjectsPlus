<?php
header("Content-Type: text/plain");

include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');

use SubjectsPlus\Control\Querier;

$db = new Querier;

$libguides_xml= new SimpleXMLElement(file_get_contents('libguides.xml','r'));

$subject_values = zip($libguides_xml->xpath('//GUIDE/NAME'), 
		      $libguides_xml->xpath('//GUIDE/GUIDE_ID'), 
		      $libguides_xml->xpath('//GUIDE/LAST_UPDATE'), 
		      $libguides_xml->xpath('//GUIDE/DESCRIPTION'), 
		      $libguides_xml->xpath('//GUIDE/PAGES'));


foreach($subject_values as $subject) { 

  if($db->exec("INSERT INTO subject (subject, subject_id, last_modified, description) VALUES ('$subject[0]', '$subject[1]', '$subject[2]', '$subject[3]')")) {

    echo "Inserted '$subject[0]' \n";

  } else {

    echo "Problem inserting '$subject[0]'. This subject guide may already exist in the database. \n";
    echo "\tError: ";
    echo  $db->errorInfo()[2] . "\n \n";
  
  }

  $subject_page = $subject[4];

  foreach ($subject_page->PAGE as $tab) {

    if($db->exec("INSERT INTO tab (tab_id, subject_id, label) VALUES ('$tab->PAGE_ID', '$subject[1]', '$tab->NAME')")) {
      
      echo  "Inserted tab '$tab->NAME' \n";

    } else {

      echo "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database. \n";
      
      echo "\tError: ";
      echo  $db->errorInfo()[2] . "\n \n";

    }
    
  }

}








