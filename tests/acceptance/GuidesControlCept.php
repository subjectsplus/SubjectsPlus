<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the Guides control page and create a guide');
TestCommons::logMeIn($I);
$I->amOnPage('control/guides/');
$I->see('Edit Your Guides');
$I->click('Create new guide');
$I->see('Create New Guide');
$I->fillField('Guide', 'Testing guide');
$I->fillField('Short Form', 'testingguide');
$I->click('Save Now');
$I->see('Thy');
