<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('edit a guide');
TestCommons::logMeIn($I);
$I->amOnPage('control/guides/');
$I->see('Edit Your Guides');
$I->click('//*[@id="maincontent"]/div/div[1]/div/div[2]/div[1]/a[4]');


// Test dropping a new box
/*

$I->moveMouseOver('#newbox');
$I->moveMouseOver('#newbox');
$I->waitForElement('#pluslet-id-Basic');
$I->seeElement('#pluslet-id-Basic');
$I->dragAndDrop('#pluslet-id-Basic', '#dropspot-left-1');

$I->waitForElement('#save_guide');
$I->seeElement('#save_guide');

//$I->fillField('//*[@id="container-0"]/div[2]/div[1]/div[1]/div[1]/input', 'Testing');


// Test new tab 

$I->waitForElement('#add_tab');
$I->click('#add_tab');
$I->fillField('#tab_title',"Testing");
$I->click('Add');
$I->see('Drop Here');


// Test saving
$I->click('SAVE CHANGES');
$I->click('SAVE CHANGES');
$I->waitForElement('#response');
$I->see('Thy');
*/
