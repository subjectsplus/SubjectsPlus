<?php
namespace SubjectsPlus\Control;

class LibGuidesImport {


  public static function load_libguides_xml($lib_guides_xml_path) {


    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));

    // zip combines arrays in fancy way
    // From the python docs: "This function returns a list of tuples, where the i-th tuple contains the i-th element from each of the argument sequences or iterables. The returned list is truncated in length to the length of the shortest argument sequence." 

    $subject_values = zip($libguides_xml->xpath('//GUIDE/NAME'), 
			  $libguides_xml->xpath('//GUIDE/GUIDE_ID'), 
			  $libguides_xml->xpath('//GUIDE/LAST_UPDATE'), 
			  $libguides_xml->xpath('//GUIDE/DESCRIPTION'), 
			  $libguides_xml->xpath('//GUIDE/PAGES'));


    return $subject_values; 

  }


  public static function import_libguides($subject_values) {

    $db = new Querier;

    foreach($subject_values as $subject) { 

      // Remove the apostrophes and spaces from the shortform 

      $shortform = preg_replace('/\s+/','_', str_replace("'", "", $subject[0] ));
      
      // Escape the apostrophes in the guide name 

      $guide_name = str_replace("'", "''",$subject[0]);

      if($db->exec("INSERT INTO subject (subject, subject_id, shortform, last_modified, description) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[2]', '$subject[3]')")) {

	echo "Inserted '$subject[0]' \n";

      } else {

	echo "Problem inserting '$subject[0]'. This subject guide may already exist in the database." . "\n";
	echo colorize("Error:", "FAILURE");
	echo  $db->errorInfo()[2] . "\n \n";
	
      }

      $subject_page = $subject[4];

      foreach ($subject_page->PAGE as $tab) {

	if($db->exec("INSERT INTO tab (tab_id, subject_id, label) VALUES ('$tab->PAGE_ID', '$subject[1]', '$tab->NAME')")) {
	  
	  echo  colorize("Inserted tab '$tab->NAME'", "SUCCESS") ."\n";

	} else {

	  echo "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database.". "\n";
	  
	  echo colorize("Error:", "FAILURE");
	  echo "\t";
	  echo  $db->errorInfo()[2] . "\n \n";

	}
	
      }

    }


  }


}
