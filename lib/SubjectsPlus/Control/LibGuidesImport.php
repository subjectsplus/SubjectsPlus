<?php
namespace SubjectsPlus\Control;

class LibGuidesImport {

  private $_guide_id;
  private $_libguides_xml;
  private $_guide_owner;
  

  public function importLog($log_text) {
    
    file_put_contents("../../../import_log.txt", $log_text);
    
  }

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


  public function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br>')
  {
    // From https://gist.github.com/dave1010/674071
    mb_regex_encoding('UTF-8');
    //replace MS special characters first
    $search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u');
    $replace = array('\'', '\'', '"', '"', '-');
    $text = preg_replace($search, $replace, $text);
    //make sure _all_ html entities are converted to the plain ascii equivalents - it appears
    //in some MS headers, some html entities are encoded and some aren't
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    //try to strip out any C style comments first, since these, embedded in html comments, seem to
    //prevent strip_tags from removing html comments (MS Word introduced combination)
    if(mb_stripos($text, '/*') !== FALSE){
      $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm');
    }
    //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be
    //'<1' becomes '< 1'(note: somewhat application specific)
    $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text);
    $text = strip_tags($text, $allowed_tags);
    //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one
    $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text);
    //strip out inline css and simplify style tags
    $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
    $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
    $text = preg_replace($search, $replace, $text);
    //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears
    //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains
    //some MS Style Definitions - this last bit gets rid of any leftover comments */
    $num_matches = preg_match_all("/\<!--/u", $text, $matches);
    if($num_matches){
      $text = preg_replace('/\<!--(.)*--\>/isu', '', $text);
    }
    return $text;
  }

  public function download_images($url) {
    // This method creates a folder for a guide image in assets, downloads the image , and then returns the new URL for that image

    global $AssetPath;

    // Download the image with CURL

    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);

    // Create a path for the iamge
    $dir_name =  dirname(dirname(dirname(dirname(__FILE__)))) . "/assets/images/" . $this->_guide_id . "/";

    // Make the guide's asset directory if needed
    if (!is_dir($dir_name))
    {
      mkdir($dir_name, 0777, true);
    }


    // Write the file
    $file_name = substr( $url, strrpos( $url, '/' )+1 );
    $file = fopen( $dir_name .  $file_name, 'w+');
    fwrite($file, $data);
    fclose($file);


    // Return the new URL
    $img_path = $AssetPath . "/images/" . $this->_guide_id . "/" . $file_name;

    return $img_path;

  }

  public function getStaffID ($email_address) {
    // This method takes an email address and returns the subjectsplus staff id for the user

    $db = new Querier;
    $staff_id = $db->query("SELECT staff_id FROM staff WHERE email = '$email_address'");

    return $staff_id[0][0];

  }

  public function output_guides($lib_guides_xml_path) {
    // Outputs a select box for guides 

    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
    
    
    $owners = $libguides_xml->xpath("//OWNER");
    $owner_names = array();
    $owner_email = array();
    $owner_profile = array();

    foreach ($owners as $owner) {
      if(!in_array((string) $owner->NAME, $owner_names)) {

	array_push($owner_names, (string) $owner->NAME);
      }
    }


    foreach ($owners as $owner) {
      if(!in_array((string) $owner->EMAIL_ADDRESS, $owner_email)) {

	array_push($owner_email, (string) $owner->EMAIL_ADDRESS);
      }
    }

    
    $owners_combined = zip($owner_names, $owner_email);

    foreach ($owners_combined as $owner) {
      
      echo "<h1>" . $owner[0] . "</h1>";
      
      
      $guide_names = $libguides_xml->xpath("//OWNER/NAME[text() = '$owner[0]']/ancestor::GUIDE");
      
      // $id_count = 1;


      echo "<select class=\"guides\" >";

      foreach ($guide_names as $guide) {

	echo "<option value=\"$guide->GUIDE_ID\">$guide->NAME</option>";
	// $id_count++;

      }

      echo "<select>";
      echo  " <button class='import_guide'>Import Guide</button>";


/*
      $dupe = $this->guide_dupe($guide->NAME);
      if ($dupe[0][0]) {
	echo "<p>";
	echo "Already Imported: " . $guide->NAME;
	echo "</p>";
      }
*/

    }

    

  }


  public function guide_imported() {

    $guide_id = $this->getGuideID();


    $db = new Querier;



    $guide = $db->query("SELECT COUNT(*) FROM subject WHERE subject_id = '$guide_id'");

    return $guide;

  }



  public function guide_dupe($guide_name) {

    $db = new Querier;
    $guide = $db->query("SELECT COUNT(*) FROM subject WHERE subject = '$guide_name'");

    return $guide;

  }

  public function load_libguides_xml($lib_guides_xml_path) {

    

    $section_index = 0;

    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));

    // zip combines arrays in fancy way
    // From the python docs: "This function returns a list of tuples, where the i-th tuple contains the i-th element from each of the argument sequences or iterables. The returned list is truncated in length to the length of the shortest argument sequence."

    $guide_id = $this->getGuideID();

    
    
    //Get the guide owner's email address
    $guide_owner_id = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/following-sibling::OWNER_ACCOUNT_ID");
    $guide_owner_email = $libguides_xml->xpath("//ACCOUNT_ID[.=\"$guide_owner_id[0]\"]/following-sibling::EMAIL");
    $this->setGuideOwner($guide_owner_email[0]);


    $subject_values = zip($libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/following-sibling::NAME"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/GUIDE_ID"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/LAST_UPDATE"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/DESCRIPTION"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/PAGES"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/PAGES//LINKS"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/OWNER_ACCOUNT_ID"),
			  $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/TAGS")

			  );

    

    return $subject_values;


  }




  public function load_libguides_links_xml($lib_guides_xml_path) {

    $db = new Querier;
    
    $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
    
    $link_values = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=[$this->_guide_id]//LINKS/LINK");
    
    $db = new Querier;

    foreach (  $link_values as $link ) {
      // Remove the proxy url from the link URL
      $noproxy_url = str_replace("https://iiiprxy.library.miami.edu/login?url=", "",$link->URL); 
      $noproxy_url = $db->quote($noproxy_url);
      $title =  $db->quote($link->NAME);

      $record_check = $db->query("SELECT COUNT(*) FROM location WHERE location = $noproxy_url ");
      $title_check = $db->query("SELECT COUNT(*) FROM title WHERE title = $title");
      $this->importLog ( $record_check) ;
      $this->importLog ("RECORD CHECK!!!!!!!!!!!!!!!!!!!!!!");
      $this->importLog($record_check[0][0]);

      if ($record_check[0][0] == 0 && $title_check[0][0] == 0) {

	if ($db->exec("INSERT INTO location (location, format, access_restrictions, eres_display) VALUES (" . $db->quote($link->URL) . " , 1, 1, 'N' )" )) {
	  
	  $this->importLog("Inserted location");
	  $location_id = $db->last_id(); 

	  
	} else {
	  
	  $this->importLog ("Error inserting location:");

	  
	}

	
	// When inserting the titles into the databases, articles (a, an, the) should be removed and then stored in the prefix field 
	
	
	$matches = array();
	preg_match("/^\b(the|a|an|la|les|el|las|los)\b/i", $link->NAME, $matches);

	
	// If there isn't an article in the title
	if (empty($maches[0])) {
	  
	  if( $db->exec("INSERT INTO title (title, description) VALUES (" . $db->quote($link->NAME) . ","  . $db->quote($link->DESCRIPTION_SHORT)  . ")") ) {
	    $this->importLog( "Inserted title");
	    $title_id = $db->last_id();

	  } else {
	    $this->importLog("Error inserting title:" );
	    $this->importLog(  $db->errorInfo() );
	  }
	  
	}
	
	// If there is an article in the title
	
	if(isset($matches[0])) {
	  
	  $clean_link_name = preg_replace("/^\b(the|a|an|la|les|el|las|los)/i", " ", $link->NAME);
	  
	  if( $db->exec("INSERT INTO title (title, description, pre) VALUES (" . $db->quote($clean_link_name) . ","  . $db->quote($link->DESCRIPTION_SHORT) . "," . $db->quote($matches[0]) . ")") ) {
	    $this->importLog( "Inserted title");
	    $title_id = $db->last_id();

	  } else {
	    $this->importLog("Error inserting title:" );
	    $this->importLog(  $db->errorInfo() );
	  }
	  
	}
	
	
	if( $db->exec("INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id )") ) {
	  $this->importLog( "Inserted location_title"); 
	  

	} else {
	  $this->importLog( "Error inserting location_title:");
	  $this->importLog(  $db->errorInfo()  );

	  $this->importLog( "INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id)");
	}

	
      }

    }    

  }


  public function import_libguides($subject_values) {
    
    //echo ($subject_values);
    
    $db = new Querier;
    $subject_id = $subject_values[0][1]->__toString();



    if ($this->guide_imported() != 0) {

      //exit;
    }
    
    
    foreach($subject_values as $subject) { 

      // Remove the apostrophes and spaces from the shortform 

      $shortform = preg_replace('/\s+/','_', str_replace("'", "", $subject[0] ));
      
      // Escape the apostrophes in the guide name 

      $guide_name = str_replace("'", "''",$subject[0]);
      $guide_check = $this->guide_dupe($guide_name);

      if ($guide_check[0][0] != 0) {
        $dupe_message = "It looks like this guide has already been imported.";
        return $dupe_message;
      }

      if ($subject[0] != null) {
	


        if($db->exec("INSERT INTO subject (subject, subject_id, shortform, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[3]', '$subject[7]')")) {

          echo $subject[1];

	  

        } else {
          echo $subject[1][0];
	  
	  $query = "INSERT INTO subject (subject, subject_id, shortform, last_modified, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[2]', '$subject[3]', '$subject[7]')";
          
	  $this->importLog( "Error inserting subject:");
	  $this->importLog ($query);
          $this->importLog ( $db->errorInfo() ); 
	  
        }

	if ($this->getGuideOwner() != null) {
	  $staff_id = $this->getStaffID( $this->getGuideOwner());
	  
	  $this->importLog ("Staff ID: " . $staff_id );
	  
	  if($db->exec("INSERT INTO staff_subject (subject_id, staff_id) VALUES ($subject[1], $staff_id)")) {
	    $this->importLog ("Inserted staff: '$staff_id'");
	    
	  } else {

	    $this->importLog("Error inserting staff. ");
	    
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
        
	if($db->exec("INSERT INTO tab (tab_id, subject_id, label, tab_index) VALUES ('$tab->PAGE_ID', '$subject[1]', $clean_tab_name, $tab_index - 1)")) {
	  
	  $this->importLog ("Inserted tab '$tab->NAME'");

	} else {

          $this->importLog( "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database." );
	  
          
	  $this->importLog ("Error inserting tab:");
	  $this->importLog ($db->errorInfo());

	}
        $row = 0;
        $column = 0;
        $section_index = null;
        foreach ($tab->BOXES as $section) {

          // LibGuide's box parents into sections
          
          $section_uniqid = $section_index . rand();
          
          $section_index++;


          if($db->exec("INSERT INTO section (tab_id, section_id, section_index) VALUES ('$tab->PAGE_ID', $section_uniqid ,   $section_index)")) {
            $this->importLog("Inserted section");
          } else { 
            $this->importLog("Problem inserting this section. This section  may already exist in the database.");
            
	    $this->importLog("Error inserting section:");
	    $this->importLog($db->errorInfo() );
            
          }
          
        }

        foreach ($tab->BOXES->BOX as $pluslet) {
          // This imports each LibGuide's boxes as pluslets 
          $description = null; 
	  
	  // Import images and replace the old urls with new urls
	  $doc = new \DOMDocument();

	  $doc->loadHTML(mb_convert_encoding($pluslet->DESCRIPTION, 'UTF-8'));
	  

	  // Download images 

	  $nodes = $doc->getElementsByTagName("img");

	  foreach( $nodes as $node ) {

	    foreach ($node->attributes as $attr) {
	      $test = strpos($attr->value, "http://");
	      
	      if ($test !== false) { 
		$this->importLog( $attr->value);
		
		$attr->value = $this->download_images($attr->value);
		

	      }
	    }
	    
	    
	    // Create html for the description

	    $description .= "<div class=\"description\">".  $this->strip_word_html(htmlspecialchars($doc->saveHTML())) . "</div>";

	  }



	  // Go throught the links in the box and check to see if they are OK

	// Links in Boxes

          foreach ( $pluslet->LINKS->LINK as $link )  {
            
            
            $db = new Querier;
	    $record = $db->query("SELECT * FROM location WHERE location = " .  $db->quote($link->URL),NULL,TRUE);


	    if ($record[0]['location_id']) {

	      $record_title = $db->query("SELECT title.title,title.title_id, location.location  FROM 
location_title 
JOIN title ON title.title_id = location_title.title_id
JOIN location on location.location_id = location_title.location_id
WHERE location.location_id = " . $record[0]['location_id']);

} else {

}

if ($record_title[0]["title"] == "") {
  
  $description .=    "<div class=\"links\">" . 
		     "<span class=\"link_title\"> $link->NAME </span>" .
                     "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                     "</div>";
} 

if ($record_title[0][title]) {
  
  $description .= 
  "<div class=\"links\">" . 
                  "{{dab},{" . $record[0]['location_id'] . "}," . "{" . $record_title[0]["title"] . "},{01}}" . 
                  "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                  "</div>";
  
}

$this->importLog ("Insert record:");
$this->importLog($record_title); 
$this->importLog("SELECT * FROM location WHERE location = " .  $db->quote($link->URL));

}

// Box type: Books

foreach ( $pluslet->BOOKS->BOOK as $book )  {
  
  $description .= 
  "<div class=\"books\">" . 
                  "<a href=\"$book->URL\">$book->TITLE</a>" . 
                  "<div class=\"book-description\">$link->DESCRIPTION</div>" .
                  "</div>";
  
  
}

//Box type: Media & Widgets

foreach ( $pluslet->EMBEDDED_MEDIA_AND_WIDGETS->URL as $media )  {
  
  $description .= 
  "<div class=\"embedded_media_widgets\">" . 
                  "<div class=\"book-description\">$media->URL</div>" .
                  "</div>";
  
  
}





$description .= "<div class=\"media\">" . $pluslet->DESCRIPTION . "</div>";  

$clean_description = $db->quote($description);

if($db->exec("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($pluslet->BOX_ID, '$pluslet->NAME', $clean_description, 'Basic')")) {
  
  $this->importLog("Inserted pluslet '$pluslet->NAME'");
  $clean_description = null;

} else {

  
  $this->importLog("Error inserting pluslet:");
  $this->importLog($db->errorInfo());
  

}

if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$pluslet->BOX_ID', '$section_uniqid', $column, $row)")) {
  $this->importLog("Inserted pluslet section relationship");
  

  // This sticks the newly created pluslet into a section 
} else {

  
  $this->importLog("Error inserting pluslet_section:");
  $this->importLog( $db->errorInfo());

}
}
}
}
}
}
