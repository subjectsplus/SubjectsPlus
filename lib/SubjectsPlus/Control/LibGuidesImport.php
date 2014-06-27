<?php
namespace SubjectsPlus\Control;

class LibGuidesImport {

  public static function load_libguides_xml($lib_guides_xml_path) {
    $section_index = 0;

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




   public static function load_libguides_links_xml($lib_guides_xml_path) {
   

    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));

    // zip combines arrays in fancy way
    // From the python docs: "This function returns a list of tuples, where the i-th tuple contains the i-th element from each of the argument sequences or iterables. The returned list is truncated in length to the length of the shortest argument sequence." 

  //  print_r( $libguides_xml);
   
    $link_values = $libguides_xml->xpath('//GUIDE//LINKS/LINK');
    

    $db = new Querier;

    
    

foreach (  $link_values as $link ) {

    if ($db->exec("INSERT INTO location (location, format, restrictions) VALUES (" . $db->quote($link->URL) . " , 1, 1)")  ) {
    
    echo "Inserted location \n";
    $location_id = $db->last_id(); 
    echo "\n";
   
    } else {

      
      echo colorize("Error inserting location:", "FAILURE");
    echo  $db->errorInfo()[2] . "\n ";
    } 

  if( $db->exec("INSERT INTO title (title) VALUES (" . $db->quote($link->NAME) . ")") ) {
    echo "Inserted title  \n"; 
    $title_id = $db->last_id();

    echo "\n";
  } else {
     echo colorize("Error inserting title:", "FAILURE");
    echo  $db->errorInfo()[2] . "\n ";
  }

  if( $db->exec("INSERT INTO location_title (title_id, , location_id) VALUES ($title_id, $location_id )") ) {
    echo "Inserted location_title  \n"; 
 
    echo "\n";
  } else {
    echo colorize("Error inserting location_title:", "FAILURE");
    echo  $db->errorInfo()[2] . "\n ";

    echo "INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id)";
  }




}
 //   print_r($link_values);
    

   

  }

  
  public static function import_libguides_links($libguides_links) {



    $db = new Querier;
    
    foreach($libguides_links as $link) {
      
      print_r($link);
    
    }

  }

  public static function import_libguides($subject_values) {

    $db = new Querier;

    foreach($subject_values as $subject) { 

      // Remove the apostrophes and spaces from the shortform 

      $shortform = preg_replace('/\s+/','_', str_replace("'", "", $subject[0] ));
      
      // Escape the apostrophes in the guide name 

      $guide_name = str_replace("'", "''",$subject[0]);
      
      if ($subject[0] != null) {
        
        if($db->exec("INSERT INTO subject (subject, subject_id, shortform, last_modified, description) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[2]', '$subject[3]')")) {

          echo "Inserted '$subject[0]' \n";

        } else {
          print_r ($subject[0]);
          echo colorize("Error inserting subject:", "FAILURE");
          echo  $db->errorInfo()[2] . "\n ";
	  
        }

      }
      
      else {
        
      }




      $subject_page = $subject[4];

      $tab_index = 0; 
      
      
      
      foreach ($subject_page->PAGE as $tab) {


        // LibGuide's pages are tabs so make a new tab

        
        $tab_index++; 
        
        $clean_tab_name = $db->quote($tab->NAME);
        
	if($db->exec("INSERT INTO tab (tab_id, subject_id, label, tab_index) VALUES ('$tab->PAGE_ID', '$subject[1]', $clean_tab_name, $tab_index)")) {
	  
	  echo  colorize("Inserted tab '$tab->NAME'", "SUCCESS") ."\n";

	} else {

          echo "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database.". "\n";
	  
          echo "\t";
	  echo colorize("Error inserting tab:", "FAILURE");
	  echo  $db->errorInfo()[2] . "\n ";
          
          /*
          echo "\n";
          echo "INSERT INTO tab (tab_id, subject_id, label, tab_index) VALUES ('$tab->PAGE_ID', '$subject[1]', $clean_tab_name, $tab_index)";
          echo "\n";
           */


	}
        $row = 0;
        $column = 0;
        $section_index = null;
        foreach ($tab->BOXES as $section) {

          // LibGuide's box parents into sections
          
          $section_uniqid = $section_index . rand();
          
          $section_index++;
        

          if($db->exec("INSERT INTO section (tab_id, section_id, section_index) VALUES ('$tab->PAGE_ID', $section_uniqid ,   $section_index)")) {
            echo colorize("Inserted section", "SUCCESS") . "\n";
          } else { 
            echo "Problem inserting this section. This section  may already exist in the database." . "\n";
            echo "\t";
	    echo colorize("Error inserting section:", "FAILURE");
	    echo  $db->errorInfo()[2] . "\n \n";
            
          }
          
        }

        foreach ($tab->BOXES->BOX as $pluslet) {
          // This imports each LibGuide's boxes as pluslets 
          $description = null; 

          foreach ( $pluslet->LINKS->LINK as $link )  {
            
            
            $description .= 
            "<div class=\"links\">" . 
                            "<a href=\"$link->URL\">$link->NAME</a>" . 
                            "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                            "</div>";
            
            
          }

          
          foreach ( $pluslet->BOOKS->BOOK as $book )  {
            // echo "BOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOK!";
            
            $description .= 
            "<div class=\"books\">" . 
                            "<a href=\"$book->URL\">$book->TITLE</a>" . 
                            "<div class=\"book-description\">$link->DESCRIPTION</div>" .
                            "</div>";
            
            
          }
          
          //  print_r($pluslet);

          $description .= "<div class=\"description\">$pluslet->DESCRIPTION</div>"; 
          $description .= "<div class=\"media\">" . $pluslet->EMBEDDED_MEDIA_AND_WIDGETS->URL . "</div>";  
        
          

          $clean_description = $db->quote($description);

          if($db->exec("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($pluslet->BOX_ID, '$pluslet->NAME', $clean_description, 'Basic')")) {
            echo colorize("Inserted pluslet '$pluslet->NAME'", "SUCCESS") ."\n";
            $clean_description = null;

          } else {

	    //   echo "Problem inserting this pluslet. This pluslet may already exist in the database." . "\n";
            echo "\t";
            echo "\n";
	    echo colorize("Error inserting pluslet:", "FAILURE");
	    echo  $db->errorInfo()[2] . "\n ";

            echo "\n";

            //echo "INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($pluslet->BOX_ID,'$pluslet->NAME', $clean_description , 'Basic')";
          }

          
          if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$pluslet->BOX_ID', '$section_uniqid', $column, $row)")) {
            echo colorize("Inserted pluslet section relationship" , "SUCCESS") ."\n";
            // This sticks the newly created pluslet into a section 
          } else {

	    
	    echo colorize("Error inserting pluslet_section:", "FAILURE");
	    echo  $db->errorInfo()[2] . "\n ";

          }

        }


        
        
      }
      
    }

  }

}
