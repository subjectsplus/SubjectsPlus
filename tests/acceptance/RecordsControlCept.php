<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the Records control page and create new record');
TestCommons::logMeIn($I);
$I->amOnPage('control/records/');
$I->see('Browse Records');
$I->click('Create new item');
$I->see('Record');
$I->fillField('Record Title', 'Testing record');
$I->fillField('//*[@id="new_record"]/div[2]/div[1]/div[2]/input[2]', 'http://example.com');
$I->selectOption('//*[@id="new_record"]/div[3]/div[3]/div[2]/select', 'General');
$I->click('Save Record Now');
$I->see('Thy');
