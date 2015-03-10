<?php 
$I = new AcceptanceTester($scenario);
$I->amOnPage('subjects/databases.php');
$I->see('Database List');

