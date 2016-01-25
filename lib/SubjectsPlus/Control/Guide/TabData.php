<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 11/10/15
 * Time: 11:55 AM
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;

use SubjectsPlus\Control\Interfaces\OutputInterface;

class TabData implements OutputInterface
{

    private $db;

    public function __construct(Querier $db) {

        $this->db = $db;
    }


    public function loadTabs($status_filter = "", $subject_id = null) {

        $connection = $this->db->getConnection();
        switch( $status_filter )
        {
            case 'hidden':
                // Find our existing tabs for this guide that is hidden
                $statement = $connection->prepare("SELECT DISTINCT tab_id, label, tab_index, external_url, visibility FROM tab WHERE subject_id = :subject_id AND visibility = 0 ORDER BY tab_index");
                break;
            case 'public':
                // Find our existing tabs for this guide that is public
                $statement = $connection->prepare("SELECT DISTINCT tab_id, label, tab_index, external_url, visibility FROM tab WHERE subject_id = :subject_id AND visibility = 1 ORDER BY tab_index");
                break;
            default:
                // Find ALL our existing tabs for this guide
                $statement = $connection->prepare("SELECT DISTINCT tab_id, label, tab_index, external_url, visibility FROM tab WHERE subject_id = :subject_id ORDER BY tab_index");
                break;
        }

        $statement->bindParam(":subject_id", $subject_id);
        $statement->execute();
        $tabs = $statement->fetchAll();

        $this->tabs = $tabs;

    }

    public function saveTabOrder($data) {

        if(isset($data)) {

            $db = $this->db;

            parse_str($data['data'], $str);

            $tabs = $str['item'];

            foreach($tabs as $key => $value) {

                $q = "UPDATE tab SET tab_index =" . $db->quote(scrubData($key) ). " WHERE tab_id = " . $value;
                $db->exec($q);

            }

        }
    }


    public function toArray() {
        return get_object_vars ( $this );
    }
    public function toJSON() {
        return json_encode ( get_object_vars ( $this ) );
    }

}