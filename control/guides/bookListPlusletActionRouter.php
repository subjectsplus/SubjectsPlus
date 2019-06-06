<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 11/21/2016
 * Time: 2:37 PM
 */

include_once("../includes/config.php");
include("../includes/functions.php");

$bookListRouter = function ($action) {

	switch ($action) {
		case 'validateSyndeticsClientCode':
			validateSyndeticsClientCode();
			break;
		case 'validateGoogleBooksAPIKey':
			validateGoogleBooksAPIKey();
			break;
		case 'validateSyndeticsImageExists';
			validateSyndeticsImageExists();
			break;
		case 'isbnPrimoCheck';
			isbn_in_primo();
			break;
		case 'bookMetadataFromOpenLibrary';
			book_metadata_from_open_library();
			break;
		case 'bookCoverFromOpenLibrary';
			book_cover_from_open_library();
			break;
		case 'bookMetadataDownload';
			book_metadata_download();
			break;
		case 'bookCoverDownload';
			book_cover_download();
			break;
	}
};

function validateSyndeticsClientCode() {
	global $syndetics_client_code;

	$url = "https://syndetics.com/index.aspx?isbn=9780605039070/xml.xml&client=". $syndetics_client_code . "&type=rn12";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($curl);

	$xml = null;
	libxml_use_internal_errors(true);
	$xml = simplexml_load_string($result);
	curl_close($curl);

	if ($xml){
		echo 'true';
	}else{
		echo 'false';
	}
	libxml_use_internal_errors(false);
};

function validateGoogleBooksAPIKey() {
	global $google_books_api_key;

	$url = "https://www.googleapis.com/books/v1/volumes?key=" . $google_books_api_key . "&q=9780605039070";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($curl);

	$json = json_decode($result);

	if ( isset( $json->error) ){
		echo 'false';
	}else{
		echo 'true';
	}
};

function validateSyndeticsImageExists() {
	global $syndetics_client_code;
	$isbn = '';

	if (isset($_GET['isbn'])){
		$isbn = htmlspecialchars($_GET['isbn']);
	}

	$xmlUrl = 'https://syndetics.com/index.aspx?isbn=' . $isbn . '/xml.xml&client=' . $syndetics_client_code . '&type=rn12';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $xmlUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($curl);
	$xml = null;
	libxml_use_internal_errors(true);
	$xml = simplexml_load_string($result);
	curl_close($curl);
    $result = '';


	if (isset($xml)){
		if (isset($xml->LC)){
			echo $result = $xml->LC;
		}elseif (isset($xml->MC)){
			echo $result = $xml->MC;
		}elseif (isset($xml->SC)){
			echo $result = $xml->SC;
		}
	}

    if (empty($result)){
        $page_url = explode('control', curPageURL());
        $url = $page_url[0] . "assets/images/blank-cover.png";
        echo $url;
    }

	libxml_use_internal_errors(false);
};

function isbn_in_primo () {

	global $booklist_primo_institution_code;
	global $booklist_primo_api_key;
	global $booklist_primo_domain;
	global $booklist_primo_vid;
	$result_url = '';

	$isbn = '';

	if (isset($_GET['isbn'])){
		$isbn = htmlspecialchars($_GET['isbn']);
	}

	$url = "https://api-na.hosted.exlibrisgroup.com/primo/v1/pnxs?q=any,contains," . $isbn . '&lang=eng&view=brief&vid=' . $booklist_primo_institution_code . '&scope=default_scope&apikey=' . $booklist_primo_api_key;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$raw = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($raw,true);

	if (isset($response['info'])) {
		$total = $response['info']['total'];
		if ($total != 0) {
			$pnxId = $response['docs'][0]['pnxId'];
			$result_url = 'https://' . $booklist_primo_domain . '/primo-explore/fulldisplay?docid=' . $pnxId . '&context=L&vid=' . $booklist_primo_vid . '&institution=' . $booklist_primo_institution_code;
		}

	}

	echo $result_url;
};

