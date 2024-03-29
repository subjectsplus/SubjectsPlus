<?php

namespace SubjectsPlus\Control\Ebooks;

class EbooksController
{

    /**
     * @var EbooksModel
     */
    private $_ebooksData;

    public function __construct(EbooksModel $ebooksData)
    {
        $this->_ebooksData = $ebooksData;
    }

    public function getPageHeader($method = null, $letter = null, $subject_id = null, $searchterm = null) {
        if ($searchterm) {
            $result = "eBooks List by search: <i>" . $searchterm . "</i>";
            return $result;
        }

        switch ($method) {
            case "byebooksub":
                $result = "eBooks List by Subject";
                break;
            case "byletter":
                $result = "eBooks List: ". $letter;
                break;
            default:
                $result = "eBooks List: A";
        }
        return $result;
    }

    public function getEbooksByParams($method = null, $letter = null, $subject_id = null, $searchterm = null) {
        if ($searchterm) {
            $result = $this->_ebooksData->fetchEbooksBySearch($searchterm);
            return $result;
        }
        switch ($method) {
            case "searchterm":
                $result = $this->_ebooksData->fetchEbooksBySearch($searchterm);
                break;
            case "byebooksub":
                $result = $this->_ebooksData->fetchEbooksBySubjectId($subject_id);
                break;
            case "byletter":
                $result = $this->_ebooksData->fetchEbooksByLetter($letter);
                break;
            default:
                $letter = "A";
                $result = $this->_ebooksData->fetchEbooksByLetter($letter);
        }
        return $result;
    }
}