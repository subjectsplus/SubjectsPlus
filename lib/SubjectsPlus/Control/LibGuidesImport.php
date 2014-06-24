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
			  $libguides_xml->xpath('//GUIDE/PAGES'),
                          $libguides_xml->xpath('//GUIDE/PAGES//LINKS'));

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

        echo  $db->errorInfo()[2] . "\n ";
	
      }

      $subject_page = $subject[4];

      $tab_index = 0; 
      $section_index = 0;
      $row = 0;
      $column = 0;
      foreach ($subject_page->PAGE as $tab) {


        // LibGuide's pages are tabs so make a new tab

        
        $tab_index++; 
        
        
	if($db->exec("INSERT INTO tab (tab_id, subject_id, label, tab_index) VALUES ('$tab->PAGE_ID', '$subject[1]', '$tab->NAME', $tab_index)")) {
	  
	  echo  colorize("Inserted tab '$tab->NAME'", "SUCCESS") ."\n";

	} else {

          echo "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database.". "\n";
	  
          echo "\t";
	  echo colorize("Error:", "FAILURE");
	  echo  $db->errorInfo()[2] . "\n ";

	}

        foreach ($tab->BOXES as $section) {
          
          // LibGuide's box parents into sections
          
          $section_uniqid = null;
          $section_uniqid = rand();

          $section_index++;
          

          if($db->exec("INSERT INTO section (tab_id, section_id, section_index) VALUES ('$tab->PAGE_ID', $section_uniqid ,   $section_index)")) {
            
          } else { 
            echo "Problem inserting this section. This section  may already exist in the database." . "\n";
            echo "\t";
	    echo colorize("Error:", "FAILURE");
	    echo  $db->errorInfo()[2] . "\n \n";
            
          }
          
        }

        foreach ($tab->BOXES->BOX as $pluslet) {
          // This imports each LibGuide's boxes as pluslets 
          $description = null; 

          foreach ( $pluslet->LINKS->LINK as $link )  {
            
           
            $description .= "<div class=\"description\">$link->DESCRIPTION_SHORT</div>" . 
                           "<div class=\"links\">" . 
                           "<a href=\"$link->URL\">$link->NAME</a>" . 
                           "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                            "<div class=\"media\">$pluslet->EMBEDDED_MEDIA_AND_WIDGETS->URL</div>" .
                           "</div>";
            echo($pluslet->description); 
            
          }

          if ($description == null) {
         
          } else { 

          }
          
          $description .= "<div class=\"description\">$pluslet_description->DESCRIPTION_SHORT</div><div class=\"media\"></div>"; 
          
        }


          $clean_description = $db->quote($description);

          if($db->exec("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($pluslet->BOX_ID, '$pluslet->NAME', $clean_description, 'Basic')")) {

            $clean_description = null;

          } else {

	 //   echo "Problem inserting this pluslet. This pluslet may already exist in the database." . "\n";
            echo "\t";
            echo "\n";
	    echo colorize("Error:", "FAILURE");
	    echo  $db->errorInfo()[2] . "\n ";

            echo "\n";

            echo "INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($pluslet->BOX_ID,'$pluslet->NAME', $clean_description , 'Basic')";
          }

          
          if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$pluslet->BOX_ID', '$section_uniqid', $column, 1)")) {
            // This sticks the newly created pluslet into a section 
          } else {

	    echo "Problem inserting pluslet_section. This pluslet section relationship may already exist in the database." . "\n";
            echo "\t";
	    echo colorize("Error:", "FAILURE");
	    echo  $db->errorInfo()[2] . "\n ";

          }
          
        }
        
      }

    }

}
