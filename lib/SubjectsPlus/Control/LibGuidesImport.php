<?php
namespace SubjectsPlus\Control;

class LibGuidesImport {

  private $_guide_id;
  private $_libguides_xml; 
  private $_guide_owner;
  

  public function setGuideOwner($guide_owner) {
    $this->_guide_owner = $guide_owner; 
  }
  public function getGuideOwner() {

    return $this->_guide_owner; 
  }

  public function setLibGuidesXML($libguides_xml) {
    $this->_libguides_xml = $libguides_xml; 
  }

  public function getLibGuidesXML() {
    $this->_libguides_xml;
  }
  
  public function setGuideID($guide_id) {
    $this->_guide_id = $guide_id;     
  }
  
  public function getGuideID() {

    return $this->_guide_id; 
  }


  public function getStaffID ($email_address) {

    // This method takes an email address and returns the subjectsplus staff id for the user 

    $db = new Querier; 
    $staff_id = $db->query("SELECT staff_id FROM staff WHERE email = '$email_address'"); 
    
    return $staff_id[0][0];
    
  }

  public function output_guides($lib_guides_xml_path) {
    
    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
    $guide_names = $libguides_xml->xpath("/LIBGUIDES[1]/GUIDES[1]/GUIDE/NAME");
    
    $id_count = 1; 
 

    echo "<select class=\"guides\" >"; 
    
    foreach ($guide_names as $guide) {
  
     echo "<option value=\"$id_count\">$guide[0]</option>";
     $id_count++;

    }
    
    echo "<select>"; 

  }


  public function guide_imported() {
    
    $guide_id = $this->getGuideID();

    $db = new Querier; 
    
    $guide = $db->query("SELECT COUNT(*) FROM subject WHERE subject_id = '$guide_id'");
    
    return $guide;     
  
  }
  
