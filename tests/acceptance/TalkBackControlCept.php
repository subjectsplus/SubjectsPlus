<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Visit the TalkBack control page');
TestCommons::logMeIn($I);
$I->amOnPage('control/talkback/');
$I->see('View and Answer TalkBacks');
