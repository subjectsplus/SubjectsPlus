<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the FAQ control page and create a new FAQ');
TestCommons::logMeIn($I);
$I->amOnPage('control/faq/');
$I->see('View FAQs');
$I->click('CREATE FAQ');




$I->see('Edit FAQ');

