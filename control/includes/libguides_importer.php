<?php
header("Content-Type: text/plain");

include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');

use SubjectsPlus\Control\Querier;

$db = new Querier;

$libguides_xml= new SimpleXMLElement(file_get_contents('libguides.xml','r'));

$subject_values = zip($libguides_xml->xpath('//GUIDE/NAME'), $libguides_xml->xpath('//GUIDE/GUIDE_ID'), $libguides_xml->xpath('//GUIDE/LAST_UPDATE'), $libguides_xml->xpath('//GUIDE/DESCRIPTION'));

foreach($subject_values as $name) { 

  if($db->exec("INSERT INTO subject (subject, subject_id, last_modified, description) VALUES ('$name[0]', '$name[1]', '$name[2]', '$name[3]')")) {

    echo "Inserted '$name[0]' \n";

  } else {
    echo "Problem inserting '$name[0]'. This subject guide may already exist in the database. \n";
  }

}










