<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('see if autocomplete is working on the front end');


// Databases page
$I->amOnPage('subjects/databases.php');
$I->fillField('#quick_search', 'samp');
$I->waitForElement('.ui-autocomplete-category');
$I->see('Sample Record');

$I->amOnPage('subjects/staff.php');
$I->fillField('#quick_search','admin');
$I->waitForElement('.ui-autocomplete-category');
$I->see('Super Admin');


