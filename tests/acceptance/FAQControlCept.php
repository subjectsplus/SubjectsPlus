<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the FAQ control page and create a new FAQ');
TestCommons::logMeIn($I);
$I->amOnPage('control/faq/');
$I->see('View FAQs');
$I->click('CREATE FAQ');
$I->see('Edit FAQ');


$I->switchTo('');
$I->fillField('//*[@id="new_record"]/div/div[1]/div/div[2]/textarea[1]', 'Testing?');
$I->fillField('#cke_1_contents', 'Yes.');
//$I->switchToWindow();
$I->click('Save Now');
$I->see('Thy');
