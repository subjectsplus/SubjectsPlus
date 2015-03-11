<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the Videos control page');
TestCommons::logMeIn($I);
$I->amOnPage('control/videos/');
$I->see('Videos Visible');
