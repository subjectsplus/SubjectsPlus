<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the Records control page and create new record');
TestCommons::logMeIn($I);
$I->amOnPage('control/records/');
$I->see('Browse Records');
$I->click('Create new item');
$I->see('Record');
$I->fillField('Record Title', 'Testing record');
$I->fillField('Prefix', 'T');
$I->fillField('Alternate Title', 'Testing');

// Add a location and and check the url
$I->fillField('//*[@id="new_record"]/div[2]/div[1]/div[2]/input[2]', 'http://example.com');
//$I->click('//*[@id="new_record"]/div[2]/div[1]/div[2]/span[1]');
//$I->seeElement('#check_url', ['title'=>'This URL looks OK to me']);
//$I->selectOption('//*[@id="new_record"]/div[3]/div[3]/div[2]/select', 'General');

// Add another location
//$I->click('Add another location');
//$I->seeElement('.new_location');
//$I->fillField('//*[@id="new_record"]/div[2]/div[2]/div/div[2]/input[2]', 'http://example.net');


// Save the record

$I->selectOption('subject_id[]','test');


$I->click('Save Record Now');
$I->see('Thy');
