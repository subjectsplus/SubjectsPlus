<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('to see if the API is returning a response for databases');
$I->amOnPage('api/database/letter/S/key/2dGEqeslKuPU58L7nyVy');
$I->see("Sample Record");
