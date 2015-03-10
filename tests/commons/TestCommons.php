<?php 
class TestCommons
{
    public static $username = 'j.little@miami.edu';
    public static $password = 'password';

    public static function logMeIn($I)
    {
        $I->amOnPage('control/login.php');
        $I->see('Please enter your');
        $I->fillField('Email', self::$username);
        $I->fillField('Password', self::$password);
        $I->click('Submit');
    }
}
