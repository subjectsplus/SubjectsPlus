<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('see if the API is returning a help message');
$I->amOnPage('api/');
$I->see('No service selected');
