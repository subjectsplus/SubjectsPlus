<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Check the link checker');
TestCommons::logMeIn($I);
$I->amOnPage('/control/guides/link_checker.php');
$I->selectOption('//*[@id="maincontent"]/div/div[1]/div/div[2]/form/select', 'General');
$I->click('Check Links In Guide');
$I->see('Would you like to mail this report to someone?');

