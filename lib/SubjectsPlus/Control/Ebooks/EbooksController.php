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

    public function getPageHeader($method = null, $letter = null, $subject_id = null) {

        switch ($method) {
            case "byebooksub":
                $result = "eBooks List by Subject";
                break;
            case "byletter":
                $result = "eBooks List: ". $letter;
                break;
            default:
                $result = "eBooks List";
        }
        return $result;
    }

    public function getEbooksByParams($method = null, $letter = null, $subject_id = null) {
        switch ($method) {
            case "byebooksub":
                $result = $this->_ebooksData->fetchEbooksBySubjectId($subject_id);
                break;
            case "byletter":
                $result = $this->_ebooksData->fetchEbooksByLetter($letter);
                break;
            default:
                $result = "No param selected";
        }
        return $result;
    }
}