  public function load_libguides_xml($lib_guides_xml_path) {


    $section_index = 0;

    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));

    // zip combines arrays in fancy way
    // From the python docs: "This function returns a list of tuples, where the i-th tuple contains the i-th element from each of the argument sequences or iterables. The returned list is truncated in length to the length of the shortest argument sequence." 

    $guide_id = $this->getGuideID();

    //Get the guide owner's email address   
    $guide_owner_id = $libguides_xml->xpath("//GUIDE[$guide_id]/OWNER_ACCOUNT_ID");
    $guide_owner_email = $libguides_xml->xpath("//ACCOUNT_ID[.=\"$guide_owner_id[0]\"]/following-sibling::EMAIL");
    $this->setGuideOwner($guide_owner_email[0]); 


    $subject_values = zip($libguides_xml->xpath("//GUIDE[$guide_id]/NAME"), 
			  $libguides_xml->xpath("//GUIDE[$guide_id]/GUIDE_ID"), 
			  $libguides_xml->xpath("//GUIDE[$guide_id]/LAST_UPDATE"), 
			  $libguides_xml->xpath("//GUIDE[$guide_id]/DESCRIPTION"), 
			  $libguides_xml->xpath("//GUIDE[$guide_id]/PAGES"),
                          $libguides_xml->xpath("//GUIDE[$guide_id]/PAGES//LINKS"),
			  $libguides_xml->xpath("//GUIDE[$guide_id]/OWNER_ACCOUNT_ID"),
			  $libguides_xml->xpath("//GUIDE[$guide_id]/TAGS")

			  );

    
    
    return $subject_values; 

  }




  public function load_libguides_links_xml($lib_guides_xml_path) {
    $guide_id = $this->getGuideID();
    

    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
    
    $link_values = $libguides_xml->xpath("//GUIDE[$guide_id]//LINKS/LINK");
    
    $db = new Querier;

    
    

    foreach (  $link_values as $link ) {

      if ($db->exec("INSERT INTO location (location, format, access_restrictions) VALUES (" . $db->quote($link->URL) . " , 1, 1)")  ) {
	
//	echo "Inserted location \n";
	$location_id = $db->last_id(); 
//	echo "\n";
	
      } else {

//	echo colorize("Error inserting location:", "FAILURE");
//	echo  $db->errorInfo()[2] . "\n ";
      } 

      if( $db->exec("INSERT INTO title (title) VALUES (" . $db->quote($link->NAME) . ")") ) {
//	echo "Inserted title  \n"; 
	$title_id = $db->last_id();

	echo "\n";
      } else {
//	echo colorize("Error inserting title:", "FAILURE");
//	echo  $db->errorInfo()[2] . "\n ";
      }

      if( $db->exec("INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id )") ) {
//	echo "Inserted location_title  \n"; 
	
//	echo "\n";
      } else {
//	echo colorize("Error inserting location_title:", "FAILURE");
//	echo  $db->errorInfo()[2] . "\n ";

//	echo "INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id)";
      }


    }    

  }


  public function import_libguides($subject_values) {

    $db = new Querier;

    foreach($subject_values as $subject) { 

      // Remove the apostrophes and spaces from the shortform 



      

      $shortform = preg_replace('/\s+/','_', str_replace("'", "", $subject[0] ));
      
      // Escape the apostrophes in the guide name 

      $guide_name = str_replace("'", "''",$subject[0]);
      
      if ($subject[0] != null && $this->guide_imported[0][0] < 1) {
        
        if($db->exec("INSERT INTO subject (subject, subject_id, shortform, last_modified, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[2]', '$subject[3]', '$subject[7]')")) {

         echo $subject[1];

	

        } else {
          print_r ($subject[0]);
      //    echo colorize("Error inserting subject:", "FAILURE");
       //   echo  $db->errorInfo()[2] . "\n ";
	  
        }

	if ($this->getGuideOwner() != null) {
	  $staff_id = $this->getStaffID( $this->getGuideOwner());
	  
	 // echo ("Staff ID: " . $staff_id  );
	  
	  if($db->exec("INSERT INTO staff_subject (subject_id, staff_id) VALUES ($subject[1], $staff_id)")) {
	   // echo colorize("Inserted staff: '$staff_id' \n", "SUCCESS");
	    
	  } else {

	   // echo colorize("Error inserting staff. ", "FAILURE");
	    
	  }
	  
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
	  
	 // echo  colorize("Inserted tab '$tab->NAME'", "SUCCESS") ."\n";

	} else {

        //  echo "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database.". "\n";
	  
        //  echo "\t";
	//  echo colorize("Error inserting tab:", "FAILURE");
	//  echo  $db->errorInfo()[2] . "\n ";

	}
        $row = 0;
        $column = 0;
        $section_index = null;
        foreach ($tab->BOXES as $section) {

          // LibGuide's box parents into sections
          
          $section_uniqid = $section_index . rand();
          
          $section_index++;
          

          if($db->exec("INSERT INTO section (tab_id, section_id, section_index) VALUES ('$tab->PAGE_ID', $section_uniqid ,   $section_index)")) {
          //  echo colorize("Inserted section", "SUCCESS") . "\n";
          } else { 
          //  echo "Problem inserting this section. This section  may already exist in the database." . "\n";
          //  echo "\t";
	  //  echo colorize("Error inserting section:", "FAILURE");
	  //  echo  $db->errorInfo()[2] . "\n \n";
            
          }
          
        }

        foreach ($tab->BOXES->BOX as $pluslet) {
          // This imports each LibGuide's boxes as pluslets 
          $description = null; 


	  $description .= "<div class=\"description\">$pluslet->DESCRIPTION</div>";
	  
          foreach ( $pluslet->LINKS->LINK as $link )  {
            
            
            $description .= 
            "<div class=\"links\">" . 
                            "<a href=\"$link->URL\">$link->NAME</a>" . 
                            "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                            "</div>";
            
            
          }

          
          foreach ( $pluslet->BOOKS->BOOK as $book )  {
            
            $description .= 
            "<div class=\"books\">" . 
                            "<a href=\"$book->URL\">$book->TITLE</a>" . 
                            "<div class=\"book-description\">$link->DESCRIPTION</div>" .
                            "</div>";
            
            
          }
          
	  

          
          $description .= "<div class=\"media\">" . $pluslet->EMBEDDED_MEDIA_AND_WIDGETS->URL . "</div>";  
          
          

          $clean_description = $db->quote($description);

          if($db->exec("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($pluslet->BOX_ID, '$pluslet->NAME', $clean_description, 'Basic')")) {
           // echo colorize("Inserted pluslet '$pluslet->NAME'", "SUCCESS") ."\n";
            $clean_description = null;

          } else {

           // echo "\t";
           // echo "\n";
	   // echo colorize("Error inserting pluslet:", "FAILURE");
	   // echo  $db->errorInfo()[2] . "\n ";
           // echo "\n";

          }
          
          if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$pluslet->BOX_ID', '$section_uniqid', $column, $row)")) {
          //  echo colorize("Inserted pluslet section relationship" , "SUCCESS") ."\n";
            // This sticks the newly created pluslet into a section 
          } else {

	    
	   // echo colorize("Error inserting pluslet_section:", "FAILURE");
	   // echo  $db->errorInfo()[2] . "\n ";

          }

        }
        
      }
      
    }

  }

}
