<?php

namespace SubjectsPlus\Control\AzRecord;

use SubjectsPlus\Control\Querier;

class RecordInsertFromCSV
{

    private $_db;
    private $_title_id;

    private $_location_id;
    private $_subject_id;

    public function __construct($db) {
        $this->_db = $db;
        $this->_staff_id = $_SESSION['staff_id'];
    }

    public function insertFromCSV($filepath) {
        // CSV file path and name
        $csvFile = $filepath;

        // Open the CSV file
        if (($handle = fopen($csvFile, "r")) !== false) {
            // Read the CSV file row by row
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // set vars from csv table data
                $subject = $data[0];
                $title = $data[1];
                $location = $data[2];
                $alternate_title = $data[3];
                $description = $data[4];
                $internal_notes = $data[5];
                $pre = $data[6];
                $format = $data[7];
                $access_restrictions = $data[8];
                $eres_display = $data[9];
                $record_status = $data[10];

                // random vars needed for title
                $last_modified_by = $this->_staff_id;
                $last_modified = date('Y-m-d H:i:s', strtotime('now'));

                // set title_data for insert into title table
                $title_data['title'] = $title;
                $title_data['alternate_title'] = $alternate_title;
                $title_data['description'] = $description;
                $title_data['internal_notes'] = $internal_notes;
                $title_data['pre'] = $pre;
                $title_data['last_modified_by'] = $last_modified_by;
                $title_data['last_modified'] = $last_modified;

                // insert data into title table
                $this->_title_id = $this->insertTitle($title_data);

                // set data array to insert data into location
                $location_data['format'] = $format;
                $location_data['call_number'] = null;
                $location_data['location'] = $location;
                $location_data['access_restrictions'] = $access_restrictions;
                $location_data['eres_display'] = $eres_display;
                $location_data['display_note'] = null;
                $location_data['helpguide'] = null;
                $location_data['citation_guide'] = null;
                $location_data['ctags'] = null;
                $location_data['trial_start'] = null;
                $location_data['trial_end'] = null;
                $location_data['record_status'] = $record_status;

                // insert into location table
                $this->_location_id = $this->insertLocation($location_data);

                // set location_title_data to insert into location_title table
                $location_title_data['location_id'] = $this->_location_id;
                $location_title_data['title_id'] = $this->_title_id;

                // insert into location_title table
                $this->insertLocationTitle($location_title_data);

                // fetch subject id for rank table - $subject is from csv
                $subject_match = $subject . '%(e)';
                $this->_subject_id = $this->fetchSubjectIdLikeSubject($subject);

                // set rank data to insert into rank table
                $rank_data['rank'] = 0;
                $rank_data['subject_id'] = $this->_subject_id;
                $rank_data['title_id'] = $this->_title_id;
                $rank_data['source_id'] = 1;
                $rank_data['description_override'] = null;
                $rank_data['dbbysub_active'] = null;

                // insert into rank table
                $this->insertRank($rank_data);


            }
            fclose($handle);
        }
    }

    public function fetchSubjectIdLikeSubject($subject) {

        $sql = "SELECT subject_id FROM subject WHERE subject LIKE '$subject'";
        // Execute the SQL statement
        return $this->_db->exec($sql);
    }

    public function insertTitle($data) {

        $title = $data['title'];
        $alternate_title = $data['alternate_title'];
        $description = $data['description'];
        $internal_notes = $data['internal_notes'];
        $pre = $data['pre'];
        $last_modified_by = $data['last_modified_by'];
        $last_modified = $data['last_modified'];

        // Prepare the SQL statement
        $sql = "INSERT INTO title (title, 
                   alternate_title, 
                   description, 
                   internal_notes, 
                   pre,
                   last_modified_by,
                   last_modified) 
                VALUES ('$title',
                        '$alternate_title',
                        '$description',
                        '$internal_notes',
                        '$pre',
                        '$last_modified_by',
                        '$last_modified')";

        // Execute the SQL statement
        $this->_db->exec($sql);

        // return the last inserted title_id
        return $this->_db->last_id();
    }

    public function insertRank($data) {

        $rank = $data['rank'];
        $subject_id = $data['subject_id'];
        $title_id = $data['title_id'];
        $source_id = $data['source_id'];
        $description_override = $data['description_override'];
        $this->_dbbysub_active = $data['dbbysub_active'];

        $sql = "INSERT INTO `rank` (`rank`,
                      subject_id, 
                      title_id, 
                      source_id, 
                      description_override, 
                      dbbysub_active) 
                VALUES ('$rank',
                        '$subject_id',
                        '$title_id',
                        '$source_id',
                        '$description_override',
                        '$this->_dbbysub_active')";

        // Execute the SQL statement
        $this->_db->exec($sql);
    }

    public function insertLocation($data) {

        $format = $data['format'];
        $call_number = $data['call_number'];
        $location = $data['location'];
        $access_restrictions = $data['access_restrictions'];
        $eres_display = $data['eres_display'];
        $display_note = $data['display_note'];
        $helpguide = $data['helpguide'];
        $citation_guide = $data['citation_guide'];
        $ctags = $data['ctags'];
        $trial_start = $data['trial_start'];
        $trial_end = $data['trial_end'];
        $record_status = $data['record_status'];

        // Prepare the SQL statement
        $sql = "INSERT INTO location (format,
                      call_number, 
                      location, 
                      access_restrictions, 
                      eres_display, 
                      display_note, 
                      helpguide, 
                      citation_guide, 
                      ctags, 
                      trial_start, 
                      trial_end, 
                      record_status) 
                VALUES ('$format',
                        '$call_number',
                        '$location',
                        '$access_restrictions',
                        '$eres_display',
                        '$display_note',
                        '$helpguide',
                        '$citation_guide',
                        '$ctags',
                        '$trial_start',
                        '$trial_end',
                        '$record_status')";

        // Execute the SQL statement
        $this->_db->exec($sql);

        return $this->_db->last_id();
    }

    public function insertLocationTitle($data) {

        $location_id = $data['location_id'];
        $title_id = $data['title_id'];

        // Prepare the SQL statement
        $sql = "INSERT INTO location_title (location_id, title_id) 
                VALUES ('$location_id', '$title_id')";

        // Execute the SQL statement
        $this->_db->exec($sql);
    }
}