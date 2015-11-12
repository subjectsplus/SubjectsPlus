<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 7/1/15
 * Time: 3:34 PM
 */

namespace SubjectsPlus\Control;

use SubjectsPlus\Control\Querier;


class FavoritePluslet {


    private $_staff_id;

    public function __construct() {

    }


    public function getFavoritePluslets($staff_id = null) {

        if($staff_id == null) {
            $staff_id = $this->_staff_id;
        }

        $querier = new Querier();
        $sql = "SELECT p.pluslet_id as 'id', p.title AS 'title', p.type AS 'type', t.tab_index AS 'tab_index' FROM pluslet AS p
                   INNER JOIN pluslet_section AS ps
                   ON ps.pluslet_id = p.pluslet_id
                   INNER JOIN section AS s
                   ON ps.section_id = s.section_id
                   INNER JOIN tab AS t
                   ON s.tab_id = t.tab_id
                   INNER JOIN subject AS subject
                   ON t.subject_id = subject.subject_id
                   INNER JOIN staff_subject AS staff_sub
                   ON subject.subject_id = staff_sub.subject_id
                   WHERE p.favorite_box = 1
                   AND staff_sub.staff_id = $staff_id";

        $favorites = $querier->query($sql);

        return $favorites;
    }




    /**
     * @return mixed
     */
    public function getStaffId()
    {
        return $this->_staff_id;
    }

    /**
     * @param mixed $staff_id
     */
    public function setStaffId($staff_id)
    {
        $this->_staff_id = $staff_id;
    }



}