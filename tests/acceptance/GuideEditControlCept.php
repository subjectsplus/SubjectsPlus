<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('edit a guide');
TestCommons::logMeIn($I);
$I->amOnPage('control/guides/');
$I->see('Edit Your Guides');
$I->click('//*[@id="maincontent"]/div/div[1]/div/div[2]/div[1]/a[4]');
$I->see('Drop Here');
$I->moveMouseOver('#newbox');
$I->dragAndDrop('//*[@id="pluslet-id-Basic"]','//*[@id="dropspot-left-1"]');
$I->see('SAVE CHANGES');
$I->waitForElement('.cke_chrome');
$I->fillField(['name'=>'new_pluslet_title'], 'Testing');
$I->click('Save Changes');
$I->see('Thy');
