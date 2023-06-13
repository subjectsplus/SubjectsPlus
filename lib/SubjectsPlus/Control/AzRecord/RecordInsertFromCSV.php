<?php

namespace SubjectsPlus\Control\AzRecord;

use PHPMailer\PHPMailer\Exception;
use SubjectsPlus\Control\Querier;

class RecordInsertFromCSV
{

    private $_db;
    private $_title_id;

    private $_connection;
    /**
     * @var mixed
     */
    private $_staff_id;

    public function __construct($db) {
        $this->_db = $db;
        $this->_connection = $this->_db->getConnection();
        $this->_staff_id = $_SESSION['staff_id'];
    }

    public function insertFromCSV($filepath)
    {
        $this->_connection->beginTransaction();
        $csvFile = '/home/site/wwwroot/control/admin/' . $filepath;

        try {
            if (file_exists($csvFile)) {

                if (($handle = fopen($csvFile, "r")) !== false) {
                    $rowNumber = 0; // Variable to track the row number

                    // Read the CSV file row by row
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {

                        // Skip the first row
                        if ($rowNumber === 0) {
                            $rowNumber++;
                            continue;
                        }
                        // set vars from csv table data
                        $csv_data['subject']             = $data[0];
                        $csv_data['title']               = $data[1];
                        $csv_data['location']            = $data[2];
                        $csv_data['alternate_title']     = $data[3];
                        $csv_data['description']         = $data[4];
                        $csv_data['internal_notes']      = $data[5];
                        $csv_data['pre']                 = $data[6];
                        $csv_data['format']              = $data[7];
                        $csv_data['access_restrictions'] = $data[8];
                        $csv_data['eres_display']        = $data[9];
                        $csv_data['record_status']       = $data[10];

                        // random vars needed for title
                        $last_modified_by = $this->_staff_id;
                        $last_modified    = date('Y-m-d H:i:s', strtotime('now'));

                        // set title_data for insert into title table
                        $title_data['title']            = $csv_data['title'];
                        $title_data['alternate_title']  = $csv_data['alternate_title'];
                        $title_data['description']      = $csv_data['description'];
                        $title_data['internal_notes']   = $csv_data['internal_notes'];
                        $title_data['pre']              = $csv_data['pre'];
                        $title_data['last_modified_by'] = $last_modified_by;
                        $title_data['last_modified']    = $last_modified;

                        // insert data into title table
                        $last_title_id = $this->insertTitle($title_data);
                        var_dump($last_title_id);

                        if($last_title_id > 0) {
                            // set data array to insert data into location
                            $location_data['format']              = $csv_data['format'];
                            $location_data['call_number']         = null;
                            $location_data['location']            = $csv_data['location'];
                            $location_data['access_restrictions'] = intval($csv_data['access_restrictions']);
                            $location_data['eres_display']        = $csv_data['eres_display'];
                            $location_data['display_note']        = null;
                            $location_data['helpguide']           = null;
                            $location_data['citation_guide']      = null;
                            $location_data['ctags']               = null;
                            $location_data['trial_start']         = null;
                            $location_data['trial_end']           = null;
                            $location_data['record_status']       = $csv_data['record_status'];
                            //var_dump($location_data);

                            // insert into location table
                            $last_location_id = $this->insertLocation($location_data);
                            var_dump($last_location_id);

                        } else {
                            return "Title did not insert";
                        }

                        if($last_location_id > 0) {
                            // set location_title_data to insert into location_title table
                            $location_title_data['location_id'] = $last_location_id;
                            $location_title_data['title_id']    = $last_title_id;

                            // insert into location_title table
                            $this->insertLocationTitle($location_title_data);
                        } else {
                            return "location did not insert";
                        }

                        // fetch subject id for rank table - $subject is from csv
                        $subject_match = $this->trimString($csv_data['subject']);
                        $subject_id    = $this->fetchSubjectIdLikeSubject($subject_match);
                        var_dump($subject_id);

                        // set rank data to insert into rank table
                        $rank_data['rank']                 = 0;
                        $rank_data['subject_id']           = $subject_id;
                        $rank_data['title_id']             = $last_title_id;
                        $rank_data['source_id']            = 1;
                        $rank_data['description_override'] = null;
                        $rank_data['dbbysub_active']       = 1;

                        // insert into rank table
                        $this->insertRank($rank_data);

                        $insertedCount = $rowNumber++;
                    }
                    fclose($handle);
                }
                $this->_connection->commit();
                return $insertedCount . " records inserted";

            } else {
                return "The file does not exist.";
            }


        } catch (\PDOException $e) {
            // Rollback the transaction if any error occurred
            $this->_connection->rollback();
            throw $e;
        }

    }

