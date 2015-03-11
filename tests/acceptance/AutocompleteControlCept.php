<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('make sure that autocomplete is working in control');
TestCommons::logMeIn($I);

// Autocomplete for home page
$I->amOnPage('control/');
$I->fillField('#sp_search', 'test');
$I->waitForElement('.ui-autocomplete-category');
$I->seeElement('.ui-autocomplete-category');

// Autocomplete for records page
$I->amOnPage('control/records');
$I->fillField('#sp_search', 'gene');
$I->waitForElement('.ui-autocomplete-category');
$I->seeElement('.ui-autocomplete-category');

// Autocomplete for Guides page
$I->amOnPage('control/guides');
$I->fillField('#sp_search', 'test');
$I->waitForElement('.ui-autocomplete-category');
$I->seeElement('.ui-autocomplete-category');


// Autocomplete for Admin page 

$I->amOnPage('control/');
$I->fillField('#sp_search', 'admin');
$I->waitForElement('.ui-autocomplete-category');
$I->seeElement('.ui-autocomplete-category');
