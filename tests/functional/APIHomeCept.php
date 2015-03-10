<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('see if the API help page is showing up');
$I->amOnPage('/api/index.php');
$I->see("No service selected.");
