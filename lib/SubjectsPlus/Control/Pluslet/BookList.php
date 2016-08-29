<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:19 PM
 */
/**
 * Class BookList
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");


class Pluslet_BookList extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "BookList";
        $this->_pluslet_id = $pluslet_id;
        $this->_subject_id = $subject_id;
        $this->_isclone = $isclone;
        $this->_pluslet_bonus_classes = "type-booklist";

    }

    protected function onEditOutput()
    {
        if($this->_extra == "")
        {
            $this->_extra = array();

        }else
        {
            $this->_extra = json_decode( $this->_extra, true );
        }

        $this->_body = $this->loadHtml(__DIR__ . '/views/BookListEditOutput.php');
    }

    protected function onViewOutput()
    {
        $this->_extra = json_decode( $this->_extra, true );
        $this->_body = $this->loadHtml(__DIR__ . '/views/BookListViewOutput.php');

    }

    static function getMenuName()
    {
        return _('Book List');
    }

    static function getMenuIcon()
    {
        $icon="<span class=\"icon-text \">" . _("Book List") . "</span>";
        return $icon;
    }

    function setBookInfo($isbn)
    {
        $info = $this->getWebServiceBookInfo($isbn);
        $this->_validBook = false;
        global $syndetics_client_code;

        if (!is_null($info)){
            if (isset($info->totalItems)) {
                if ($info->totalItems > 0) {
                    $info = $info->items[0];
                    $this->_validBook = true;
                    $this->_bookId = $info->id;
                    $info = $info->volumeInfo;
                    $this->_bookTitle = $info->title;

                    $this->setBookCover($isbn, $syndetics_client_code, $info);

                    $this->_bookIsbnNumber = $isbn;
                    $this->_authorsList = implode(", ", $info->authors);
                    $this->_publishedDate = date_format(date_create($info->publishedDate), "F, Y");
                }
            }
        }
    }

    function getWebServiceBookInfo($isbn)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.googleapis.com/books/v1/volumes?q=isbn:".$isbn);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
        $result = json_decode(curl_exec($curl));
        curl_close($curl);

        return $result;
    }

    function setBookCover($isbn, $syndetics_client_code, $info)
    {
        $foundInSyndetics = false;

        if (!empty($syndetics_client_code)) {

            $xmlURL = "https://syndetics.com/index.aspx?isbn=" . $isbn . "/xml.xml&client=" . $syndetics_client_code . "&type=rn12";

            try{
                $xml = @simplexml_load_file($xmlURL);
            }catch (\Exception $e){}

            if ($xml) {
                if ($xml->LC) {
                    $foundInSyndetics = true;
                    $this->_bookCover = $xml->LC;
                }
            }
        }

        if (!$foundInSyndetics) {
            if (isset($info->imageLinks->thumbnail)) {
                $this->_bookCover = $info->imageLinks->thumbnail;
            }else{
                $this->_bookCover = "";
            }
        }
    }

}