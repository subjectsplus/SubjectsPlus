<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 7/14/16
 * Time: 1:23 PM
 */

namespace SubjectsPlus\Control\OAI;
use SubjectsPlus\Control\Querier;


class Record
{

    private $connection;
    private $db;
    
    private $creator;
    private $date;
    private $description;
    private $format = 'text/html';
    private $identifier;
    private $identifierUrl;
    private $language = 'English';
    private $publisher;
    private $title;
    private $type = 'InteractiveResource';

    public function __construct(Querier $db, array $setup)
    {
        $this->db = $db;
        $this->connection = $db->getConnection();

        $this->repositoryName = $setup['repositoryName'];
        $this->baseUrl        = $setup['baseUrl'];
        $this->adminEmail     = $setup['adminEmail'];
        $this->publisher      = $setup['institutionName'];
        $this->identifierUrl  = $setup['identifierUrl'];
    }



    public  function getRecord($id) {

        $record = $this->getSubject($id);
        $staff  = $this->getStaff($id);

        $this->setTitle($record['subject']);
        $this->setDate($record['last_modified']);
        $this->setDescription($record['description']);
        $this->setIdentifier($this->identifierUrl.$id);
        $this->setCreator($staff['fname'].' '.$staff['lname']);
    }


    public function toArray() {
        return get_object_vars($this);
    }


    public function getStaff($subject_id) {

        $statement = $this->connection->prepare("SELECT fname, lname FROM staff 
                                                  INNER JOIN staff_subject
                                                  ON staff_subject.staff_id = staff.staff_id
                                                  WHERE staff_subject.subject_id = :id");
        $statement->bindParam(':id', $subject_id);
        $statement->execute();
        $result = $statement->fetch();

        return $result;
    }


    public function getSubject($id) {

        $statement = $this->connection->prepare("SELECT * FROM subject WHERE subject_id = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $result = $statement->fetch();

        return $result;
    }
    

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param mixed $publisher
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    
    
    
}