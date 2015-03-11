<?php 
class TestCommons
{
    public static $username = 'admin@admin.edu';
    public static $password = 'password';

    public static function logMeIn($I)
    {
        $I->amOnPage('control/login.php');
        $I->see('Please enter your');
        $I->fillField('Email', self::$username);
        $I->fillField('Password', self::$password);
        $I->click('Submit');
    }
    
    public static function findElement($xpath)
    {
        $webDriver = $this->getModule('WebDriver')->webDriver;
        $elem = $webDriver->findElement(\WebDriverBy::xpath($selector));
        
        return $elem;
    }

}