function book_metadata_from_open_library () {
	$isbn = '';

	if (isset($_GET['isbn'])){
		$isbn = htmlspecialchars($_GET['isbn']);
	}

	$url = "https://openlibrary.org/api/volumes/brief/isbn/" . $isbn . ".json";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, htmlspecialchars_decode($url));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$raw = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($raw,true);
	$result = array("isbn" => array());

	if (!empty($response)) {
		foreach ($response as $data) {
			foreach ($data as $info) {
				$title = "";
				$author = array();
				$date = "";

				if (array_key_exists('title', $info['data'])){
					$title = $info['data']['title'];
				}

				if (array_key_exists('authors', $info['data'])){
					$author_list = $info['data']['authors'];
					foreach ($author_list as $author_info){
						array_push($author, $author_info['name']);
					}
				}

				if (array_key_exists('publish_date', $info['data'])){
					$date = $info['data']['publish_date'];
				}

				$result = array("isbn" => array('title' => "$title" ,'isbn' => "$isbn",'author' => array($author),'date' => "$date"));
			}
			break;
		}
	}
	echo json_encode($result);
};

function book_cover_from_open_library () {

	$isbn = '';

	if (isset($_GET['isbn'])){
		$isbn = scrubData($_GET['isbn']);
	}

	$url = "https://openlibrary.org/api/books?bibkeys=ISBN:" . $isbn . "&format=json";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, htmlspecialchars_decode($url));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$raw = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($raw,true);
	$result = "";

	if (!empty($response)) {
		foreach ($response as $data) {
			if (array_key_exists('thumbnail_url', $data)) {
				$cover_url = str_replace("-S.jpg", "-M.jpg", $data['thumbnail_url']);
				$result = $cover_url;
			}
		}
	}

	if (empty($result)){
        $page_url = explode('control', curPageURL());
        $url = $page_url[0] . "assets/images/blank-cover.png";
		$result = $url;
	}

	echo $result;
};

function book_metadata_download () {

	$title = '';
	$isbn = '';
	$author = '';
	$date = '';
	$primoUrl = '';

	if (isset($_GET['isbn'])) {
		$isbn = scrubData( $_GET['isbn'] );

		if (isset($_GET['title'])) {
			$title = scrubData( $_GET['title'] );
		}

		if (isset($_GET['author'])) {
			$author = scrubData( $_GET['author'] );
		}

		if (isset($_GET['date'])) {
			$date = scrubData( $_GET['date'] );
		}

		if (isset($_GET['primoUrl'])) {
			$primoUrl = scrubData( $_GET['primoUrl'] );
		}

		$prefix    = explode( 'subjects', dirname( __FILE__ ) );
		$file_path = $prefix[0] . "/../../assets/cache/" . $isbn . ".bookmetadata";

		if ( ! file_exists( $file_path ) ) {
			$data          = array(
				"isbn" => array(
					'title'    => "$title",
					'isbn'     => "$isbn",
					'author'   => "$author",
					'date'     => "$date",
					'primoUrl' => $primoUrl
				)
			);
			$newJsonString = json_encode( $data );
			file_put_contents( $file_path, $newJsonString, FILE_APPEND | LOCK_EX );
		}
	}
};

function book_cover_download () {

	$isbn ='';
	$url = '';

	if (isset($_GET['isbn'])) {
		$isbn = scrubData( $_GET['isbn'] );
	}

	if (isset($_GET['url'])) {
		$url = scrubData( $_GET['url'] );
	}

	$prefix = explode('subjects', dirname(__FILE__));
	if (empty($url) || strpos($url, 'blank-cover') !== false) {
		$page_url = explode('control', curPageURL());
		$url = $page_url[0] . "assets/images/blank-cover.png";
		$cover = file_get_contents($url);
		file_put_contents($prefix[0] . "/../../assets/cache/" . $isbn . ".jpg", $cover, FILE_APPEND | LOCK_EX);

	} else {

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, htmlspecialchars_decode($url));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$raw = curl_exec($curl);
		curl_close($curl);
		file_put_contents($prefix[0] . "/../../assets/cache/" . $isbn . ".jpg", $raw, FILE_APPEND | LOCK_EX);
	}

};

function curPageURL() {
	$pageURL = 'http';
    if(array_key_exists('HTTPS', $_SERVER)) {
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
    }
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

$bookListRouter(scrubData($_GET['action']));