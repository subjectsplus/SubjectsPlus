<?php 
$I = new AcceptanceTester($scenario);
$I->amOnPage('/subjects');
$I->see('Subjects');
