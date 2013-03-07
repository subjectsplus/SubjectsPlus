<?php

class sp_LinkChecker {

  private $_blacklistFile;
  private $_linkArray;
  private $_blacklistArray;
  private $_problemList;
  private $_baseUrl;
  
  public function __construct($blacklistFile = NULL, $baseUrl = NULL) {
    if (!function_exists('curl_exec')) {
      throw new Exception('CURL extension required to create an sp_LinkChecker object');
    }
    $this->_baseUrl = $baseUrl;
    $this->_blacklistFile = $blacklistFile;
    if (file_exists($this->_blacklistFile)) {
      $this->_blacklistArray = file($this->_blacklistFile);
    } else {
      throw new Exception('Can\'t find the blacklist file.');
    }
  }
  
  public function checkLinks($page) {
    
    $linkArray = self::extractLinks($page);

    $_problemList = "<div id=\"link_result_set\">";
    
    foreach ($linkArray as $link) {

      // resolve link if relative
      if (!(preg_match('{^(http|https|mailto):}',$link[0]))) {
	$link[0] = $this->_baseUrl . $link[0];
      }
      
      // skip this link if it matches something on the blacklist
      if ($this->_blacklistArray) {
	foreach ($this->_blacklistArray as $blitem) {
	  $blitem = trim($blitem);
	  if (preg_match("/$blitem/",$link[0])) {
	    continue 2;
	  }
	}
      }
      
      // strip proxy string
      $link[0] = self::stripProxy($link[0]);
      
      // visit the link
      if (!$c = curl_init($link[0])) {
 	throw new Exception("Problem visiting " . $link[0]);
      } else {
	//	echo $link[0] . ": ";
      }
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($c, CURLOPT_NOBODY, 1);
      curl_setopt($c, CURLOPT_HEADER, 1);
      $link_headers = curl_exec($c);
      $curl_info = curl_getinfo($c);
      curl_close($c);
 
      $rc = $curl_info['http_code'];

      
      if (preg_match('/\b404\b/',$rc)) {  // dead links

	$_problemList .= "<div style=\"background-color:yellow; margin: 2px; border-bottom: #efefef;\"><a target='_blank' href='" . $link[0] . "'>" . $link[1] . "</a>" . "&nbsp;POSSIBLE PROBLEM, RESPONSE CODE: " .  $curl_info['http_code'] . "</div>\n";

      } else if (preg_match('/\b4\d\d\b|\b0\b/',$rc)) { // weird links

	$_problemList .= "<div style=\"margin: 2px; border-bottom: #efefef;\"><a target='_blank' href='" . $link[0] . "'>" . $link[1] . "</a>" . "&nbsp;POSSIBLE PROBLEM, RESPONSE CODE: " .  $curl_info['http_code'] . "</div>\n";

      } else if (preg_match('/\b301\b/',$rc) && preg_match('/^Location: (.*)$/m',$link_headers,$matches) ) {  // redirects

	$location = trim($matches[1]);
	$_problemList .= "<div style=\"margin: 2px; border-bottom: #efefef;\"><a target='_blank' href='" . $link[0] . "'>" . $link[1] . "</a>" . "&nbsp;REDIRECTED TO: " . $location . "&nbsp;RESPONSE CODE: " .  $curl_info['http_code'] . "</div>\n";
      }

    }
//     if (!preg_match('/^\w+$/',$_problemList)) {
//       $_problemList = "<p>Yur links r okey-dokey</p>";
//     }

    $_problemList .= "</div>";

    return $_problemList;
  }
  
  
  protected function extractLinks($targetPage) {
    $a = array();
    if (preg_match_all('/<A\s+.*?HREF=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/A>/i',$targetPage,$matches,PREG_SET_ORDER)) {
      foreach ($matches as $match) {
      if (!preg_match('/mailto/',$match[1])) {
		array_push($a,array($match[1],$match[2]));
		}
      }
    }
    return $a;
  }


  protected function stripProxy($url) {
    if (preg_match('/(.+)http/', $url)) {
      $nlink = "";
      $bits = preg_split('/=/',$url);
      array_shift($bits);
      $nlink .= array_shift($bits);
      foreach ($bits as $bit) {
	$nlink .= '=' . $bit;
      }
      return $nlink;
    } else {
      return $url;
    }
  }
  
}
