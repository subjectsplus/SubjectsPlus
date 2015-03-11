<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('edit a guide');
TestCommons::logMeIn($I);
$I->amOnPage('control/guides/');
$I->see('Edit Your Guides');
$I->click('//*[@id="maincontent"]/div/div[1]/div/div[2]/div[1]/a[4]');


// Test dropping a new box


$I->moveMouseOver('#newbox');
$I->dragAndDrop('//*[@id="pluslet-id-Basic"]','//*[@id="dropspot-left-1"]');

$I->see('SAVE CHANGES');
$I->waitForElement('.cke_chrome');
$I->fillField(['name'=>'new_pluslet_title'], 'Testing');


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
