<?php
// ЮТФ-8


// sendrequest('http://www.torrents.kg/takelogin.php','username=admin','http://www.torrents.kg/','','','GET');
function sendrequest($url,$data,$referer,$proxy,$proxystatus,$method='POST',$onlyheaders=false) {
	$cookie_jar = "cookie.txt";
	$ch = curl_init("$url");
	if ($onlyheaders===true) {
		curl_setopt($ch, CURLOPT_FILETIME, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
	} else {
		curl_setopt($ch, CURLOPT_HEADER, 0);
	}
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "$cookie_jar");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "$cookie_jar"); 
	curl_setopt($ch, CURLOPT_URL, "$url");  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	if ($method=='POST') {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		if ($data<>'') curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 4);
	$result=curl_exec($ch);
	curl_close($ch);
	return $result; 
}


function translatenew4($string,$fromlang="auto",$tolang="en") {
	//$url='http://translate.googleapis.com/translate_a/t?anno=3&client=te&format=html&v=2.0&logld=v8&ie=UTF-8&oe=UTF-8';
	$url='http://translate.google.com/translate_a/t?client=json&js=n&hl=ru&ie=UTF-8&oe=UTF-8&sl='.$fromlang.'&tl='.$tolang.'&multires=1&prev=btn&ssel=0&tsel=0&sc=1&layout=1&eotf=1&c_ad=1';
	$temp2='';
	$query='';
	
	if (is_array($string)) {
		for ($i=0; $i<count($string); $i++) {
			$query.='q='.rawurlencode($string[$i]);
			if (($i<count($string)-1)) $query.='&';
		}
	} else {
		$query.='q='.rawurlencode($string);
	}
	$query.='&sl='.$fromlang.'&tl='.$tolang.'&tc=1';
	
	$result=sendrequest($url,$query,"http://translate.google.com/#",'','','POST');
	$json=json_decode($result, true);
	
	if (is_array($string)) {
		for ($i=0; $i<count($json['results']); $i++) {
			$thistemp='';
			if (isset($json['results'][$i]['sentences'])) {
				for ($t=0; $t<count($json['results'][$i]['sentences']); $t++) {
					$thistemp.=trim($json['results'][$i]['sentences'][$t]['trans']);
					if ($t<(count($json['results'][$i]['sentences'])-1)) $thistemp.="\n";
				}
			}
			$temp2[]=$thistemp;
		}
	} else {
		$thistemp='';
		for ($t=0; $t<count($json['sentences']); $t++) {
			$thistemp.=trim($json['sentences'][$t]['trans']);
			if ($t<(count($json['sentences'])-1)) $thistemp.="\n";
		}
		$temp2=$thistemp;
	}
	
	if ($temp2=='') {
		echo '<pre>'; print_r($result); echo '</pre>'; echo '<pre>'; print_r($json); echo '</pre>'; exit;
		return 'ERROR';
	} else {
		return $temp2;
	}
}


function printr($str) {
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}




$trans=false;
$textarr='';
unset($texttrans);


if ($fp=fopen('messages_orig.po','r')) {
	
	while (($bu = fgets($fp, 4096)) !== false) {
		
		if (substr($bu,0,6)=='msgstr') {
			$transtext=str_replace(array('<strong>','</strong>'),array('[11]','[12]'),$transtext);
			$transtext=str_replace(array('<em>','</em>'),array('[21]','[22]'),$transtext);
			$transtext=str_replace(array('<p>','</p>'),array('[31]','[32]'),$transtext);
			$transtext=str_replace(array('<br />'),array('[41]'),$transtext);
			$transtext=str_replace(array('\r','\n','\t'),array('[51]','[52]','[53]'),$transtext);
			$texttrans[]=$transtext;
			$textarr[]=$bu;
			$trans=false;
			$transtext='';
		} else {
			$textarr[]=$bu;
		}
		
		if (substr($bu,0,5)=='msgid') {
			$tcount=0;
			$trans=true;
			$transtext='';
		}
		
		if ($trans==true) {
			if ($tcount==0) $transtext.=substr($bu,7,-2);
			else $transtext.=substr($bu,1,-2);
			$tcount++;
		}
		
	}
	
	if (!feof($fp)) echo "Error: unexpected fgets() fail\n";
	fclose($fp);
}


$donetext=translatenew4($texttrans,'en','ru');



$num=0;

for ($i=0; $i<count($textarr); $i++) {
	if (substr($textarr[$i],0,6)=='msgstr') {
		$donetext[$num]=str_replace(array("\n","\r",' ,',' .','[ ',' ]'),array('','',',','.','[',']'),$donetext[$num]);
		$donetext[$num]=str_replace(array('[11]','[12]'),array('<strong>','</strong>'),$donetext[$num]);
		$donetext[$num]=str_replace(array('[21]','[22]'),array('<em>','</em>'),$donetext[$num]);
		$donetext[$num]=str_replace(array('[31]','[32]'),array('<p>','</p>'),$donetext[$num]);
		$donetext[$num]=str_replace(array('[41]'),array('<br />'),$donetext[$num]);
		$donetext[$num]=str_replace(array('[51]','[52]','[53]'),array('\r','\n','\t'),$donetext[$num]);
		$textarr[$i]='msgstr "'.$donetext[$num].'"'."\n";
		$num++;
	} 
}


if ($sa=fopen('messages.po','w')) {
	fwrite($sa,implode("",$textarr));
	fclose($sa);
}


?>