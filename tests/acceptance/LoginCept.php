<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Login and and taken to the admin interface');
TestCommons::logMeIn($I);
$I->see('Hello Super Admin');
