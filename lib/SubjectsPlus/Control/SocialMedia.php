<?php



namespace SubjectsPlus\Control;

use SubjectsPlus\Control\Interfaces\OutputInterface;
use SubjectsPlus\Control\Querier;



class SocialMedia implements OutputInterface {


    public $socialMediaAccounts = array(

        array(
            'name' => 'Facebook',
            'url'  => 'http://facebook.com/',
        ),
        array(
            'name' => 'Twitter',
            'url'  => 'http://twitter.com/',
        ),
        array(
            'name' => 'Pinterest',
            'url'  => 'http://pinterest.com/',
        ),
        array(
            'name' => 'Instagram',
            'url'  => 'http://instagram.com/',
        )

    );

    public function __construct() {

    }

    public function toArray() {
        return $this->socialMediaAccounts;
    }

    public function toJSON() {
        return "";
    }


}