    public function cleanUpTitleString($string) {
        return preg_replace('/,\s+/', ', ', $string);
    }

    public function trimString($sting) {
        return trim($sting);
    }

    public function fetchSubjectIdLikeSubject($subject) {

        try {
            // Prepare the SQL statement
            $sql = "SELECT subject_id FROM subject WHERE subject LIKE CONCAT('%', :subject, ' (e)')";

            // Prepare the statement
            $stmt = $this->_connection->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':subject', $subject);

            // Execute the prepared statement
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch();

            // Check if a result was found
            if ($result) {
                return $result['subject_id'];
            } else {
                return "Subject not found.";
            }
        } catch (\PDOException $exception) {
            return "An error returned when attempting to fetch a subject id: " . $exception->getMessage();
        }

    }

    public function insertTitle($data) {

        try {

            // Prepare the SQL statement
            $sql = "INSERT INTO title (title, 
                   alternate_title, 
                   description, 
                   internal_notes, 
                   pre,
                   last_modified_by,
                   last_modified) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $stmt = $this->_connection->prepare($sql);

            $title            = $data['title'];
            $title            = $this->cleanUpTitleString($title);
            $title            = $this->trimString($title);
            $alternate_title  = $data['alternate_title'];
            $description      = $data['description'];
            $internal_notes   = $data['internal_notes'];
            $pre              = $data['pre'];
            $last_modified_by = $data['last_modified_by'];
            $last_modified    = $data['last_modified'];

            // Bind the parameters
            $stmt->bindParam(1, $title);
            $stmt->bindParam(2, $alternate_title);
            $stmt->bindParam(3, $description);
            $stmt->bindParam(4, $internal_notes);
            $stmt->bindParam(5, $pre);
            $stmt->bindParam(6, $last_modified_by);
            $stmt->bindParam(7, $last_modified);

            // Execute the prepared statement
            $stmt->execute();

            // Check for successful execution or handle any errors
            if ($stmt->rowCount() > 0) {
                // return the last inserted title_id
                $return =  $this->_db->last_id();
            }

        } catch (\PDOException $exception) {
            $return = "An error returned when attempting to insert a title: " . $exception->getMessage();
        }
        return $return;
    }

    public function insertLocation($data) {

        try {

            // Prepare the SQL statement
//            $sql = "INSERT INTO location (format,
//                                        call_number,
//                                        location,
//                                        access_restrictions,
//                                        eres_display,
//                                        display_note,
//                                        helpguide,
//                                        citation_guide,
//                                        ctags,
//                                        trial_start,
//                                        trial_end,
//                                        record_status)
//                    VALUES (:format,
//                            :call_number,
//                            :location,
//                            :access_restrictions,
//                            :eres_display,
//                            :display_note,
//                            :helpguide,
//                            :citation_guide,
//                            :ctags,
//                            :trial_start,
//                            :trial_end,
//                            :record_status)";
//
//            // Prepare the statement
//            $stmt = $this->_connection->prepare($sql);
//
//            $format              = $data['format'];
//            $call_number         = $data['call_number'];
//            $location            = $data['location'];
//            $location            = $this->trimString($location);
//            $access_restrictions = $data['access_restrictions'];
//            $eres_display        = $data['eres_display'];
//            $display_note        = $data['display_note'];
//            $helpguide           = $data['helpguide'];
//            $citation_guide      = $data['citation_guide'];
//            $ctags               = $data['ctags'];
//            $trial_start         = $data['trial_start'];
//            $trial_end           = $data['trial_end'];
//            $record_status       = $data['record_status'];
//            var_dump($data);
//
//            // Bind the parameters
//            $stmt->bindParam(':format', $format);
//            $stmt->bindParam(':call_number', $call_number);
//            $stmt->bindParam(':location', $location);
//            $stmt->bindParam(':access_restrictions', $access_restrictions);
//            $stmt->bindParam(':eres_display', $eres_display);
//            $stmt->bindParam(':display_note', $display_note);
//            $stmt->bindParam(':helpguide', $helpguide);
//            $stmt->bindParam(':citation_guide', $citation_guide);
//            $stmt->bindParam(':ctags', $ctags);
//            $stmt->bindParam(':trial_start', $trial_start);
//            $stmt->bindParam(':trial_end', $trial_end);
//            $stmt->bindParam(':record_status', $record_status);


            $sql = "INSERT INTO location (location) VALUES (:location)";

            // Prepare the statement
            $stmt = $this->_connection->prepare($sql);

//            $format              = $data['format'];
//            $call_number         = $data['call_number'];
            $location            = $data['location'];
            $location            = $this->trimString($location);
//            $access_restrictions = $data['access_restrictions'];
//            $eres_display        = $data['eres_display'];
//            $display_note        = $data['display_note'];
//            $helpguide           = $data['helpguide'];
//            $citation_guide      = $data['citation_guide'];
//            $ctags               = $data['ctags'];
//            $trial_start         = $data['trial_start'];
//            $trial_end           = $data['trial_end'];
//            $record_status       = $data['record_status'];
            //var_dump($data);

            // Bind the parameters
//            $stmt->bindParam(':format', $format);
//            $stmt->bindParam(':call_number', $call_number);
            $stmt->bindParam(':location', $location);
//            $stmt->bindParam(':access_restrictions', $access_restrictions);
//            $stmt->bindParam(':eres_display', $eres_display);
//            $stmt->bindParam(':display_note', $display_note);
//            $stmt->bindParam(':helpguide', $helpguide);
//            $stmt->bindParam(':citation_guide', $citation_guide);
//            $stmt->bindParam(':ctags', $ctags);
//            $stmt->bindParam(':trial_start', $trial_start);
//            $stmt->bindParam(':trial_end', $trial_end);
//            $stmt->bindParam(':record_status', $record_status);

            // Execute the prepared statement
            $stmt->execute();

            var_dump($stmt);

            // Check for successful execution or handle any errors
            if ($stmt->rowCount() > 0) {
                return  $this->_db->last_id();
            }

        } catch (\PDOException $exception) {
            return "location table insert failed" . $exception->getMessage();
        }

    }

    public function insertLocationTitle($data) {

        try {
            // Prepare the SQL statement
            $sql = "INSERT INTO location_title (location_id, title_id) VALUES (:location_id, :title_id)";
            // Prepare the statement
            $stmt = $this->_connection->prepare($sql);

            $location_id = $data['location_id'];
            $title_id = $data['title_id'];

            // Bind the parameters
            $stmt->bindParam(':location_id', $location_id);
            $stmt->bindParam(':title_id', $title_id);

            // Execute the prepared statement
            $stmt->execute();
            // Check for successful execution or handle any errors
            if ($stmt->rowCount() > 0) {
                return $this->_db->last_id();
            }

        } catch (\PDOException $exception) {
            return "An error returned when attempting to insert a location_title: " . $exception->getMessage();
        }
    }
    public function insertRank($data) {

        try {
            // Prepare the SQL statement
            $sql = "INSERT INTO `rank` (`rank`, subject_id, title_id, source_id, description_override, dbbysub_active) VALUES (?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $stmt = $this->_connection->prepare($sql);

            $rank                 = $data['rank'];
            $subject_id           = $data['subject_id'];
            $title_id             = $data['title_id'];
            $source_id            = $data['source_id'];
            $description_override = $data['description_override'];
            $dbbysub_active       = $data['dbbysub_active'];

            // Bind the parameters
            $stmt->bindParam(1, $rank);
            $stmt->bindParam(2, $subject_id);
            $stmt->bindParam(3, $title_id);
            $stmt->bindParam(4, $source_id);
            $stmt->bindParam(5, $description_override);
            $stmt->bindParam(6, $dbbysub_active);

            // Execute the prepared statement
            $stmt->execute();

            // Check for successful execution or handle any errors
            if ($stmt->rowCount() > 0) {
                return $this->_db->last_id();
            } else {
                return "failed insert of rank";
            }

        } catch (\PDOException $exception) {
            return  "An error returned when attempting to insert a rank: " . $exception->getMessage();
        }
    }



}